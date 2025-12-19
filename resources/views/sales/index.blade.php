<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sale') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="bg-white shadow rounded-lg p-6">
            <!-- Header with New Sale Button -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Sale</h3>
                <div class="flex gap-3">
                    <button onclick="window.location.href='{{ route('sale.create') }}'" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold flex items-center shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                            New Sale
                    </button>
                    <button class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Export Report
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="mb-6 flex gap-4">
                <form method="GET" action="{{ route('sale') }}" class="flex gap-3 flex-1">
                    <!-- Sale Type Filter -->
                    <div class="flex-1">
                        <select name="saleType" onchange="this.form.submit()" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">All Sales</option>
                            <option value="indoor" {{ request('saleType') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                            <option value="online" {{ request('saleType') == 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="flex-1">
                        <input type="date" name="date" value="{{ request('date') }}" 
                            onchange="this.form.submit()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Search -->
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by customer or Order Id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        Search
                    </button>
                </form>
            </div>

            <!-- Recent Sale Table -->
            <div class="mt-6">
                <h4 class="text-lg font-semibold mb-4">Recent Sale</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Order Id</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Customer</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Items</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Total</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Date</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr class="hover:bg-gray-50 {{ $loop->index == 0 ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                    <td class="px-6 py-4 text-sm text-gray-800 border-b">{{ $sale->orderID }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 border-b">{{ $sale->customer->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 border-b">
                                        <span class="capitalize">{{ $sale->saleType }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800 border-b">{{ count($sale->items) }} Items</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-800 border-b">${{ number_format($sale->total, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 border-b">{{ $sale->date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm border-b">
                                        <a href="{{ route('sale.invoice', $sale->_id) }}" 
                                            class="text-blue-600 hover:text-blue-800 font-medium">Print View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                            <p class="text-lg">No sales found</p>
                                            <p class="text-sm text-gray-400 mt-1">Click "+ New Sale" to create your first sale</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
