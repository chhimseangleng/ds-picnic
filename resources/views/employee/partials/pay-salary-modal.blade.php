<!-- Pay Salary Modal -->
<div id="paySalaryModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-indigo-600 text-white px-6 py-4 rounded-t-lg">
            <h2 class="text-xl font-semibold">💰 Pay Salary</h2>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('employees.salary.pay') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="employeeID" id="employeeID">

            <!-- Employee Info Display -->
            <div class="mb-6 p-4 bg-indigo-50 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Employee:</p>
                        <p class="text-lg font-semibold text-gray-800" id="employeeName"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Monthly Salary:</p>
                        <p class="text-lg font-bold text-indigo-600" id="employeeSalaryDisplay"></p>
                    </div>
                </div>
            </div>

            <!-- Amount Input -->
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                    Payment Amount <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    step="0.01" 
                    name="amount" 
                    id="amount" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100"
                    placeholder="Enter payment amount"
                >
            </div>

            <!-- Payment Period Input -->
            <div class="mb-4">
                <label for="paymentPeriod" class="block text-sm font-medium text-gray-700 mb-2">
                    Payment Period <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="paymentPeriod" 
                    id="paymentPeriod" 
                    required 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100"
                    placeholder="e.g., January 2025, Week 1-7 Dec"
                >
            </div>

            <!-- Notes Input -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes (Optional)
                </label>
                <textarea 
                    name="notes" 
                    id="notes" 
                    rows="3" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100"
                    placeholder="Add any notes about this payment..."
                ></textarea>
            </div>

            <!-- Modal Actions -->
            <div class="flex gap-3 justify-end">
                <button 
                    type="button" 
                    onclick="closePaySalaryModal()" 
                    class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-md font-medium hover:bg-gray-300 transition-all"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-md font-medium hover:bg-indigo-700 transition-all"
                >
                    Process Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPaySalaryModal(employeeID, employeeName, salary, selectedPeriod) {
        document.getElementById('paySalaryModal').classList.remove('hidden');
        document.getElementById('employeeID').value = employeeID;
        document.getElementById('employeeName').textContent = employeeName;
        document.getElementById('employeeSalaryDisplay').textContent = '$' + parseFloat(salary).toFixed(2);
        document.getElementById('amount').value = parseFloat(salary).toFixed(2);
        
        // Use the selected period from the filter
        document.getElementById('paymentPeriod').value = selectedPeriod;
        
        // Make the payment period field readonly to match the filtered period
        document.getElementById('paymentPeriod').setAttribute('readonly', 'readonly');
    }

    function closePaySalaryModal() {
        document.getElementById('paySalaryModal').classList.add('hidden');
        // Reset form
        document.getElementById('employeeID').value = '';
        document.getElementById('amount').value = '';
        document.getElementById('paymentPeriod').value = '';
        document.getElementById('notes').value = '';
    }

    // Close modal when clicking outside
    document.getElementById('paySalaryModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePaySalaryModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePaySalaryModal();
        }
    });
</script>
