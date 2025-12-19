<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Bundle') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="bg-gray-100 shadow rounded-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Edit: {{ $bundle->name }}</h3>
                <a href="{{ route('bundles') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Back to Bundles
                </a>
            </div>

            <form method="POST" action="{{ route('bundles.update', $bundle->_id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Product Selection -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h4 class="text-lg font-bold mb-4">Select Products</h4>
                        
                        <!-- Search/Filter -->
                        <input type="text" id="productSearch" placeholder="Search products..." 
                            class="w-full px-4 py-2 border rounded-lg mb-4" onkeyup="filterProducts()">
                        
                        <!-- Product List -->
                        <div id="productList" class="space-y-2 max-h-96 overflow-y-auto">
                            @foreach($products as $product)
                                @php
                                    $isSelected = collect($bundle->products)->contains('productID', $product->_id);
                                    $selectedQty = $isSelected ? collect($bundle->products)->firstWhere('productID', $product->_id)['quantity'] : 1;
                                @endphp
                                <div class="product-item flex items-center p-3 border rounded hover:bg-gray-50" 
                                    data-name="{{ strtolower($product->name) }}">
                                    <input type="checkbox" 
                                        id="product_{{ $product->_id }}" 
                                        value="{{ $product->_id }}"
                                        {{ $isSelected ? 'checked' : '' }}
                                        class="product-checkbox mr-3"
                                        onchange="toggleProduct('{{ $product->_id }}', '{{ $product->name }}', {{ $product->qty }}, '{{ $product->image_url }}', {{ $selectedQty }})">
                                    
                                    <label for="product_{{ $product->_id }}" class="flex items-center flex-1 cursor-pointer">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" class="w-12 h-12 object-cover rounded mr-3">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center text-xs">
                                                No Image
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1">
                                            <p class="font-medium">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-500">Stock: {{ $product->qty }} | ${{ number_format($product->sellPrice, 2) }}</p>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Column: Bundle Details -->
                    <div class="space-y-6">
                        <!-- Bundle Information -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-lg font-bold mb-4">Bundle Information</h4>
                            
                            <!-- Current Image Preview -->
                            @if($bundle->image_url)
                                <div class="mb-4 flex justify-center">
                                    <img src="{{ $bundle->image_url }}" alt="{{ $bundle->name }}" class="w-32 h-32 object-cover rounded-lg shadow">
                                </div>
                            @endif

                            <!-- Bundle Name -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Bundle Name *</label>
                                <input type="text" name="name" value="{{ $bundle->name }}" required 
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500"
                                    placeholder="e.g., Starter Pack">
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Description (Optional)</label>
                                <textarea name="description" rows="3" 
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500"
                                    placeholder="Describe this bundle...">{{ $bundle->description }}</textarea>
                            </div>

                            <!-- Bundle Price -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Bundle Price *</label>
                                <input type="number" step="0.01" name="bundlePrice" value="{{ $bundle->bundlePrice }}" required 
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500"
                                    placeholder="0.00">
                            </div>

                            <!-- Bundle Image -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Update Image (Optional)</label>
                                <input type="file" name="image" accept="image/*" 
                                    class="w-full px-4 py-2 border rounded-lg">
                                <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                            </div>
                        </div>

                        <!-- Selected Products -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h4 class="text-lg font-bold mb-4">Selected Products (<span id="selectedCount">0</span>)</h4>
                            <div id="selectedProducts" class="space-y-3">
                                <p class="text-gray-500 text-sm">Loading...</p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-lg">
                            Update Bundle
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    let selectedProducts = {};

    // Initialize with existing bundle products
    @foreach($bundle->products as $item)
        @php
            $product = \App\Models\Product::find($item['productID']);
        @endphp
        @if($product)
            selectedProducts['{{ $item['productID'] }}'] = {
                name: '{{ $product->name }}',
                stock: {{ $product->qty }},
                quantity: {{ $item['quantity'] }},
                imageUrl: '{{ $product->image ? Storage::disk('s3')->url($product->image) : '' }}'
            };
        @endif
    @endforeach

    // Update display on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedProducts();
    });

    function toggleProduct(productId, productName, stock, imageUrl, initialQty = 1) {
        const checkbox = document.getElementById('product_' + productId);
        
        if (checkbox.checked) {
            selectedProducts[productId] = {
                name: productName,
                stock: stock,
                quantity: selectedProducts[productId]?.quantity || initialQty,
                imageUrl: imageUrl
            };
        } else {
            delete selectedProducts[productId];
        }
        
        updateSelectedProducts();
    }

    function updateSelectedProducts() {
        const container = document.getElementById('selectedProducts');
        const count = Object.keys(selectedProducts).length;
        document.getElementById('selectedCount').textContent = count;
        
        if (count === 0) {
            container.innerHTML = '<p class="text-gray-500 text-sm">No products selected yet</p>';
            return;
        }
        
        let html = '';
        for (const [productId, product] of Object.entries(selectedProducts)) {
            html += `
                <div class="flex items-center justify-between p-3 border rounded bg-gray-50">
                    <div class="flex items-center flex-1">
                        ${product.imageUrl ? 
                            `<img src="${product.imageUrl}" class="w-10 h-10 object-cover rounded mr-3">` :
                            '<div class="w-10 h-10 bg-gray-200 rounded mr-3"></div>'
                        }
                        <span class="font-medium">${product.name}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm">Qty:</label>
                        <input type="number" 
                            min="1" 
                            max="${product.stock}"
                            value="${product.quantity}"
                            name="products[${productId}][quantity]"
                            onchange="updateProductQuantity('${productId}', this.value)"
                            class="w-20 px-2 py-1 border rounded text-center">
                        <input type="hidden" name="products[${productId}][productID]" value="${productId}">
                        <button type="button" onclick="removeProduct('${productId}')" 
                            class="text-red-500 hover:text-red-700 ml-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        }
        
        container.innerHTML = html;
    }

    function updateProductQuantity(productId, quantity) {
        if (selectedProducts[productId]) {
            selectedProducts[productId].quantity = parseInt(quantity);
        }
    }

    function removeProduct(productId) {
        delete selectedProducts[productId];
        document.getElementById('product_' + productId).checked = false;
        updateSelectedProducts();
    }

    function filterProducts() {
        const searchTerm = document.getElementById('productSearch').value.toLowerCase();
        const items = document.querySelectorAll('.product-item');
        
        items.forEach(item => {
            const name = item.getAttribute('data-name');
            if (name.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }
    </script>
</x-app-layout>
