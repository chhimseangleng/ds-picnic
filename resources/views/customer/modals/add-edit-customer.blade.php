<!-- Add/Edit Customer Modal -->
<div id="customerModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden animate-fadeIn">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b bg-gradient-to-r from-blue-500 to-blue-600">
            <h3 id="modalTitle" class="text-2xl font-bold text-white">Add Customer</h3>
            <button onclick="closeCustomerModal()" class="text-white hover:text-gray-200 text-3xl font-bold">&times;</button>
        </div>

        <!-- Form -->
        <form id="customerForm" method="POST">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">
            
            <div class="p-6 space-y-4">
                <!-- Name Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                    <input type="text" name="name" id="customer_name" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Enter customer name">
                </div>

                <!-- Contact Info Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Info *</label>
                    <input type="text" name="phone" id="customer_phone" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Enter phone number">
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 p-6 border-t bg-gray-50">
                <button type="button" onclick="closeCustomerModal()" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition">
                    Cancel
                </button>
                <button type="submit" id="submitButton" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                    Add Customer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let isEditMode = false;
let currentCustomerId = null;

function openAddCustomerModal() {
    isEditMode = false;
    currentCustomerId = null;
    
    // Reset form
    document.getElementById('customerForm').reset();
    document.getElementById('customerForm').action = '{{ route('customers.store') }}';
    document.getElementById('methodField').value = 'POST';
    
    // Update modal title and button
    document.getElementById('modalTitle').textContent = 'Add Customer';
    document.getElementById('submitButton').textContent = 'Add Customer';
    
    // Show modal
    document.getElementById('customerModal').classList.remove('hidden');
}

function openEditCustomerModal(customerId, customerName, customerPhone) {
    isEditMode = true;
    currentCustomerId = customerId;
    
    // Set form action for update
    document.getElementById('customerForm').action = `/customers/${customerId}`;
    document.getElementById('methodField').value = 'PUT';
    
    // Populate form fields
    document.getElementById('customer_name').value = customerName;
    document.getElementById('customer_phone').value = customerPhone;
    
    // Update modal title and button
    document.getElementById('modalTitle').textContent = 'Edit Customer';
    document.getElementById('submitButton').textContent = 'Update Customer';
    
    // Show modal
    document.getElementById('customerModal').classList.remove('hidden');
}

function closeCustomerModal() {
    document.getElementById('customerModal').classList.add('hidden');
    document.getElementById('customerForm').reset();
    isEditMode = false;
    currentCustomerId = null;
}
</script>
