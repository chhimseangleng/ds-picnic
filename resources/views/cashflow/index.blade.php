<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cashflow Summary') }}
        </h2>
    </x-slot>

    <div class="p-6">

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

        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold mb-4">Transaction Records</h3>

            <div class="overflow-x-auto">
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
                                    {{ $transaction->description ?? 'No description' }}
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
</x-app-layout>