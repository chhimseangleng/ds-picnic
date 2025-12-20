<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">

        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Monthly Revenue Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Monthly Revenue</p>
                        <h3 class="text-3xl font-bold mt-2">${{ number_format($currentRevenue, 2) }}</h3>
                        <p class="text-sm mt-2 {{ $revenueGrowth >= 0 ? 'text-green-200' : 'text-red-200' }}">
                            {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ number_format(abs($revenueGrowth), 1) }}% from last month
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Net Profit Card -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Net Profit</p>
                        <h3 class="text-3xl font-bold mt-2">${{ number_format($currentProfit, 2) }}</h3>
                        <p class="text-sm mt-2 {{ $profitGrowth >= 0 ? 'text-green-200' : 'text-red-200' }}">
                            {{ $profitGrowth >= 0 ? '↑' : '↓' }} {{ number_format(abs($profitGrowth), 1) }}% from last month
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Sales Card -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Sales</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $currentSalesCount }}</h3>
                        <p class="text-sm mt-2 {{ $salesGrowth >= 0 ? 'text-green-200' : 'text-red-200' }}">
                            {{ $salesGrowth >= 0 ? '↑' : '↓' }} {{ number_format(abs($salesGrowth), 1) }}% from last month
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert Card -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Low Stock Alerts</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $lowStockCount }}</h3>
                        <p class="text-sm mt-2 text-red-200">
                            Products below 10 units
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- Middle Row: Sales Breakdown & Quick Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Sales by Type -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Breakdown</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Indoor Sales -->
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-5 border-l-4 border-indigo-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-indigo-600 text-sm font-medium">Indoor Sales</p>
                                <p class="text-2xl font-bold text-indigo-900 mt-1">${{ number_format($indoorSales, 2) }}</p>
                            </div>
                            <div class="bg-indigo-500 bg-opacity-20 p-3 rounded-full">
                                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Online Sales -->
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg p-5 border-l-4 border-teal-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-teal-600 text-sm font-medium">Online Sales</p>
                                <p class="text-2xl font-bold text-teal-900 mt-1">${{ number_format($onlineSales, 2) }}</p>
                            </div>
                            <div class="bg-teal-500 bg-opacity-20 p-3 rounded-full">
                                <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-700 font-medium">Products</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $totalProducts }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-700 font-medium">Employees</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $totalEmployees }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="ml-3 text-gray-700 font-medium">Customers</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $totalCustomers }}</span>
                    </div>

                </div>
            </div>

        </div>

        <!-- Bottom Row: Top Products & Recent Sales -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Top Selling Products -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 Products This Month</h3>
                <div class="space-y-3">
                    @forelse($topProducts as $index => $product)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product['name'] }}</p>
                                    <p class="text-sm text-gray-500">Sold: {{ $product['quantity'] }} units</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">${{ number_format($product['revenue'], 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p>No products sold this month</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Sales</h3>
                <div class="space-y-3">
                    @forelse($recentSales as $sale)
                        <a href="{{ route('sale.invoice', $sale->_id) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-indigo-50 transition-colors border border-transparent hover:border-indigo-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $sale->orderID }}</p>
                                    <p class="text-sm text-gray-600">{{ $sale->customer->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $sale->date ? $sale->date->format('M d, Y h:i A') : 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">${{ number_format($sale->total, 2) }}</p>
                                    <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full {{ $sale->saleType === 'indoor' ? 'bg-indigo-100 text-indigo-800' : 'bg-teal-100 text-teal-800' }}">
                                        {{ ucfirst($sale->saleType) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>No recent sales</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
