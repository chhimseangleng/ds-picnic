<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales Overview') }}
        </h2>
    </x-slot>

    <div class="p-6">

        <!-- Header Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Total Sales -->
            <div class="bg-white shadow rounded-lg p-6 flex justify-between">
                <div>
                    <div class="text-gray-500">Total Sales</div>
                    <div class="text-2xl font-bold text-blue-500 mt-1">$12,345</div>
                </div>
                <i class="fas fa-dollar-sign text-3xl text-blue-500"></i>
            </div>

            <!-- Today Sales -->
            <div class="bg-white shadow rounded-lg p-6 flex justify-between">
                <div>
                    <div class="text-gray-500">Today</div>
                    <div class="text-2xl font-bold text-green-500 mt-1">$450</div>
                </div>
                <i class="fas fa-calendar-day text-3xl text-green-500"></i>
            </div>

            <!-- Monthly Growth -->
            <div class="bg-white shadow rounded-lg p-6 flex justify-between">
                <div>
                    <div class="text-gray-500">Monthly Growth</div>
                    <div class="text-2xl font-bold text-yellow-500 mt-1">+14%</div>
                </div>
                <i class="fas fa-chart-line text-3xl text-yellow-500"></i>
            </div>

        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Recent Sales</h3>

            <table class="w-full text-left">
                <thead class="border-b">
                <tr>
                    <th class="py-2">Product</th>
                    <th class="py-2">Qty</th>
                    <th class="py-2">Amount</th>
                    <th class="py-2">Date</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-b">
                    <td class="py-2">Product A</td>
                    <td>3</td>
                    <td>$90</td>
                    <td>Today</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2">Product B</td>
                    <td>1</td>
                    <td>$30</td>
                    <td>Today</td>
                </tr>
                <tr>
                    <td class="py-2">Product C</td>
                    <td>5</td>
                    <td>$150</td>
                    <td>Yesterday</td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
