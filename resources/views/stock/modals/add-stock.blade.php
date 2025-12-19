<!-- Add Stock Modal -->
<div id="addStockModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <h3 class="text-xl font-bold text-gray-800">Add Stock</h3>
            <button onclick="closeAddStockModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>

        <!-- Form -->
        <form id="addStockForm" method="POST" action="{{ route('stock.addStock') }}">
            @csrf
            <input type="hidden" name="product_id" id="stock_product_id">
            
            <div class="p-6 space-y-4">
                <!-- Product Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-2">Product Information</h4>
                    <p class="text-sm text-gray-600"><span class="font-medium">Name:</span> <span id="stock_product_name"></span></p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Current Stock:</span> <span id="stock_current_qty" class="font-bold text-indigo-600"></span></p>
                </div>

                <!-- Quantity to Add -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity to Add *</label>
                    <input type="number" name="quantity" id="stock_quantity" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter quantity to add">
                </div>

                <!-- Notes (Optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes" id="stock_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Add any notes..."></textarea>
                </div>

                <!-- New Stock Preview -->
                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                    <p class="text-sm text-indigo-800">
                        <span class="font-medium">New Stock After Addition:</span> 
                        <span id="stock_new_qty" class="font-bold text-lg"></span>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 p-6 border-t bg-gray-50">
                <button type="button" onclick="closeAddStockModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Add Stock</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentStockQty = 0;

function openAddStockModal(product) {
    // Set product ID (MongoDB uses _id as primary key)
    document.getElementById('stock_product_id').value = product._id;
    
    // Display product info
    document.getElementById('stock_product_name').textContent = product.name;
    currentStockQty = parseInt(product.qty) || 0;
    document.getElementById('stock_current_qty').textContent = currentStockQty;
    
    // Reset quantity input
    document.getElementById('stock_quantity').value = '';
    document.getElementById('stock_notes').value = '';
    document.getElementById('stock_new_qty').textContent = currentStockQty;
    
    // Show modal
    document.getElementById('addStockModal').classList.remove('hidden');
}

function closeAddStockModal() {
    document.getElementById('addStockModal').classList.add('hidden');
    document.getElementById('addStockForm').reset();
}

// Update new stock preview
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('stock_quantity');
    if (qtyInput) {
        qtyInput.addEventListener('input', function() {
            const addQty = parseInt(this.value) || 0;
            const newQty = currentStockQty + addQty;
            document.getElementById('stock_new_qty').textContent = newQty;
        });
    }
    
    // Add form submit handler to manually submit via fetch to ensure POST method
    const addStockForm = document.getElementById('addStockForm');
    if (addStockForm) {
        addStockForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            e.stopPropagation(); // Stop event bubbling
            
            console.log('Form submitting via fetch...', {
                method: this.method,
                action: this.action
            });
            
            // Get form data
            const formData = new FormData(this);
            
            // Get CSRF token from form
            const csrfToken = formData.get('_token');
            
            // Submit using fetch with POST
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                console.log('Response status:', response.status, response.statusText);
                if (response.ok || response.redirected) {
                    // If successful or redirected, close modal and reload
                    closeAddStockModal();
                    window.location.reload();
                } else {
                    alert('Error adding stock. Status: ' + response.status);
                    console.error('Error:', response);
                }
            })
            .catch(error => {
                alert('Network error. Please try again.');
                console.error('Fetch error:', error);
            });
        });
    }
});
</script>
