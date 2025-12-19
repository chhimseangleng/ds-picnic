<x-app-layout>
    <div class="p-8 bg-gray-50 min-h-screen">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('employees') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-600 rounded-md text-sm font-medium hover:bg-gray-100 hover:text-gray-800 transition-all shadow">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Employees
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg mb-6 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Employee Detail Card -->
        <div class="bg-white rounded-xl p-8 shadow mb-6">
            <!-- Header with Avatar -->
            <div class="flex items-center gap-6 mb-8 pb-8 border-b-2 border-gray-100">
                <!-- Profile Image or Default Avatar -->
                @if($employee->image_url)
                    <div class="relative w-20 h-20 flex-shrink-0">
                        <img src="{{ $employee->image_url }}" alt="{{ $employee->name }}" class="w-full h-full rounded-full object-cover">
                    </div>
                @else
                    @php
                        $colors = ['bg-gradient-to-br from-pink-400 to-red-500', 'bg-gradient-to-br from-blue-400 to-cyan-500', 'bg-gradient-to-br from-green-400 to-teal-400', 'bg-gradient-to-br from-pink-400 to-yellow-400', 'bg-gradient-to-br from-teal-300 to-pink-200'];
                        $colorClass = $colors[ord(substr($employee->name, 0, 1)) % 5];
                    @endphp
                    <div class="w-20 h-20 rounded-full {{ $colorClass }} flex items-center justify-center text-3xl font-semibold text-white flex-shrink-0">
                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1">
                    <h1 class="text-3xl font-semibold text-gray-800 mb-2">{{ $employee->name }}</h1>
                    @php
                        $roleBadgeClasses = [
                            'Manager' => 'bg-green-100 text-green-800',
                            'Employee' => 'bg-blue-100 text-blue-800',
                            'Cashier' => 'bg-yellow-100 text-yellow-800',
                            'Stock Manager' => 'bg-indigo-100 text-indigo-800',
                        ];
                        $badgeClass = $roleBadgeClasses[$employee->role] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-md text-xs font-semibold capitalize {{ $badgeClass }}">
                        {{ $employee->role }}
                    </span>
                </div>
            </div>

            <!-- Edit Form -->
            <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 pb-2 border-b border-gray-200">Personal Information</h3>
                    
                    <!-- Profile Image Upload -->
                    <div class="flex items-center gap-4 mb-4">
                        <div class="relative">
                            @if($employee->image_url)
                                <img src="{{ $employee->image_url }}" id="profileImagePreview" alt="{{ $employee->name }}" class="w-24 h-24 rounded-full object-cover">
                            @else
                                @php
                                    $colorClass = $colors[ord(substr($employee->name, 0, 1)) % 5];
                                @endphp
                                <div id="profileImagePreview" class="w-24 h-24 rounded-full {{ $colorClass }} flex items-center justify-center text-3xl font-semibold text-white">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>
                            @endif
                            <label for="profile_image" class="absolute bottom-0 right-0 w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center border-3 border-white cursor-pointer hover:bg-indigo-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                    <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                                    <circle cx="12" cy="13" r="4"/>
                                </svg>
                            </label>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                            <input type="file" name="profile_image" id="profile_image" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" onchange="previewDetailImage(this)">
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF, WEBP (Max 5MB)</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div class="flex flex-col gap-2">
                            <label for="name" class="text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ $employee->name }}" required class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="gender" class="text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" id="gender" required class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                                <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ $employee->gender == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="email" class="text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ $employee->email ?? '' }}" class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100" placeholder="employee@example.com">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="phone" class="text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ $employee->phone }}" required class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 pb-2 border-b border-gray-200">Employment Details</h3>
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div class="flex flex-col gap-2">
                            <label for="role" class="text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" required class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                                <option value="Manager" {{ $employee->role == 'Manager' ? 'selected' : '' }}>Manager</option>
                                <option value="Employee" {{ $employee->role == 'Employee' ? 'selected' : '' }}>Employee</option>
                                <option value="Cashier" {{ $employee->role == 'Cashier' ? 'selected' : '' }}>Cashier</option>
                                <option value="Stock Manager" {{ $employee->role == 'Stock Manager' ? 'selected' : '' }}>Stock Manager</option>
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="salary" class="text-sm font-medium text-gray-700">Salary</label>
                            <input type="number" name="salary" id="salary" value="{{ $employee->salary ?? '' }}" step="0.01" class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100" placeholder="Enter salary">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="startWork" class="text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="startWork" id="startWork" value="{{ \Carbon\Carbon::parse($employee->startWork)->format('Y-m-d') }}" required class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="stopWork" class="text-sm font-medium text-gray-700">End Date (if applicable)</label>
                            <input type="date" name="stopWork" id="stopWork" value="{{ $employee->stopWork ? \Carbon\Carbon::parse($employee->stopWork)->format('Y-m-d') : '' }}" class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="working" class="text-sm font-medium text-gray-700">Employment Status</label>
                            <select name="working" id="working" class="px-3.5 py-2.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                                <option value="1" {{ $employee->working ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$employee->working ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4 justify-end pt-6 border-t-2 border-gray-100">
                    <a href="{{ route('employees') }}" class="px-8 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 hover:-translate-y-0.5 hover:shadow-lg transition-all">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Additional Info Card -->
        <div class="bg-white rounded-xl p-6 shadow">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Additional Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $employee->id }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</span>
                    <span class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($employee->created_at)->format('M d, Y h:i A') }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</span>
                    <span class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($employee->updated_at)->format('M d, Y h:i A') }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Employment Duration</span>
                    <span class="text-sm font-semibold text-gray-800">
                        @if($employee->stopWork)
                            {{ \Carbon\Carbon::parse($employee->startWork)->diffInDays(\Carbon\Carbon::parse($employee->stopWork)) }} days
                        @else
                            {{ \Carbon\Carbon::parse($employee->startWork)->diffInDays(now()) }} days (Active)
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preview image when selected on detail page
        function previewDetailImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profileImagePreview');
                    preview.outerHTML = '<img src="' + e.target.result + '" id="profileImagePreview" alt="Preview" class="w-24 h-24 rounded-full object-cover">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
