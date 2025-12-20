<!-- Mobile Menu Button (shown on mobile only) -->
<button id="mobile-menu-button" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-md shadow-lg">
    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<!-- Overlay (shown when mobile menu is open) -->
<div id="mobile-menu-overlay" class="hidden lg:hidden fixed inset-0 bg-black bg-opacity-50 z-30"></div>

<!-- Sidebar Navigation -->
<nav id="sidebar" class="fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col w-64 bg-white border-r border-gray-200 min-h-screen z-40">

    <!-- Logo + Store Name -->
    <div class="flex flex-col items-center py-6 border-b border-gray-200">
        <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="w-2/3 mb-2">
        <h1 class="text-xl font-bold text-gray-800">The Picnic Store</h1>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 mt-6 px-4 flex flex-col space-y-1 overflow-y-auto">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-3 py-3 rounded transition
       {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Dashboard
        </a>

        <!-- Sales -->
        <a href="{{ route('sales') }}"
            class="flex items-center px-3 py-3 rounded transition
       {{ request()->routeIs('sales') ? 'bg-blue-500 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fas fa-shopping-bag mr-3"></i>
            Sales
        </a>

        <!-- Stock -->
        <a href="{{ route('stock') }}"
            class="flex items-center px-3 py-3 rounded transition
       {{ request()->routeIs('stock') ? 'bg-blue-500 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fas fa-boxes mr-3"></i>
            Stock
        </a>

        <!-- Cashflow -->
        <a href="{{ route('cashflow') }}"
            class="flex items-center px-3 py-3 rounded transition
       {{ request()->routeIs('cashflow') ? 'bg-blue-500 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fas fa-chart-line mr-3"></i>
            Cashflow
        </a>

        <!-- Employees -->
        <a href="{{ route('employees') }}"
            class="flex items-center px-3 py-3 rounded transition
       {{ request()->routeIs('employees') ? 'bg-blue-500 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fas fa-users mr-3"></i>
            Employees
        </a>

        <!-- Customers -->
        <a href="{{ route('customers') }}"
            class="flex items-center px-3 py-3 rounded transition
       {{ request()->routeIs('customers') ? 'bg-blue-500 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fas fa-user-friends mr-3"></i>
            Customers
        </a>

        <!-- Settings -->
        <a href="{{ route('settings') }}"
            class="flex items-center px-3 py-3 rounded transition
       {{ request()->routeIs('settings') ? 'bg-blue-500 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fas fa-cog mr-3"></i>
            Settings
        </a>

    </div>



    <!-- User Profile Dropdown at Bottom -->
    <div class="px-4 py-6 border-t border-gray-200 mt-auto relative">

        <x-dropdown align="left" width="48">
            <!-- Trigger -->
            <x-slot name="trigger">
                <button
                    class="w-full inline-flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    <div class="text-left">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <svg class="ml-2 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <!-- Dropdown content opens above -->
            <x-slot name="content">
                <div
                    class="absolute bottom-full left-0 mb-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                    <x-dropdown-link :href="'#'">
                        Profile
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Log Out
                        </x-dropdown-link>
                    </form>
                </div>
            </x-slot>
        </x-dropdown>

    </div>
</nav>

<!-- Mobile Menu Script -->
<script>
    const menuButton = document.getElementById('mobile-menu-button');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-menu-overlay');

    // Toggle mobile menu
    menuButton.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });

    // Close menu when clicking overlay
    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });

    // Close menu when clicking a link (optional, for better UX)
    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) { // Only on mobile
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });
    });
</script>
