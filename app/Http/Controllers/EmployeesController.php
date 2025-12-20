<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalaryPayment;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeesController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by date
        if ($request->has('date_filter') && $request->date_filter) {
            $today = now();
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('startWork', $today->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('startWork', [
                        $today->startOfWeek()->toDateString(),
                        $today->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('startWork', $today->month)
                          ->whereYear('startWork', $today->year);
                    break;
            }
        }

        $employees = $query->orderBy('created_at', 'desc')->get();

        // Add image URLs to employees
        $employees->transform(function ($employee) {
            $employee->image_url = $this->getImageUrl($employee->profile_image);
            return $employee;
        });

        return view('employee.index', compact('employees'));
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->image_url = $this->getImageUrl($employee->profile_image);
        return view('employee.show', compact('employee'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'role' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'salary' => 'nullable|numeric|min:0',
            'startWork' => 'required|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $validated['working'] = true; // New employees are working by default
        $validated['stopWork'] = null;

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $this->uploadImageToS3($request->file('profile_image'), 'employee-profiles');
        }

        Employee::create($validated);

        return redirect()->route('employees')->with('success', 'Employee added successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'role' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'salary' => 'nullable|numeric|min:0',
            'startWork' => 'required|date',
            'working' => 'nullable|boolean',
            'stopWork' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $employee = Employee::findOrFail($id);

        // Handle profile image upload if new image provided
        if ($request->hasFile('profile_image')) {
            // Delete old image from S3 if it exists
            $this->deleteImageFromS3($employee->profile_image);
            // Upload new image
            $validated['profile_image'] = $this->uploadImageToS3($request->file('profile_image'), 'employee-profiles');
        }

        $employee->update($validated);

        return redirect()->route('employees.show', $id)->with('success', 'Employee updated successfully!');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        
        // Delete profile image from S3 if it exists
        $this->deleteImageFromS3($employee->profile_image);
        
        $employee->delete();

        return redirect()->route('employees')->with('success', 'Employee deleted successfully!');
    }

    /**
     * Display salary management page with month/year filter
     */
    public function salaryManagement(Request $request)
    {
        // Get filter parameters or default to current month/year
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);
        
        // Create the payment period search string (e.g., "December 2025")
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $selectedPeriod = $monthNames[$selectedMonth] . ' ' . $selectedYear;

        // Check if the selected period is in the future (after current month)
        $selectedDate = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1);
        $currentDate = now()->startOfMonth();
        $isFutureMonth = $selectedDate->greaterThan($currentDate);

        // Get all active employees
        $employees = Employee::where('working', true)
            ->orderBy('name')
            ->get();

        // Add image URLs, payment status, and recent payments to employees
        $employees->transform(function ($employee) use ($selectedPeriod) {
            $employee->image_url = $this->getImageUrl($employee->profile_image);
            
            // Check if employee has been paid for the selected period
            $payment = SalaryPayment::where('employeeID', $employee->_id)
                ->where('paymentPeriod', $selectedPeriod)
                ->first();
            
            $employee->is_paid = $payment !== null;
            $employee->payment_details = $payment;
            
            // Get recent salary payments for this employee (last 5)
            $employee->recent_payments = SalaryPayment::where('employeeID', $employee->_id)
                ->orderBy('paymentDate', 'desc')
                ->limit(5)
                ->get();
            
            return $employee;
        });

        return view('employee.salary-management', compact('employees', 'selectedMonth', 'selectedYear', 'selectedPeriod', 'isFutureMonth'));
    }

    /**
     * Process salary payment for an employee
     */
    public function paySalary(Request $request)
    {
        $validated = $request->validate([
            'employeeID' => 'required',
            'amount' => 'required|numeric|min:0',
            'paymentPeriod' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $employee = Employee::findOrFail($validated['employeeID']);

        // Check if employee has already been paid for this period
        $existingPayment = SalaryPayment::where('employeeID', $validated['employeeID'])
            ->where('paymentPeriod', $validated['paymentPeriod'])
            ->first();

        if ($existingPayment) {
            return redirect()->back()->with('error', 'Employee ' . $employee->name . ' has already been paid for ' . $validated['paymentPeriod'] . '!');
        }

        // Create salary payment record
        SalaryPayment::create([
            'employeeID' => $validated['employeeID'],
            'amount' => $validated['amount'],
            'paymentPeriod' => $validated['paymentPeriod'],
            'notes' => $validated['notes'] ?? null,
            'paidBy' => auth()->user()->id ?? null,
            'paymentDate' => now(),
        ]);

        // Record expense transaction in cashflow
        TransactionHistory::create([
            'amount' => $validated['amount'],
            'type' => 'expense',
            'description' => 'Salary payment: ' . $employee->name . ' (' . $validated['paymentPeriod'] . ')',
            'date' => now(),
        ]);

        return redirect()->back()->with('success', 'Salary paid successfully to ' . $employee->name . '!');
    }

    /**
     * Get the full S3 URL for an image path.
     */
    private function getImageUrl(?string $imagePath): ?string
    {
        if (empty($imagePath)) {
            return null;
        }
        return Storage::disk('s3')->url($imagePath);
    }

    /**
     * Upload an image file to S3.
     */
    private function uploadImageToS3($file, string $folder = 'employee-profiles'): ?string
    {
        try {
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('s3')->putFileAs($folder, $file, $filename);
            
            if ($path) {
                \Log::info('S3 Employee Image Upload Success: ' . $path);
                return $path;
            }
            
            \Log::error('S3 Employee Image Upload Failed: Path returned empty');
            return null;
        } catch (\Exception $e) {
            \Log::error('S3 Employee Image Upload Failed: ' . $e->getMessage());
            if (config('app.debug')) {
                throw $e;
            }
            return null;
        }
    }

    /**
     * Delete an image from S3.
     */
    private function deleteImageFromS3(?string $imagePath): bool
    {
        if (empty($imagePath)) {
            return false;
        }
        
        try {
            if (Storage::disk('s3')->exists($imagePath)) {
                Storage::disk('s3')->delete($imagePath);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('S3 Employee Image Delete Failed: ' . $e->getMessage());
            return false;
        }
    }
}
