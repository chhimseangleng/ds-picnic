<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employees') }}
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Employee List</h3>

            <table class="w-full text-left">
                <thead class="border-b">
                <tr>
                    <th class="py-2">Name</th>
                    <th class="py-2">Role</th>
                    <th class="py-2">Status</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-b">
                    <td class="py-2">John Doe</td>
                    <td>Manager</td>
                    <td><span class="text-green-600 font-semibold">Active</span></td>
                </tr>
                <tr>
                    <td class="py-2">Jane Smith</td>
                    <td>Cashier</td>
                    <td><span class="text-yellow-600 font-semibold">Leave</span></td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
