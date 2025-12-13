<div
    id="addCategoryModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
>
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg">

        <h2 class="text-xl font-semibold mb-4">Add Product Category</h2>

        <form action="{{ route('stock.add-category') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Category Name</label>
                <input
                    type="text"
                    name="name"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                    required
                >
            </div>

            <div class="flex justify-end space-x-2">
                <button
                    type="button"
                    onclick="closeAddCategoryModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    Save
                </button>
            </div>

        </form>

    </div>
</div>


<script>
    function openAddCategoryModal() {
        document.getElementById('addCategoryModal').classList.remove('hidden');
    }

    function closeAddCategoryModal() {
        document.getElementById('addCategoryModal').classList.add('hidden');
    }
</script>
