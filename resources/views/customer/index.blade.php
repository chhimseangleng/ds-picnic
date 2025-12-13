<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customers') }}
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Customer List</h3>

            <table class="w-full text-left">
                <thead class="border-b">
                <tr>
                    <th class="py-2">Name</th>
                    <th class="py-2">Phone</th>
                    <th class="py-2">Registered</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-b">
                    <td class="py-2">Customer One</td>
                    <td>+123 456 789</td>
                    <td>2024-01-16</td>
                </tr>
                <tr>
                    <td class="py-2">Customer Two</td>
                    <td>+987 654 321</td>
                    <td>2024-01-12</td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
