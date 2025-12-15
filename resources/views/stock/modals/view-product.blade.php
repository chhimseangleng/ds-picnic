<!-- View Product Modal -->
<div id="viewProductModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden animate-fadeIn">
        <!-- Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-xl font-bold">Product Details</h3>
            <button onclick="closeViewProductModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <!-- Body -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex justify-center items-center">
                <img id="modalProductImage" src="" alt="Product Image" class="rounded shadow w-48 h-48 object-cover">
            </div>
            <div class="space-y-2">
                <p><strong>Name:</strong> <span id="modalProductName"></span></p>
                <p><strong>Category:</strong> <span id="modalProductCategory"></span></p>
                <p><strong>Unit Price:</strong> $<span id="modalProductUnitPrice"></span></p>
                <p><strong>Sell Price:</strong> $<span id="modalProductSellPrice"></span></p>
                <p><strong>Quantity:</strong> <span id="modalProductQty"></span></p>
                <p><strong>Minimum Stock:</strong> <span id="modalProductMinStock"></span></p>
                <p><strong>Status:</strong> <span id="modalProductStatus" class="font-semibold"></span></p>
            </div>
        </div>
        <!-- Footer -->
        <div class="flex justify-end p-4 border-t">
            <button onclick="closeViewProductModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Close</button>
        </div>
    </div>
</div>

<script>
function openViewProductModal(product) {
    // Fill modal fields - use the S3 URL from image_url
    const imgEl = document.getElementById('modalProductImage');
    if (product.image_url) {
        imgEl.src = product.image_url;
        imgEl.style.display = 'block';
        imgEl.onerror = function() {
            this.style.display = 'none';
            this.parentElement.innerHTML = '<div class="w-48 h-48 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">Image not available</div>';
        };
    } else {
        imgEl.style.display = 'none';
        imgEl.parentElement.innerHTML = '<div class="w-48 h-48 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">No Image</div>';
    }
    
    document.getElementById('modalProductName').textContent = product.name || '-';
    document.getElementById('modalProductCategory').textContent = product.category?.name || product.categoryID || '-';
    document.getElementById('modalProductUnitPrice').textContent = parseFloat(product.unitPrice).toFixed(2);
    document.getElementById('modalProductSellPrice').textContent = parseFloat(product.sellPrice).toFixed(2);
    document.getElementById('modalProductQty').textContent = product.qty;
    document.getElementById('modalProductMinStock').textContent = product.minStock;

    // Status
    let statusEl = document.getElementById('modalProductStatus');
    if(parseInt(product.qty) === 0) {
        statusEl.textContent = 'No Stock';
        statusEl.className = 'text-gray-500 font-semibold';
    } else if(parseInt(product.qty) <= parseInt(product.minStock)) {
        statusEl.textContent = 'Low Stock';
        statusEl.className = 'text-red-600 font-semibold';
    } else {
        statusEl.textContent = 'In Stock';
        statusEl.className = 'text-green-600 font-semibold';
    }

    // Show modal
    document.getElementById('viewProductModal').classList.remove('hidden');
}

function closeViewProductModal() {
    document.getElementById('viewProductModal').classList.add('hidden');
}
</script>

