@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <a href="{{ route('admin.materials.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Materials
        </a>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-900 to-blue-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">{{ $material->material_name }}</h1>
                        <p class="text-blue-100 mt-2">{{ $material->supplier ?? 'No supplier specified' }}</p>
                    </div>
                    <span
                        class="px-4 py-2 text-sm rounded-full font-semibold
                        {{ $material->status === 'available' ? 'bg-green-500 text-white' : 
                           ($material->status === 'low stock' ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white') }}">
                        {{ ucfirst($material->status) }}
                    </span>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-gray-50">
                <div class="bg-white p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600">Current Stock</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $material->quantity }} {{ $material->unit }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600">Unit Price</p>
                    <p class="text-2xl font-bold text-green-600">₱{{ number_format($material->price, 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600">Total Value</p>
                    <p class="text-2xl font-bold text-purple-600">₱{{ number_format($material->total_value, 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="text-2xl font-bold {{ $material->status_color }}-600">
                        {{ ucfirst($material->status) }}
                    </p>
                </div>
            </div>

            <!-- Material Details -->
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Material Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Material Name</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $material->material_name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Supplier</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $material->supplier ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Quantity</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $material->quantity }} {{ $material->unit }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Unit Price</p>
                        <p class="text-lg font-semibold text-gray-800">₱{{ number_format($material->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Value</p>
                        <p class="text-lg font-semibold text-gray-800">₱{{ number_format($material->total_value, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Last Updated</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $material->updated_at->format('M d, Y h:i A')
                            }}</p>
                    </div>
                </div>

                @if($material->description)
                <div class="mt-6 pt-6 border-t">
                    <p class="text-sm text-gray-500 mb-2">Description</p>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ $material->description }}</p>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="p-6 bg-gray-50 border-t flex gap-3">
                <button onclick="openEditModal()"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Edit Material
                </button>

                <button onclick="confirmDelete()"
                    class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                    Delete Material
                </button>

                <a href="{{ route('admin.materials.index') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="bg-white rounded-lg shadow-lg mt-6 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Activity Timeline</h2>

            <div class="space-y-4">
                <div class="flex gap-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Material Created</p>
                        <p class="text-xs text-gray-500">{{ $material->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                @if($material->updated_at != $material->created_at)
                <div class="flex gap-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Last Updated</p>
                        <p class="text-xs text-gray-500">{{ $material->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                @endif

                @if($material->status === 'low stock')
                <div class="flex gap-3">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full mt-1.5"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Low Stock Warning</p>
                        <p class="text-xs text-gray-500">Current quantity: {{ $material->quantity }} {{ $material->unit
                            }}</p>
                    </div>
                </div>
                @endif

                @if($material->status === 'out of stock')
                <div class="flex gap-3">
                    <div class="w-2 h-2 bg-red-500 rounded-full mt-1.5"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Out of Stock</p>
                        <p class="text-xs text-gray-500">Please restock immediately</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <form action="{{ route('admin.materials.update', $material->material_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-blue-900 text-white">
                <h2 class="text-2xl font-semibold">Edit Material</h2>
                <button type="button" onclick="closeEditModal()"
                    class="text-white hover:text-gray-200 text-2xl">&times;</button>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-medium text-gray-700">Material Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="material_name" value="{{ $material->material_name }}"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2"
                            required>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700">Supplier</label>
                        <input type="text" name="supplier" value="{{ $material->supplier }}"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2">
                    </div>
                </div>
                <div>
                    <label class="block font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3"
                        class="mt-1 block w-full border-2 border-gray-300 rounded focus:border-indigo-600 focus:outline-none p-2">{{ $material->description }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block font-medium text-gray-700">Quantity <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="quantity" value="{{ $material->quantity }}" min="0"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2"
                            required>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700">Unit <span class="text-red-500">*</span></label>
                        <input type="text" name="unit" value="{{ $material->unit }}"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2"
                            required>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700">Price (₱) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ $material->price }}" step="0.01" min="0"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2"
                            required>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
                <button type="button" onclick="closeEditModal()"
                    class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
                <button type="submit"
                    class="py-2 px-4 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md">Update</button>
            </div>
        </form>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Confirm Delete</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this material? This action cannot be undone.</p>
        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Cancel
            </button>
            <form action="{{ route('admin.materials.destroy', $material->material_id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
<script>
    function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeDeleteModal();
    }
});
</script>
@endsection