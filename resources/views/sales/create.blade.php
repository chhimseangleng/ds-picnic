<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Sale') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Create New Sale</h3>

            <form id="saleForm" method="POST" action="{{ route('sale.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Sale Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Sale Information -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Sale Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale Type *</label>
                                    <select name="saleType" id="saleType" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Sales</option>
                                        <option value="indoor">Indoor</option>
                                        <option value="online">Online</option>
                                    </select>
                                </div>

                                <!-- Employee -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee Name *</label>
                                    <select name="employeeID" id="employeeID" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->_id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Customer -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                                    <select name="customerID" id="customerID" required onchange="loadCustomerContact()"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->_id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Customer Contact (Auto-filled) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Contact</label>
                                    <input type="text" id="customerContact" readonly 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" 
                                        placeholder="Will auto-fill when customer selected">
                                </div>
                            </div>
                        </div>

                        <!-- Items Section -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Items</h4>
                            
                            <!-- Product/Bundle Tabs -->
                            <div class="flex gap-2 mb-4 border-b">
                                <button type="button" id="tabSingleProduct" onclick="switchTab('single')"
                                    class="px-6 py-2 font-medium border-b-2 border-blue-600 text-blue-600">
                                    Single Product
                                </button>
                                <button type="button" id="tabBundle" onclick="switchTab('bundle')"
                                    class="px-6 py-2 font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                                    Bundle Product
                                </button>
                            </div>

                            <!-- Single Product Section -->
                            <div id="singleProductSection">
                                <!-- Category Tabs -->
                                <div class="flex gap-2 mb-4 flex-wrap">
                                    <button type="button" onclick="filterByCategory('')" 
                                        class="category-tab px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">
                                        All
                                    </button>
                                    @foreach($categories as $category)
                                        <button type="button" onclick="filterByCategory('{{ $category->_id }}')" 
                                            class="category-tab px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300">
                                            {{ $category->name }}
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Product Grid -->
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2">
                                    @foreach($products as $product)
                                        <div class="product-card border rounded-lg p-3 hover:shadow-lg cursor-pointer transition" 
                                            data-category="{{ $product->category_id ?? '' }}"
                                            data-id="{{ $product->_id }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->sellPrice }}"
                                            data-stock="{{ $product->qty }}"
                                            onclick="addProductToCart('{{ $product->_id }}', '{{ $product->name }}', {{ $product->sellPrice }}, {{ $product->qty }})">
                                            <div class="aspect-square bg-gray-100 rounded mb-2 flex items-center justify-center overflow-hidden">
                                                @if($product->image_url)
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <h5 class="font-medium text-sm mb-1 truncate">{{ $product->name }}</h5>
                                            <p class="text-blue-600 font-semibold">${{ number_format($product->sellPrice, 2) }}</p>
                                            <p class="text-xs text-gray-500">Stock: {{ $product->qty }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Bundle Product Section (Hidden by default) -->
                            <div id="bundleProductSection" class="hidden">
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2">
                                    @foreach($bundles as $bundle)
                                        <div class="border rounded-lg p-3 hover:shadow-lg cursor-pointer transition"
                                            onclick="addBundleToCart('{{ $bundle->_id }}', '{{ $bundle->name }}', {{ $bundle->bundlePrice }}, {{ $bundle->available_stock }})">
                                            <div class="aspect-square bg-gray-100 rounded mb-2 flex items-center justify-center overflow-hidden">
                                                @if($bundle->image_url)
                                                    <img src="{{ $bundle->image_url }}" alt="{{ $bundle->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <h5 class="font-medium text-sm mb-1 truncate">{{ $bundle->name }}</h5>
                                            <p class="text-purple-600 font-semibold">${{ number_format($bundle->bundlePrice, 2) }}</p>
                                            <p class="text-xs text-gray-500">Available: {{ $bundle->available_stock }}</p>
                                            <p class="text-xs text-gray-400">{{ count($bundle->products) }} items</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Cart & Summary -->
                    <div class="space-y-6">
                        <!-- Cart Items -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-3">Selected Items (<span id="cartCount">0</span>)</h4>
                            <div id="cartItems" class="space-y-2 max-h-64 overflow-y-auto">
                                <p class="text-sm text-gray-500 text-center py-8">No items added yet</p>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold" id="subtotalDisplay">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <label class="text-gray-600">Discount:</label>
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-bold">$</span>
                                    <input type="number" name="discount" id="discountInput" min="0" step="0.01" value="0"
                                        oninput="calculateTotal()"
                                        class="w-24 px-2 py-1 border border-gray-300 rounded text-right">
                                </div>
                            </div>
                            <div class="border-t pt-3 flex justify-between">
                                <span class="font-bold text-lg">Total:</span>
                                <span class="font-bold text-xl text-blue-600" id="totalDisplay">$0.00</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button type="button" onclick="window.location.href='{{ route('sale') }}'" 
                                class="w-full px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                Complete Sale
                            </button>
                        </div>

                        <!-- Hidden fields for form submission -->
                        <input type="hidden" name="items" id="itemsInput">
                        <input type="hidden" name="subtotal" id="subtotalInput">
                        <input type="hidden" name="total" id="totalInput">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Out of Stock Modal -->
    <div id="outOfStockModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-4 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Out of Stock!!</h3>
            <p class="text-gray-600 mb-4" id="outOfStockMessage"></p>
            <button onclick="closeOutOfStockModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Okay
            </button>
        </div>
    </div>

    <script>
    let cart = [];
    let currentTab = 'single';

    // Load customer contact when customer selected
    async function loadCustomerContact() {
        const customerId = document.getElementById('customerID').value;
        if (!customerId) {
            document.getElementById('customerContact').value = '';
            return;
        }

        try {
            const response = await fetch(`/api/customer/${customerId}/contact`);
            const data = await response.json();
            document.getElementById('customerContact').value = data.phone || '';
        } catch (error) {
            console.error('Error loading customer contact:', error);
        }
    }

    // Switch between product/bundle tabs
    function switchTab(tab) {
        currentTab = tab;
        const singleTab = document.getElementById('tabSingleProduct');
        const bundleTab = document.getElementById('tabBundle');
        const singleSection = document.getElementById('singleProductSection');
        const bundleSection = document.getElementById('bundleProductSection');

        if (tab === 'single') {
            singleTab.className = 'px-6 py-2 font-medium border-b-2 border-blue-600 text-blue-600';
            bundleTab.className = 'px-6 py-2 font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700';
            singleSection.classList.remove('hidden');
            bundleSection.classList.add('hidden');
        } else {
            bundleTab.className = 'px-6 py-2 font-medium border-b-2 border-blue-600 text-blue-600';
            singleTab.className = 'px-6 py-2 font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700';
            bundleSection.classList.remove('hidden');
            singleSection.classList.add('hidden');
        }
    }

    // Filter products by category
    function filterByCategory(categoryId) {
        const products = document.querySelectorAll('.product-card');
        const tabs = document.querySelectorAll('.category-tab');
        
        // Update tab styles
        tabs.forEach(tab => {
            tab.className = 'category-tab px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300';
        });
        event.target.className = 'category-tab px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium';

        // Filter products
        products.forEach(product => {
            const productCategory = product.getAttribute('data-category');
            if (categoryId === '' || productCategory === categoryId) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    }

    // Add product to cart
    function addProductToCart(id, name, price, stock) {
        // Check if already in cart
        const existing = cart.find(item => item.id === id && item.type === 'product');
        
        if (existing) {
            if (existing.quantity >= stock) {
                showOutOfStockModal(`Cannot add more "${name}". Only ${stock} in stock.`);
                return;
            }
            existing.quantity++;
        } else {
            if (stock <= 0) {
                showOutOfStockModal(`"${name}" is out of stock!`);
                return;
            }
            cart.push({
                id: id,
                type: 'product',
                name: name,
                price: price,
                quantity: 1,
                maxStock: stock
            });
        }
        
        updateCart();
    }

    // Add bundle to cart
    function addBundleToCart(id, name, price, availableStock) {
        const existing = cart.find(item => item.id === id && item.type === 'bundle');
        
        if (existing) {
            if (existing.quantity >= availableStock) {
                showOutOfStockModal(`Cannot add more "${name}". Only ${availableStock} bundles available.`);
                return;
            }
            existing.quantity++;
        } else {
            if (availableStock <= 0) {
                showOutOfStockModal(`"${name}" bundle is not available!`);
                return;
            }
            cart.push({
                id: id,
                type: 'bundle',
                name: name,
                price: price,
                quantity: 1,
                maxStock: availableStock
            });
        }
        
        updateCart();
    }

    // Update cart display
    function updateCart() {
        const cartContainer = document.getElementById('cartItems');
        const cartCount = document.getElementById('cartCount');
        
        if (cart.length === 0) {
            cartContainer.innerHTML = '<p class="text-sm text-gray-500 text-center py-8">No items added yet</p>';
            cartCount.textContent = '0';
        } else {
            let html = '';
            cart.forEach((item, index) => {
                html += `
                    <div class="flex items-center justify-between p-2 bg-white rounded border">
                        <div class="flex-1">
                            <p class="font-medium text-sm">${item.name}</p>
                            <p class="text-xs text-gray-500">${item.type === 'product' ? 'Product' : 'Bundle'} - $${item.price.toFixed(2)}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="decreaseQuantity(${index})" 
                                class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300 flex items-center justify-center">-</button>
                            <span class="w-8 text-center font-medium">${item.quantity}</span>
                            <button type="button" onclick="increaseQuantity(${index})" 
                                class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300 flex items-center justify-center">+</button>
                            <button type="button" onclick="removeFromCart(${index})" 
                                class="ml-2 text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });
            cartContainer.innerHTML = html;
            cartCount.textContent = cart.length;
        }
        
        calculateTotal();
    }

    // Increase quantity
    function increaseQuantity(index) {
        const item = cart[index];
        if (item.quantity >= item.maxStock) {
            showOutOfStockModal(`Cannot add more "${item.name}". Maximum ${item.maxStock} available.`);
            return;
        }
        item.quantity++;
        updateCart();
    }

    // Decrease quantity
    function decreaseQuantity(index) {
        const item = cart[index];
        if (item.quantity > 1) {
            item.quantity--;
            updateCart();
        }
    }

    // Remove from cart
    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCart();
    }

    // Calculate total
    function calculateTotal() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const total = Math.max(0, subtotal - discount);

        document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('totalDisplay').textContent = '$' + total.toFixed(2);
        
        // Update hidden form fields
        document.getElementById('subtotalInput').value = subtotal.toFixed(2);
        document.getElementById('totalInput').value = total.toFixed(2);
    }

    // Show out of stock modal
    function showOutOfStockModal(message) {
        document.getElementById('outOfStockMessage').textContent = message;
        document.getElementById('outOfStockModal').classList.remove('hidden');
    }

    function closeOutOfStockModal() {
        document.getElementById('outOfStockModal').classList.add('hidden');
    }

    // Form submission
    document.getElementById('saleForm').addEventListener('submit', function(e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Please add at least one item to the cart');
            return false;
        }

        // Prepare items data for submission
        const itemsData = cart.map(item => ({
            type: item.type,
            itemID: item.id,
            quantity: item.quantity,
            price: item.price
        }));

        document.getElementById('itemsInput').value = JSON.stringify(itemsData);
    });
    </script>
</x-app-layout>
