<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cashflow Summary') }}
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Incoming -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-3">Incoming</h3>
                <p class="text-3xl font-bold text-green-600">$18,500</p>
                <p class="text-green-600 mt-2">+12% this month</p>
            </div>

            <!-- Outgoing -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-3">Outgoing</h3>
                <p class="text-3xl font-bold text-red-600">$12,100</p>
                <p class="text-red-600 mt-2">+6% this month</p>
            </div>

        </div>

        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold mb-4">Transaction Records</h3>

            <table class="w-full text-left">
                <thead class="border-b">
                <tr>
                    <th class="py-2">Type</th>
                    <th class="py-2">Amount</th>
                    <th class="py-2">Date</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-b">
                    <td class="py-2 text-green-600">Incoming</td>
                    <td>$1,200</td>
                    <td>Today</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 text-red-600">Outgoing</td>
                    <td>$550</td>
                    <td>Yesterday</td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
