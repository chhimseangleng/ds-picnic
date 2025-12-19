<!-- Delete Product Confirmation Modal -->
<div id="deleteProductModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden animate-fadeIn">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b bg-gradient-to-r from-red-500 to-red-600">
            <h3 class="text-2xl font-bold text-white">Delete Product</h3>
            <button onclick="closeDeleteProductModal()" class="text-white hover:text-gray-200 text-3xl font-bold">&times;</button>
        </div>

        <!-- Body -->
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="rounded-full bg-red-100 p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <h4 class="text-lg font-semibold text-gray-800 text-center mb-2">Are you sure?</h4>
            <p class="text-gray-600 text-center mb-2">You are about to delete:</p>
            <p class="text-center font-bold text-gray-800 mb-4" id="delete_product_name"></p>
            <p class="text-sm text-gray-500 text-center">This action cannot be undone.</p>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 p-6 border-t bg-gray-50">
            <button type="button" onclick="closeDeleteProductModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition">Cancel</button>
            <form id="deleteProductForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition">Delete Product</button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDeleteProduct(product) {
    // Set product name
    document.getElementById('delete_product_name').textContent = product.name;
    
    // Set form action - MongoDB uses _id as primary key
    document.getElementById('deleteProductForm').action = `/stock/product/${product._id}`;
    
    // Show modal
    document.getElementById('deleteProductModal').classList.remove('hidden');
}

function closeDeleteProductModal() {
    document.getElementById('deleteProductModal').classList.add('hidden');
}
</script>
