<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Bundles') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="bg-gray-100 shadow rounded-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Bundle Management</h3>
                <div class="flex gap-3">
                    <a href="{{ route('stock') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Back to Stock
                    </a>
                    <a href="{{ route('bundles.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        Create New Bundle
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Bundle Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($bundles as $bundle)
                    <div class="bg-white rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-300 overflow-hidden flex flex-col p-4">
                        <!-- Bundle Image -->
                        <div class="flex justify-center mb-3">
                            @if($bundle->image_url)
                                <img src="{{ $bundle->image_url }}" alt="{{ $bundle->name }}"
                                    class="w-32 h-32 object-contain rounded-lg shadow-md bg-gray-100">
                            @else
                                <div class="w-32 h-32 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v14a2 2 0 002 2h14a2 2 0 002-2V7m-6 0V3H9v4M3 7h18"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Bundle Name -->
                        <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">{{ $bundle->name }}</h3>

                        @if($bundle->description)
                            <p class="text-sm text-gray-600 text-center mb-3 line-clamp-2">{{ $bundle->description }}</p>
                        @endif

                        <!-- Bundle Info -->
                        <div class="mt-auto space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Products:</span>
                                <span class="font-semibold">{{ count($bundle->products) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Available:</span>
                                <span class="font-semibold {{ $bundle->available_stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $bundle->available_stock }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Price:</span>
                                <span class="font-bold text-purple-600 text-lg">${{ number_format($bundle->bundlePrice, 2) }}</span>
                            </div>
                        </div>

                        <!-- Products List -->
                        <div class="mt-3 p-3 bg-gray-50 rounded">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Includes:</p>
                            <div class="space-y-1">
                                @foreach($bundle->products_with_details as $item)
                                    @if($item)
                                        <p class="text-xs text-gray-600">
                                            {{ $item['quantity'] }}× {{ $item['product']->name }}
                                        </p>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('bundles.edit', $bundle->_id) }}" 
                                class="flex-1 py-2 bg-blue-200 text-blue-600 font-bold rounded-lg hover:bg-blue-300 text-center text-sm">
                                Edit
                            </a>
                            <button type="button" 
                                onclick="confirmDeleteBundle('{{ $bundle->_id }}', '{{ $bundle->name }}')"
                                class="flex-1 py-2 bg-red-300 text-red-600 rounded-lg font-bold hover:bg-red-400 text-sm">
                                Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="mt-4 text-gray-500 text-lg">No bundles created yet</p>
                        <a href="{{ route('bundles.create') }}" class="mt-4 inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            Create Your First Bundle
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteBundleModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden animate-fadeIn">
            <!-- Header -->
            <div class="flex justify-between items-center p-6 border-b bg-gradient-to-r from-red-500 to-red-600">
                <h3 class="text-2xl font-bold text-white">Delete Bundle</h3>
                <button onclick="closeDeleteBundleModal()" class="text-white hover:text-gray-200 text-3xl font-bold">&times;</button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <div class="flex items-center justify-center mb-4">
                    <div class="rounded-full bg-red-100 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>

                <h4 class="text-lg font-semibold text-gray-800 text-center mb-2">Are you sure?</h4>
                <p class="text-gray-600 text-center mb-2">You are about to delete:</p>
                <p class="text-center font-bold text-gray-800 mb-4" id="delete_bundle_name"></p>
                <p class="text-sm text-gray-500 text-center">This action cannot be undone.</p>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 p-6 border-t bg-gray-50">
                <button type="button" onclick="closeDeleteBundleModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition">Cancel</button>
                <form id="deleteBundleForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition">Delete Bundle</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function confirmDeleteBundle(bundleId, bundleName) {
        // Set bundle name
        document.getElementById('delete_bundle_name').textContent = bundleName;
        
        // Set form action - MongoDB uses _id as primary key
        document.getElementById('deleteBundleForm').action = `/stock/bundles/${bundleId}`;
        
        // Show modal
        document.getElementById('deleteBundleModal').classList.remove('hidden');
    }

    function closeDeleteBundleModal() {
        document.getElementById('deleteBundleModal').classList.add('hidden');
    }
    </script>
</x-app-layout>
