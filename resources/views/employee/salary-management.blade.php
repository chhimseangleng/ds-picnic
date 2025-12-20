<x-app-layout>
    <div class="p-4 md:p-8 bg-gray-50 min-h-screen">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8 bg-white px-4 md:px-8 py-6 rounded-lg shadow">
            <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Salary Management</h1>
            <a href="{{ route('employees') }}" class="w-full md:w-auto text-center bg-gray-600 text-white px-6 py-2.5 rounded-md font-medium text-sm hover:bg-gray-700 transition-all">
                ← Back to Employees
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg mb-6 font-medium">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-500 text-white px-6 py-4 rounded-lg mb-6 font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Month/Year Filter Section -->
        <div class="bg-white px-4 md:px-8 py-6 rounded-lg mb-8 shadow">
            <form method="GET" action="{{ route('employees.salary.management') }}" class="flex flex-col md:flex-row gap-4 md:gap-6 md:items-end">
                <div class="flex flex-col gap-2 flex-1">
                    <label class="text-sm font-medium text-gray-600">Month</label>
                    <select name="month" id="monthSelect" class="px-4 py-2.5 border border-gray-300 rounded-md text-sm w-full focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                        @php
                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            $currentYear = now()->year;
                            $currentMonth = now()->month;
                        @endphp
                        @foreach($months as $index => $month)
                            @php
                                $monthNumber = $index + 1;
                                // Only hide past months if the SELECTED year is the current year
                                // For future years, show all months
                                $hidePast = ($selectedYear == $currentYear && $monthNumber < $currentMonth);
                            @endphp
                            @if(!$hidePast)
                                <option value="{{ $monthNumber }}" {{ $selectedMonth == $monthNumber ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-2 flex-1">
                    <label class="text-sm font-medium text-gray-600">Year</label>
                    <select name="year" id="yearSelect" class="px-4 py-2.5 border border-gray-300 rounded-md text-sm w-full focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                        @for($year = now()->year; $year <= now()->year + 2; $year++)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="w-full md:w-auto bg-indigo-600 text-white px-8 py-2.5 rounded-md font-medium text-sm hover:bg-indigo-700 transition-all">
                    Filter
                </button>

                <div class="text-center md:text-right md:flex-1">
                    <p class="text-sm text-gray-600">Currently viewing:</p>
                    <p class="text-lg font-semibold text-indigo-600">{{ $selectedPeriod }}</p>
                </div>
            </form>
        </div>

        <!-- Future Month Warning -->
        @if($isFutureMonth)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 px-6 py-4 rounded-lg mb-8">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-yellow-800">Future Month Selected</h3>
                        <p class="text-sm text-yellow-700">Salary payments for <strong>{{ $selectedPeriod }}</strong> are not yet available. You can only pay salaries for the current month or past months.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Employee Salary Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @forelse($employees as $employee)
                <div class="bg-white rounded-xl p-6 shadow hover:-translate-y-1 hover:shadow-xl transition-all flex flex-col {{ $isFutureMonth ? 'border-2 border-gray-300' : ($employee->is_paid ? 'border-2 border-green-200' : 'border-2 border-orange-200') }}">
                    <!-- Payment Status Badge -->
                    <div class="mb-3">
                        @if($isFutureMonth)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                🔒 UNAVAILABLE - Future Month
                            </span>
                        @elseif($employee->is_paid)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                ✓ PAID for {{ $selectedPeriod }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                ⚠ UNPAID for {{ $selectedPeriod }}
                            </span>
                        @endif
                    </div>

                    <!-- Employee Header -->
                    <div class="flex items-center gap-4 mb-4">
                        <!-- Avatar -->
                        <div class="w-16 h-16 rounded-full overflow-hidden flex-shrink-0">
                            @if($employee->image_url)
                                <img src="{{ $employee->image_url }}" alt="{{ $employee->name }}" class="w-full h-full object-cover">
                            @else
                                @php
                                    $colors = ['bg-gradient-to-br from-pink-400 to-red-500', 'bg-gradient-to-br from-blue-400 to-cyan-500', 'bg-gradient-to-br from-green-400 to-teal-400', 'bg-gradient-to-br from-pink-400 to-yellow-400', 'bg-gradient-to-br from-teal-300 to-pink-200'];
                                    $colorClass = $colors[$loop->index % 5];
                                @endphp
                                <div class="w-full h-full {{ $colorClass }} flex items-center justify-center text-xl font-semibold text-white">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Employee Info -->
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $employee->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $employee->role }}</p>
                        </div>
                    </div>

                    <!-- Salary Info -->
                    <div class="mb-4 p-4 bg-indigo-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 font-medium">Monthly Salary:</span>
                            <span class="text-xl font-bold text-indigo-600">
                                ${{ number_format($employee->salary ?? 0, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Payment Details for Paid Employees -->
                    @if($employee->is_paid && $employee->payment_details)
                        <div class="mb-4 p-4 bg-green-50 rounded-lg border border-green-200">
                            <h4 class="text-sm font-semibold text-green-800 mb-2">Payment Details:</h4>
                            <div class="space-y-1">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Amount Paid:</span>
                                    <span class="font-bold text-green-700">${{ number_format($employee->payment_details->amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Payment Date:</span>
                                    <span class="text-gray-800">{{ \Carbon\Carbon::parse($employee->payment_details->paymentDate)->format('M d, Y') }}</span>
                                </div>
                                @if($employee->payment_details->notes)
                                    <div class="mt-2 pt-2 border-t border-green-200">
                                        <p class="text-xs text-gray-600"><strong>Notes:</strong> {{ $employee->payment_details->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Recent Payments -->
                    @if($employee->recent_payments && count($employee->recent_payments) > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Recent Payments:</h4>
                            <div class="space-y-2">
                                @foreach($employee->recent_payments->take(3) as $payment)
                                    <div class="flex justify-between text-xs bg-gray-50 p-2 rounded">
                                        <span class="text-gray-600">{{ $payment->paymentPeriod }}</span>
                                        <span class="font-semibold text-gray-800">${{ number_format($payment->amount, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-4 text-sm text-gray-500 italic">
                            No payment history yet
                        </div>
                    @endif

                    <!-- Pay Salary Button -->
                    @if($isFutureMonth)
                        <button 
                            disabled
                            class="w-full mt-auto bg-gray-300 text-gray-500 px-4 py-2.5 rounded-md font-medium text-sm cursor-not-allowed"
                        >
                            🔒 Not Available Yet
                        </button>
                    @elseif($employee->is_paid)
                        <button 
                            disabled
                            class="w-full mt-auto bg-gray-300 text-gray-500 px-4 py-2.5 rounded-md font-medium text-sm cursor-not-allowed"
                        >
                            ✓ Already Paid
                        </button>
                    @else
                        <button 
                            onclick="openPaySalaryModal('{{ $employee->_id }}', '{{ $employee->name }}', {{ $employee->salary ?? 0 }}, '{{ $selectedPeriod }}')"
                            class="w-full mt-auto bg-indigo-600 text-white px-4 py-2.5 rounded-md font-medium text-sm hover:bg-indigo-700 transition-all"
                        >
                            💰 Pay Salary
                        </button>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-gray-500 text-base">
                    <p>No active employees found.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Include Pay Salary Modal -->
    @include('employee.partials.pay-salary-modal')
</x-app-layout>
