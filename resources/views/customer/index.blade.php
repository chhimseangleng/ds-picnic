<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="bg-white shadow rounded-lg p-6">
            <!-- Header with Add Customer Button -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Customer List</h3>
                <button onclick="openAddCustomerModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold flex items-center shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Customer
                </button>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search Bar -->
            <div class="mb-4">
                <form method="GET" action="{{ route('customers') }}" class="flex gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search Customer" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('customers') }}" class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Customer Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Contact Info</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Purchase History</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $index => $customer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-800 border-b">{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 border-b">{{ $customer->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 border-b">{{ $customer->phone }}</td>
                                <td class="px-6 py-4 text-sm border-b">
                                    <button class="text-blue-600 hover:text-blue-800 font-medium">View</button>
                                </td>
                                <td class="px-6 py-4 text-sm border-b">
                                    <button onclick="openEditCustomerModal('{{ $customer->_id }}', '{{ $customer->name }}', '{{ $customer->phone }}')" 
                                        class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <p class="text-lg">No customers found</p>
                                        <p class="text-sm text-gray-400 mt-1">Click "+ Add Customer" to create your first customer</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('customer.modals.add-edit-customer')
</x-app-layout>
