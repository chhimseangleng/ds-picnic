<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cashflow Summary') }}
            </h2>
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="openBonusModal()" class="w-full sm:w-auto bg-green-600 text-white px-6 py-2.5 rounded-md font-medium text-sm hover:bg-green-700 transition-all">
                    + Add Bonus
                </button>
                <button onclick="openExpenseModal()" class="w-full sm:w-auto bg-red-600 text-white px-6 py-2.5 rounded-md font-medium text-sm hover:bg-red-700 transition-all">
                    + Add Expense
                </button>
            </div>
        </div>
    </x-slot>

    <div class="p-4 md:p-6">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg mb-6 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white shadow rounded-lg p-6 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-lg mb-2">Incoming (This Month)</h3>
                    <p class="text-3xl font-bold text-green-600">
                        ${{ number_format($totalIncome, 2) }}
                    </p>
                    <p class="{{ $incomeChange >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        {{ $incomeChange >= 0 ? '+' : '' }}{{ number_format($incomeChange, 1) }}% this month
                    </p>
                </div>

                <div class="bg-green-100 text-green-600 p-4 rounded-full">
                    <!-- Heroicon: Arrow Trending Up -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 17.25V21a.75.75 0 00.75.75H21a.75.75 0 000-1.5H4.5V17.25a.75.75 0 00-1.5 0z" />
                        <path
                            d="M4.5 15.75l5.47-5.47 3.53 3.53 5.72-5.72v2.66a.75.75 0 001.5 0V6a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.44l-5.19 5.19-3.53-3.53-6 6z" />
                    </svg>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-6 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-lg mb-2">Outgoing (This Month)</h3>
                    <p class="text-3xl font-bold text-red-600">
                        ${{ number_format($totalExpense, 2) }}
                    </p>
                    <p class="{{ $expenseChange >= 0 ? 'text-red-600' : 'text-green-600' }} mt-2">
                        {{ $expenseChange >= 0 ? '+' : '' }}{{ number_format($expenseChange, 1) }}% this month
                    </p>
                </div>

                <div class="bg-red-100 text-red-600 p-4 rounded-full">
                    <!-- Heroicon: Arrow Trending Down -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 6.75A.75.75 0 013.75 6H21a.75.75 0 010 1.5H4.5v2.25a.75.75 0 01-1.5 0V6.75z" />
                        <path
                            d="M4.5 8.25l5.47 5.47 3.53-3.53 5.72 5.72v-2.66a.75.75 0 011.5 0V18a.75.75 0 00-.75.75h-4.5a.75.75 0 010-1.5h2.44l-5.19-5.19-3.53 3.53-6-6z" />
                    </svg>
                </div>

            </div>
            <div class="bg-white shadow rounded-lg p-6 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-lg mb-2">Net Profit</h3>
                    <p class="text-3xl font-bold text-blue-600">
                        ${{ number_format($totalIncome - $totalExpense, 2) }}
                    </p>
                </div>

                <div class="bg-blue-100 text-blue-600 p-4 rounded-full">
                    <!-- Heroicon: Wallet -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M2.25 7.5A3 3 0 015.25 4.5h13.5a3 3 0 013 3v.75h-6a3 3 0 000 6h6v.75a3 3 0 01-3 3H5.25a3 3 0 01-3-3v-7.5z" />
                        <path d="M16.5 9.75a1.5 1.5 0 100 3h5.25v-3H16.5z" />
                    </svg>
                </div>




            </div>



        </div>

        <!-- Month/Year Filter Section -->
        <div class="bg-white px-4 md:px-8 py-6 rounded-lg mb-6 shadow">
            <form method="GET" action="{{ route('cashflow') }}" class="flex flex-col md:flex-row gap-4 md:gap-6 md:items-end">
                <div class="flex flex-col gap-2 flex-1">
                    <label class="text-sm font-medium text-gray-600">Month</label>
                    <select name="month" class="px-4 py-2.5 border border-gray-300 rounded-md text-sm w-full focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                        @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $month)
                            <option value="{{ $index + 1 }}" {{ $selectedMonth == $index + 1 ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-2 flex-1">
                    <label class="text-sm font-medium text-gray-600">Year</label>
                    <select name="year" class="px-4 py-2.5 border border-gray-300 rounded-md text-sm w-full focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                        @for($year = now()->year - 2; $year <= now()->year; $year++)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="w-full md:w-auto bg-indigo-600 text-white px-8 py-2.5 rounded-md font-medium text-sm hover:bg-indigo-700 transition-all">
                    Filter
                </button>

                <div class="text-center md:text-right md:flex-1">
                    <p class="text-sm text-gray-600">Viewing:</p>
                    <p class="text-lg font-semibold text-indigo-600">
                        @php
                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        @endphp
                        {{ $months[$selectedMonth - 1] }} {{ $selectedYear }}
                    </p>
                </div>
            </form>
        </div>

        <div class="bg-white shadow rounded-lg p-4 md:p-6 mt-6">
            <h3 class="text-lg font-semibold mb-4">Transaction Records</h3>

            <div class="overflow-x-auto -mx-4 md:mx-0">
                <table class="w-full text-left">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 font-medium text-gray-600">Type</th>
                            <th class="py-3 px-4 font-medium text-gray-600">Description</th>
                            <th class="py-3 px-4 font-medium text-gray-600">Amount</th>
                            <th class="py-3 px-4 font-medium text-gray-600">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 px-4">
                                    @if($transaction->type === 'income')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Incoming
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Outgoing
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-gray-700">
                                    @if($transaction->type === 'income')
                                        @php
                                            $saleId = null;
                                            // Try to get sale_id directly
                                            if ($transaction->sale_id) {
                                                $saleId = $transaction->sale_id;
                                            } 
                                            // Fallback: parse orderID from description for old transactions
                                            elseif (preg_match('/Sale Order #(A-[0-9]+)/', $transaction->description, $matches)) {
                                                $orderID = $matches[1];
                                                $sale = \App\Models\Sale::where('orderID', $orderID)->first();
                                                $saleId = $sale ? $sale->_id : null;
                                            }
                                        @endphp
                                        
                                        @if($saleId)
                                            <a href="{{ route('sale.invoice', $saleId) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline font-medium transition-colors">
                                                {{ $transaction->description ?? 'No description' }}
                                            </a>
                                        @else
                                            {{ $transaction->description ?? 'No description' }}
                                        @endif
                                    @else
                                        {{ $transaction->description ?? 'No description' }}
                                    @endif
                                </td>
                                <td
                                    class="py-3 px-4 font-semibold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    ${{ number_format($transaction->amount, 2) }}
                                </td>
                                <td class="py-3 px-4 text-gray-500">
                                    {{ $transaction->date ? $transaction->date->format('M d, Y h:i A') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>

    </div>

    <!-- Add Bonus Modal -->
    <div id="bonusModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg mx-4">
            <!-- Modal Header -->
            <div class="bg-green-600 text-white px-6 py-4 rounded-t-lg">
                <h2 class="text-xl font-semibold">Add Bonus / Income</h2>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('cashflow.add-bonus') }}" method="POST" class="p-6">
                @csrf

                <!-- Name Input -->
                <div class="mb-4">
                    <label for="bonus_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Income Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="bonus_name" 
                        required 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-600 focus:ring-3 focus:ring-green-100"
                        placeholder="e.g., Sales Bonus, Client Payment, Service Fee"
                    >
                </div>

                <!-- Description Input -->
                <div class="mb-4">
                    <label for="bonus_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        id="bonus_description" 
                        required 
                        rows="3" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-600 focus:ring-3 focus:ring-green-100"
                        placeholder="Provide details about this income..."
                    ></textarea>
                </div>

                <!-- Amount Input -->
                <div class="mb-6">
                    <label for="bonus_amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500 font-medium">$</span>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="amount" 
                            id="bonus_amount" 
                            required 
                            min="0"
                            class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-green-600 focus:ring-3 focus:ring-green-100"
                            placeholder="0.00"
                        >
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex gap-3 justify-end">
                    <button 
                        type="button" 
                        onclick="closeBonusModal()" 
                        class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-md font-medium hover:bg-gray-300 transition-all"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 bg-green-600 text-white rounded-md font-medium hover:bg-green-700 transition-all"
                    >
                        Add Income
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div id="expenseModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg mx-4">
            <!-- Modal Header -->
            <div class="bg-red-600 text-white px-6 py-4 rounded-t-lg">
                <h2 class="text-xl font-semibold">Add General Expense</h2>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('cashflow.add-expense') }}" method="POST" class="p-6">
                @csrf

                <!-- Name Input -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Expense Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        required 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-red-600 focus:ring-3 focus:ring-red-100"
                        placeholder="e.g., Office Supplies, Utilities, Rent"
                    >
                </div>

                <!-- Description Input -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        required 
                        rows="3" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-red-600 focus:ring-3 focus:ring-red-100"
                        placeholder="Provide details about this expense..."
                    ></textarea>
                </div>

                <!-- Amount Input -->
                <div class="mb-6">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500 font-medium">$</span>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="amount" 
                            id="amount" 
                            required 
                            min="0"
                            class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:border-red-600 focus:ring-3 focus:ring-red-100"
                            placeholder="0.00"
                        >
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex gap-3 justify-end">
                    <button 
                        type="button" 
                        onclick="closeExpenseModal()" 
                        class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-md font-medium hover:bg-gray-300 transition-all"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 bg-red-600 text-white rounded-md font-medium hover:bg-red-700 transition-all"
                    >
                        Add Expense
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Bonus Modal Functions
        function openBonusModal() {
            document.getElementById('bonusModal').classList.remove('hidden');
        }

        function closeBonusModal() {
            document.getElementById('bonusModal').classList.add('hidden');
            // Reset form
            document.getElementById('bonus_name').value = '';
            document.getElementById('bonus_description').value = '';
            document.getElementById('bonus_amount').value = '';
        }

        // Close bonus modal when clicking outside
        document.getElementById('bonusModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBonusModal();
            }
        });

        // Expense Modal Functions
        function openExpenseModal() {
            document.getElementById('expenseModal').classList.remove('hidden');
        }

        function closeExpenseModal() {
            document.getElementById('expenseModal').classList.add('hidden');
            // Reset form
            document.getElementById('name').value = '';
            document.getElementById('description').value = '';
            document.getElementById('amount').value = '';
        }

        // Close modal when clicking outside
        document.getElementById('expenseModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeExpenseModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBonusModal();
                closeExpenseModal();
            }
        });
    </script>
</x-app-layout>