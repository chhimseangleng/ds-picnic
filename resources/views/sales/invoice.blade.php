<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <!-- Invoice Container (Printable) -->
            <div id="invoiceContainer" class="bg-white shadow-lg rounded-lg p-8">
                <!-- Header -->
                <div class="text-center mb-8 border-b pb-6">
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">Invoice</h1>
                    <p class="text-gray-600">The Picnic Store</p>
                </div>

                <!-- Order Details -->
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Order ID:</p>
                        <p class="font-semibold text-lg">{{ $sale->orderID }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Date:</p>
                        <p class="font-semibold text-lg">{{ $sale->date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Sale Type:</p>
                        <p class="font-semibold capitalize">{{ $sale->saleType }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Employee Name:</p>
                        <p class="font-semibold">{{ $sale->employee->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Customer Name:</p>
                        <p class="font-semibold">{{ $sale->customer->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Customer Contact:</p>
                        <p class="font-semibold">{{ $sale->customer->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Items Section -->
                <div class="mb-8">
                    <h3 class="font-semibold text-lg mb-4">{{ count($detailedItems) }} Items</h3>
                    <div class="space-y-3">
                        @foreach($detailedItems as $item)
                            <div class="flex justify-between items-start py-3 border-b">
                                <div class="flex-1">
                                    <p class="font-medium">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-500">
                                        Qty {{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}
                                    </p>
                                </div>
                                <p class="font-semibold">${{ number_format($item['subtotal'], 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Totals -->
                <div class="border-t pt-6 space-y-3">
                    @if($sale->discount > 0)
                        <div class="flex justify-between text-blue-600">
                            <span class="font-medium">Discount:</span>
                            <span class="font-semibold">${{ number_format($sale->discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-xl font-bold">
                        <span>Total:</span>
                        <span class="text-blue-600">${{ number_format($sale->total, 2) }}</span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-12 text-center text-gray-500 text-sm border-t pt-6">
                    <p>Thank you for your business!</p>
                    <p class="mt-2">The Picnic Store - Quality Furniture & More</p>
                </div>
            </div>

            <!-- Action Buttons (Not printed) -->
            <div class="flex gap-4 mt-6 print:hidden">
                <button onclick="window.print()" 
                    class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Invoice
                </button>
                <button onclick="window.location.href='{{ route('sale') }}'" 
                    class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                    Back to Sales
                </button>
            </div>
        </div>
    </div>

    <style>
    @media print {
        body * {
            visibility: hidden;
        }
        #invoiceContainer, #invoiceContainer * {
            visibility: visible;
        }
        #invoiceContainer {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
    </style>
</x-app-layout>
