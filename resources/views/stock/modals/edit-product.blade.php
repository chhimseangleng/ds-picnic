<!-- Edit Product Modal -->
<div id="editProductModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden animate-fadeIn">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b bg-gradient-to-r from-blue-500 to-blue-600">
            <h3 class="text-2xl font-bold text-white">Edit Product</h3>
            <button onclick="closeEditProductModal()" class="text-white hover:text-gray-200 text-3xl font-bold">&times;</button>
        </div>

        <!-- Form -->
        <form id="editProductForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                <!-- Product Image Preview -->
                <div class="flex justify-center mb-4">
                    <div class="relative">
                        <img id="edit_product_image_preview" src="" alt="Product Image" class="w-32 h-32 object-cover rounded-lg shadow-md bg-gray-100">
                        <label for="edit_product_image" class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-2 cursor-pointer hover:bg-blue-600 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                        <input type="file" id="edit_product_image" name="image" accept="image/*" class="hidden" onchange="previewEditImage(event)">
                    </div>
                </div>

                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" id="edit_product_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter product name">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="categoryID" id="edit_product_category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Prices Row -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                        <input type="number" step="0.01" name="unitPrice" id="edit_product_unit_price" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sell Price *</label>
                        <input type="number" step="0.01" name="sellPrice" id="edit_product_sell_price" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
                    </div>
                </div>

                <!-- Quantities Row -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                        <input type="number" name="qty" id="edit_product_qty" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Stock</label>
                        <input type="number" name="minStock" id="edit_product_min_stock" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 p-6 border-t bg-gray-50">
                <button type="button" onclick="closeEditProductModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">Update Product</button>
            </div>
        </form>
    </div>
</div>

<script>
let editProductId = null;

function openEditProductModal(product) {
    // MongoDB uses _id as primary key
    editProductId = product._id;
    
    // Set form action
    document.getElementById('editProductForm').action = `/stock/product/${product._id}`;
    
    // Populate form fields
    document.getElementById('edit_product_name').value = product.name || '';
    document.getElementById('edit_product_category').value = product.categoryID || '';
    document.getElementById('edit_product_unit_price').value = parseFloat(product.unitPrice).toFixed(2);
    document.getElementById('edit_product_sell_price').value = parseFloat(product.sellPrice).toFixed(2);
    document.getElementById('edit_product_qty').value = product.qty || 0;
    document.getElementById('edit_product_min_stock').value = product.minStock || 0;
    
    // Set image preview
    const imgPreview = document.getElementById('edit_product_image_preview');
    if (product.image_url) {
        imgPreview.src = product.image_url;
    } else {
        imgPreview.src = '';
        imgPreview.alt = 'No Image';
    }
    
    // Show modal
    document.getElementById('editProductModal').classList.remove('hidden');
}

function closeEditProductModal() {
    document.getElementById('editProductModal').classList.add('hidden');
    document.getElementById('editProductForm').reset();
    editProductId = null;
}

function previewEditImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('edit_product_image_preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
