<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex flex-col h-full space-y-6">

      <!-- Top Section (1/4 of screen) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 h-1/4">

    <!-- Card 1 -->
    <div class="bg-white shadow rounded-lg p-6 flex justify-between">
        <!-- Left side: title, number, percentage -->
        <div class="flex flex-col justify-between">
            <div>
                <div class="text-gray-500 text-lg">Total Sales</div>
                <div class="text-2xl font-bold text-blue-500 mt-1">$2,344</div>
            </div>
            <div class="text-md text-blue-500 font-semibold mt-2">+20% from last month</div>
        </div>
        <!-- Right side: icon -->
        <div class="text-blue-500 text-3xl">
            <i class="fas fa-dollar-sign"></i>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white shadow rounded-lg p-6 flex justify-between">
        <div class="flex flex-col justify-between">
            <div>
                <div class="text-gray-500 text-lg">Total Orders</div>
                <div class="text-2xl font-bold text-green-500 mt-1">125</div>
            </div>
            <div class="text-md text-green-500 font-semibold mt-2">+15% from last month</div>
        </div>
        <div class="text-green-500 text-3xl">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white shadow rounded-lg p-6 flex justify-between">
        <div class="flex flex-col justify-between">
            <div>
                <div class="text-gray-500 text-lg">Total Users</div>
                <div class="text-2xl font-bold text-yellow-500 mt-1">342</div>
            </div>
            <div class="text-md text-yellow-500 font-semibold mt-2">+8% from last month</div>
        </div>
        <div class="text-yellow-500 text-3xl">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white shadow rounded-lg p-6 flex justify-between">
        <div class="flex flex-col justify-between">
            <div>
                <div class="text-gray-500 text-lg">Revenue</div>
                <div class="text-2xl font-bold text-red-500 mt-1">$12,500</div>
            </div>
            <div class="text-md text-red-500 font-semibold mt-2">+12% from last month</div>
        </div>
        <div class="text-red-500 text-3xl">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

</div>



<!-- Bottom Section (3/4 of screen) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-3/4">

    <!-- Box 1: Sale Overview -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="font-semibold text-xl text-gray-800 mb-4">Sale Overview</div>
        <div class="space-y-4">
            <!-- Small Box 1 -->
            <div class="bg-blue-100 p-4 rounded-lg flex justify-between items-center w-full">
                <div class="flex flex-col">
                    <span class="text-blue-800 font-semibold">Indoor Sale</span>
                    <span class="text-blue-600 text-sm mt-1">+14%</span>
                </div>
                <div class="text-blue-800 font-bold text-xl">$3,424</div>
            </div>

            <!-- Small Box 2 -->
            <div class="bg-green-100 p-4 rounded-lg flex justify-between items-center w-full">
                <div class="flex flex-col">
                    <span class="text-green-800 font-semibold">Outdoor Sale</span>
                    <span class="text-green-600 text-sm mt-1">+10%</span>
                </div>
                <div class="text-green-800 font-bold text-xl">$2,850</div>
            </div>


            <!-- Small Box 3 -->
            <div class="bg-yellow-100 p-4 rounded-lg flex justify-between items-center w-full">
                <div class="flex flex-col">
                    <span class="text-yellow-800 font-semibold">Online Sale</span>
                    <span class="text-yellow-600 text-sm mt-1">+18%</span>
                </div>
                <div class="text-yellow-800 font-bold text-xl">$4,120</div>
            </div>

            <!-- Small Box 4 -->
            <div class="bg-red-100 p-4 rounded-lg flex justify-between items-center w-full">
                <div class="flex flex-col">
                    <span class="text-red-800 font-semibold">Wholesale</span>
                    <span class="text-red-600 text-sm mt-1">+12%</span>
                </div>
                <div class="text-red-800 font-bold text-xl">$5,600</div>
            </div>
        </div>
    </div>

    <!-- Box 2: Top Sale Items -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="font-semibold text-xl text-gray-800 mb-4">Top Sale Items</div>
        <div class="space-y-4">
            <!-- Small Box 1 -->
            <div class="bg-purple-100 p-4 rounded-lg flex justify-between items-center w-full">
                <div class="flex flex-col">
                    <span class="text-purple-800 font-semibold">Product A</span>
                    <span class="text-purple-600 text-sm mt-1">+20%</span>
                </div>
                <div class="text-purple-800 font-bold text-xl">$1,200</div>
            </div>

            <!-- Small Box 2 -->
            <div class="bg-pink-100 p-4 rounded-lg flex justify-between items-center w-full">
                <div class="flex flex-col">
                    <span class="text-pink-800 font-semibold">Product B</span>
                    <span class="text-pink-600 text-sm mt-1">+15%</span>
                </div>
                <div class="text-pink-800 font-bold text-xl">$900</div>
            </div>

            <!-- Small Box 3 -->
            <div class="bg-indigo-100 p-4 rounded-lg flex justify-between items-center w-full">
                <div class="flex flex-col">
                    <span class="text-indigo-800 font-semibold">Product C</span>
                    <span class="text-indigo-600 text-sm mt-1">+18%</span>
                </div>
                <div class="text-indigo-800 font-bold text-xl">$750</div>
            </div>

            <!-- Small Box 4 -->
            <div class="bg-teal-100 p-4 rounded-lg flex justify-between items-center w-full">
                <div class="flex flex-col">
                    <span class="text-teal-800 font-semibold">Product D</span>
                    <span class="text-teal-600 text-sm mt-1">+12%</span>
                </div>
                <div class="text-teal-800 font-bold text-xl">$1,050</div>
            </div>
        </div>
    </div>

</div>



    </div>
</x-app-layout>
