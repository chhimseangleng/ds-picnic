<x-app-layout>
    <div class="p-8 bg-gray-50 min-h-screen">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8 bg-white px-8 py-6 rounded-lg shadow">
            <h1 class="text-2xl font-semibold text-gray-800">Employee</h1>
            <button onclick="openEmployeeModal()" class="bg-indigo-600 text-white px-6 py-2.5 rounded-md font-medium text-sm hover:bg-indigo-700 transition-all hover:-translate-y-0.5 hover:shadow-lg">
                + Add Employee
            </button>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg mb-6 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter and Search Section -->
        <div class="bg-white px-8 py-6 rounded-lg mb-8 shadow">
            <form method="GET" action="{{ route('employees') }}" class="flex gap-8 items-end">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-600">Date</label>
                    <select name="date_filter" onchange="this.form.submit()" class="px-4 py-2.5 border border-gray-300 rounded-md text-sm min-w-[200px] focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-600">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Employee" class="px-4 py-2.5 border border-gray-300 rounded-md text-sm min-w-[200px] focus:outline-none focus:border-indigo-600 focus:ring-3 focus:ring-indigo-100">
                </div>
            </form>
        </div>

        <!-- Employee Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">
            @forelse($employees as $employee)
                <div class="bg-white rounded-xl p-6 shadow hover:-translate-y-1 hover:shadow-xl transition-all flex flex-col items-center h-[380px]">
                    <!-- Avatar / Image Container -->
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-4 flex-shrink-0">
                        @if($employee->image_url)
                            <img src="{{ $employee->image_url }}" alt="{{ $employee->name }}" class="w-full h-full object-cover">
                        @else
                            @php
                                $colors = ['bg-gradient-to-br from-pink-400 to-red-500', 'bg-gradient-to-br from-blue-400 to-cyan-500', 'bg-gradient-to-br from-green-400 to-teal-400', 'bg-gradient-to-br from-pink-400 to-yellow-400', 'bg-gradient-to-br from-teal-300 to-pink-200'];
                                $colorClass = $colors[$loop->index % 5];
                            @endphp
                            <div class="w-full h-full {{ $colorClass }} flex items-center justify-center text-2xl font-semibold text-white">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Employee Info -->
                    <div class="text-center w-full flex-1 flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            {{ $employee->name }} 
                            <span class="text-sm font-medium text-gray-500">({{ substr($employee->gender, 0, 1) }})</span>
                        </h3>

                        <div class="my-4 space-y-2 flex-1">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 font-medium">Role:</span>
                                @php
                                    $roleBadgeClasses = [
                                        'Manager' => 'bg-green-100 text-green-800',
                                        'Employee' => 'bg-blue-100 text-blue-800',
                                        'Cashier' => 'bg-yellow-100 text-yellow-800',
                                        'Stock Manager' => 'bg-indigo-100 text-indigo-800',
                                    ];
                                    $badgeClass = $roleBadgeClasses[$employee->role] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 rounded-md text-xs font-semibold capitalize {{ $badgeClass }}">
                                    {{ $employee->role }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 font-medium">Status:</span>
                                @if($employee->working)
                                    <span class="px-3 py-1 rounded-md text-xs font-semibold bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 font-medium">Contact Info:</span>
                                <span class="text-gray-800 font-medium">{{ $employee->phone }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 font-medium">Joined:</span>
                                <span class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($employee->startWork)->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-4 w-full justify-center">
                            <a href="{{ route('employees.show', $employee->id) }}" class="px-6 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 transition-all">
                                View Details
                            </a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-all flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-gray-500 text-base">
                    <p>No employees found. Click "+ Add Employee" to add your first employee.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Include Add/Edit Employee Modal -->
    @include('employee.partials.add-modal')
</x-app-layout>

