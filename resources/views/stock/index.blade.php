<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stock Management') }}
        </h2>
    </x-slot>

    <div class="py-6">

        <!-- Box Container -->
        <div class="bg-gray-100 shadow rounded-lg p-6">

            <!-- Top Bar: Title Left, Buttons Right -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Inventory List</h3>

                <div class="flex gap-4">
                    <!-- Product Category -->
                    <a href="{{ route('stock.categories') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
                        <!-- Icon: Folder Plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 7v13a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-7l-2-2H5a2 2 0 00-2 2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v6m3-3H9" />
                        </svg>
                        Product Category
                    </a>

                    <!-- Add Product -->
                    <button
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow flex items-center"
                        onclick="openAddProductModal()">
                        <!-- Icon: Plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Product
                    </button>
                    @include('stock.add-product-modal')

                    <!-- Bundle Management Section - More Prominent -->
                    <div class="flex gap-3 ml-4 pl-4 border-l-2 border-purple-300">
                        <!-- View Bundles Button -->
                        <a href="{{ route('bundles') }}" 
                            class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-6 py-3 rounded-lg shadow-lg flex items-center font-bold text-base transform hover:scale-105 transition-all duration-200">
                            <!-- Icon: Eye -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Bundles
                        </a>

                        <!-- Create Bundle Button -->
                        <a href="{{ route('bundles.create') }}" 
                            class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-6 py-3 rounded-lg shadow-lg flex items-center font-bold text-base transform hover:scale-105 transition-all duration-200">
                            <!-- Icon: Plus Collection -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Create Bundle
                        </a>
                    </div>

                </div>
            </div>

            <div class="my-6 w-full bg-gray-50 rounded-xl p-4">
                <div class="flex overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300">
                    <div class="flex gap-4 items-center">
                        <!-- All Product - Active when NO category is selected -->
                        <a href="{{ route('stock') }}"
                            class="px-6 py-3 rounded-full shadow-sm font-medium whitespace-nowrap flex-shrink-0 transition
                      {{ !request('category') ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100 hover:text-gray-800' }}">
                            All Product
                        </a>

                        <!-- Category Pills -->
                        @foreach ($categories as $category)
                            <a href="{{ route('stock', ['category' => $category->id]) }}"
                                class="px-6 py-3 rounded-full shadow-sm font-medium whitespace-nowrap flex-shrink-0 transition
                                  {{ request('category') == $category->id ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100 hover:text-gray-800' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Inventory Card Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($products ?? [] as $product)
                    <div
                        class="bg-white rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-300 overflow-hidden flex flex-col p-4 text-center">
                        <!-- Image -->
                        <div class="flex justify-center">
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                    class="w-32 h-32 object-contain rounded-lg shadow-md bg-gray-100"
                                    onerror="this.onerror=null; this.src=''; this.parentElement.innerHTML='<div class=\'w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center\'>Image Error</div>';">
                            @else
                                <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                    No Image
                                </div>
                            @endif
                        </div>

                        <!-- Name -->
                        <h3 class="mt-2 text-md font-semibold text-gray-800">{{ $product->name }}</h3>

                        <!-- Prices -->
                        <div class="mt-2 grid grid-cols-2 gap-2 px-4">
                            <p class="text-gray-600 text-md text-left">Unit:
                                ${{ number_format((float) $product->unitPrice, 2) }}</p>
                            <p class="text-red-600 font-bold text-md text-right">Sell:
                                ${{ number_format((float) $product->sellPrice, 2) }}</p>
                        </div>

                        <!-- Qty & Status in a row -->
                        <div class="mt-2 flex justify-between items-center px-4">
                            <p class="text-gray-600 text-md">Qty: {{ $product->qty }}</p>
                            @if ((int) $product->qty === 0)
                                <p class="text-gray-500 font-semibold text-md">No Stock</p>
                            @elseif((int) $product->qty <= (int) $product->minStock)
                                <p class="text-red-600 font-semibold text-md">Low Stock</p>
                            @else
                                <p class="text-green-600 font-semibold text-md">In Stock</p>
                            @endif
                        </div>

                        <!-- Buttons -->
                        <div class="mt-3 flex flex-col gap-2 grid grid-cols-7">
                            <!-- Top Row: Edit + Delete -->
                            <div class="col-span-5">
                                <button
                                    class="flex-1 w-full py-2 bg-blue-200 text-blue-500 font-bold rounded-lg hover:bg-blue-300 flex items-center justify-center"
                                    onclick='openEditProductModal(@json($product))'>
                                    <!-- Icon: Pencil -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 11l6-6m0 0L6 20H3v-3L15 5z" />
                                    </svg>
                                    Edit
                                </button>
                            </div>
                            <div class="col-span-2">
                                <button
                                    class="flex-1 w-full py-2 bg-red-300 text-red-600 rounded-lg font-bold hover:bg-red-400 flex items-center justify-center"
                                    onclick='confirmDeleteProduct(@json($product))'>
                                    <!-- Icon: Trash -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-6 0V5a2 2 0 012-2h2a2 2 0 012 2v2" />
                                    </svg>

                                </button>
                            </div>
                            <!-- Bottom Row: Add Stock -->
                            <div class="col-span-7">
                                <!-- Add Stock Button -->
                                <button
                                    class="flex-1 w-full py-2 bg-blue-500 font-bold text-white rounded hover:bg-blue-600 flex items-center justify-center"
                                    onclick='openAddStockModal(@json($product))'>
                                    <!-- Icon: Plus Circle -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Stock
                                </button>
                            </div>

                        </div>
                    </div>
                @endforeach

                @if (count($products ?? []) === 0)
                    <p class="col-span-4 text-center text-gray-500">No products found.</p>
                @endif
            </div>

        </div>

    </div>

    @include('stock.modals.view-product')
    @include('stock.modals.add-stock')
    @include('stock.modals.edit-product')
    @include('stock.modals.delete-product')
</x-app-layout>