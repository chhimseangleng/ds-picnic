<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <h3 class="text-lg font-semibold mb-4">System Settings</h3>

            <div class="space-y-4">

                <div>
                    <label class="text-gray-600">Business Name</label>
                    <input type="text" class="w-full mt-1 p-2 border rounded" placeholder="Your Store">
                </div>

                <div>
                    <label class="text-gray-600">Contact Email</label>
                    <input type="email" class="w-full mt-1 p-2 border rounded" placeholder="email@example.com">
                </div>

                <div>
                    <label class="text-gray-600">Currency</label>
                    <select class="w-full mt-1 p-2 border rounded">
                        <option>USD ($)</option>
                        <option>EUR (€)</option>
                        <option>KHR (៛)</option>
                    </select>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
