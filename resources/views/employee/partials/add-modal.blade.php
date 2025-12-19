<!-- Add/Edit Employee Modal -->
<div id="employeeModal" class="hidden fixed inset-0 z-[9999] items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" onclick="closeEmployeeModal()"></div>
    <div class="relative bg-white rounded-2xl p-8 w-11/12 max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl z-[10000]">
        <h2 id="modalTitle" class="text-xl font-semibold text-gray-800 mb-6 text-center">Add Employee / Edit Employee</h2>

        <form id="employeeForm" method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="employee_id" id="employeeId">

            <!-- Avatar Preview -->
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-green-400 to-teal-400 flex items-center justify-center text-3xl font-semibold text-white cursor-pointer overflow-hidden" id="avatarPreview">
                        <span id="avatarLetter">?</span>
                        <img id="avatarImage" class="hidden absolute inset-0 w-full h-full object-cover" src="" alt="Preview">
                    </div>
                    <label for="profile_image" class="absolute bottom-0 right-0 w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center border-3 border-white cursor-pointer hover:bg-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                </div>
            </div>

            <!-- Form Fields -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="flex flex-col gap-2">
                    <label for="name" class="text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" required class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100" oninput="updateAvatarLetter(this.value)">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="gender" class="text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" required class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="startWork" class="text-sm font-medium text-gray-700">Join Date</label>
                    <input type="date" name="startWork" id="startWork" required class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                </div>
            </div>

            <div class="flex flex-col gap-2 mb-4">
                <label for="role" class="text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" required class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                    <option value="">Select Role</option>
                    <option value="Manager">Manager</option>
                    <option value="Employee">Employee</option>
                    <option value="Cashier">Cashier</option>
                    <option value="Stock Manager">Stock Manager</option>
                </select>
            </div>

            <div class="flex flex-col gap-2 mb-4">
                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100" placeholder="employee@example.com">
            </div>

            <div class="flex flex-col gap-2 mb-4">
                <label for="salary" class="text-sm font-medium text-gray-700">Salary</label>
                <input type="number" name="salary" id="salary" step="0.01" class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100" placeholder="Enter salary">
            </div>

            <div class="flex flex-col gap-2 mb-4">
                <label for="phone" class="text-sm font-medium text-gray-700">Contact Info</label>
                <input type="text" name="phone" id="phone" required class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100" placeholder="Phone number">
            </div>

            <!-- Modal Actions -->
            <div class="flex gap-4 mt-8">
                <button type="button" onclick="closeEmployeeModal()" class="flex-1 px-3 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-3 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all">
                    <span id="submitText">Add Employee</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview image when selected
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarImage').src = e.target.result;
                document.getElementById('avatarImage').classList.remove('hidden');
                document.getElementById('avatarLetter').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Open modal for adding new employee
    function openEmployeeModal() {
        document.getElementById('employeeModal').classList.remove('hidden');
        document.getElementById('employeeModal').classList.add('flex');
        document.getElementById('modalTitle').textContent = 'Add Employee / Edit Employee';
        document.getElementById('employeeForm').action = '{{ route("employees.store") }}';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('submitText').textContent = 'Add Employee';
        document.getElementById('employeeForm').reset();
        document.getElementById('avatarLetter').textContent = '?';
        document.getElementById('avatarLetter').classList.remove('hidden');
        document.getElementById('avatarImage').classList.add('hidden');
    }

    // Open modal for editing employee
    function editEmployee(id, name, gender, role, phone, startWork, email, salary) {
        document.getElementById('employeeModal').classList.remove('hidden');
        document.getElementById('employeeModal').classList.add('flex');
        document.getElementById('modalTitle').textContent = 'Add Employee / Edit Employee';
        document.getElementById('employeeForm').action = '/employees/' + id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('submitText').textContent = 'Add Employee';
        
        // Populate form fields
        document.getElementById('name').value = name;
        document.getElementById('gender').value = gender;
        document.getElementById('role').value = role;
        document.getElementById('phone').value = phone;
        document.getElementById('startWork').value = startWork;
        document.getElementById('email').value = email || '';
        document.getElementById('salary').value = salary || '';
        document.getElementById('avatarLetter').textContent = name.charAt(0).toUpperCase();
    }

    // Close modal
    function closeEmployeeModal() {
        document.getElementById('employeeModal').classList.add('hidden');
        document.getElementById('employeeModal').classList.remove('flex');
    }

    // Update avatar letter when typing name
    function updateAvatarLetter(value) {
        const letter = value.length > 0 ? value.charAt(0).toUpperCase() : '?';
        document.getElementById('avatarLetter').textContent = letter;
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEmployeeModal();
        }
    });
</script>
