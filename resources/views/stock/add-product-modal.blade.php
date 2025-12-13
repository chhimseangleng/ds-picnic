<!-- Add Product Modal -->
<div id="addProductModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg">

        <h2 class="text-2xl font-semibold mb-4">Add Product</h2>

        <form action="{{ route('stock.store.product') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Product Name -->
                <div>
                    <label class="block text-gray-700 mb-1">Product Name</label>
                    <input type="text" name="name" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:ring focus:ring-blue-300">
                </div>

                <!-- Category ID Dropdown -->
                <div>
                    <label class="block text-gray-700 mb-1">Category</label>
                    <select name="categoryID" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:ring focus:ring-blue-300">
                        <option value="">Select Category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->_id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Unit Price -->
                <div>
                    <label class="block text-gray-700 mb-1">Unit Price</label>
                    <input type="number" step="0.01" name="unitPrice" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:ring focus:ring-blue-300">
                </div>

                <!-- Sell Price -->
                <div>
                    <label class="block text-gray-700 mb-1">Selling Price</label>
                    <input type="number" step="0.01" name="sellPrice" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:ring focus:ring-blue-300">
                </div>

                <!-- Qty -->
                <div>
                    <label class="block text-gray-700 mb-1">Quantity</label>
                    <input type="number" name="qty" required
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:ring focus:ring-blue-300">
                </div>

                <!-- Min Stock -->
                <div>
                    <label class="block text-gray-700 mb-1">Minimum Stock</label>
                    <input type="number" name="minStock"
                        class="w-full border border-gray-300 px-3 py-2 rounded focus:ring focus:ring-blue-300">
                </div>

                <!-- Image Upload -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Product Image</label>

                    <!-- Upload Box -->
                    <div id="imageUploadBox"
                        class="w-full h-48 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-100 relative overflow-hidden"
                        onclick="document.getElementById('imageInput').click()">

                        <!-- Icon and Text -->
                        <div id="uploadPlaceholder" class="flex flex-col items-center text-gray-400">
                            <!-- Picture Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7v13a2 2 0 002 2h14a2 2 0 002-2V7M16 3h-8a2 2 0 00-2 2v2h12V5a2 2 0 00-2-2zM4 10l4 4 4-4 4 4 4-4" />
                            </svg>
                            <p class="text-md font-medium">Click here to upload</p>
                            <p class="text-sm text-gray-400">Max file size 5MB</p>
                        </div>

                        <!-- Image Preview -->
                        <img id="imagePreview" class="hidden w-full h-full object-contain absolute top-0 left-0"
                            alt="Preview">
                    </div>

                    <!-- Hidden File Input -->
                    <input type="file" id="imageInput" name="image" accept="image/*" class="hidden"
                        onchange="previewImage(event)">
                </div>

                <!-- JavaScript for Preview -->
                <script>
                    function previewImage(event) {
                        const file = event.target.files[0];
                        const preview = document.getElementById('imagePreview');
                        const placeholder = document.getElementById('uploadPlaceholder');

                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                preview.classList.remove('hidden');
                                placeholder.classList.add('hidden');
                            }
                            reader.readAsDataURL(file);
                        } else {
                            preview.src = '';
                            preview.classList.add('hidden');
                            placeholder.classList.remove('hidden');
                        }
                    }
                </script>


            </div>

            <!-- Actions -->
            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" onclick="closeAddProductModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Cancel
                </button>

                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Save Product
                </button>
            </div>

        </form>
    </div>
</div>
<script>
    function openAddProductModal() {
        document.getElementById('addProductModal').classList.remove('hidden');
    }

    function closeAddProductModal() {
        document.getElementById('addProductModal').classList.add('hidden');
    }
</script>
