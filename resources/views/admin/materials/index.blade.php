@extends('layouts.app')

@section('page-title', 'Materials Management')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Materials</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalMaterials }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Inventory Value</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">₱{{ number_format($totalValue, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Low Stock Items</h3>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $lowStockCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Out of Stock</h3>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $outOfStockCount }}</p>
        </div>
    </div>

    <!-- Add Material Button -->
    <button id="openAddMaterialModal"
        class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition">
        + Add Material
    </button>

    @if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-3 rounded bg-red-100 text-red-700 border border-red-300">
        {{ session('error') }}
    </div>
    @endif

    <!-- Materials Table -->
    <div class="overflow-x-auto rounded-xl shadow-lg mt-4">
        <table class="min-w-full border-separate border-spacing-0 bg-white">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Material Name</th>
                    <th class="px-6 py-3 text-left">Description</th>
                    <th class="px-6 py-3 text-left">Quantity</th>
                    <th class="px-6 py-3 text-left">Unit</th>
                    <th class="px-6 py-3 text-left">Price</th>
                    <th class="px-6 py-3 text-left">Total Value</th>
                    <th class="px-6 py-3 text-left">Supplier</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $material)
                <tr class="hover:bg-gray-50 border-b">
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $material->material_name }}</td>
                    <td class="px-6 py-3 text-gray-700 text-sm">{{ Str::limit($material->description, 40) ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-3 text-gray-700 font-medium">{{ $material->quantity }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $material->unit }}</td>
                    <td class="px-6 py-3 text-gray-700">₱{{ number_format($material->price, 2) }}</td>
                    <td class="px-6 py-3 text-gray-700 font-semibold">₱{{ number_format($material->total_value, 2) }}
                    </td>
                    <td class="px-6 py-3 text-gray-700">{{ $material->supplier ?? 'N/A' }}</td>
                    <td class="">
                        <div class="flex justify-center">
                            <span
                                class="px-2 py-1 text-xs rounded-full font-medium
                            {{ $material->status === 'available' ? 'bg-green-100 text-green-800' : 
                               ($material->status === 'low stock' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($material->status) }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-3 flex gap-2">
                        <!-- View Button -->
                        <a href="{{ route('admin.materials.show', $material->material_id) }}"
                            class="px-2 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition"
                            aria-label="View Material">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="w-4 h-4">
                                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                <path fill-rule="evenodd"
                                    d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>

                        <!-- Edit Button -->
                        <button
                            onclick="openEditMaterialModal({{ $material->material_id }}, '{{ addslashes($material->material_name) }}', '{{ addslashes($material->description ?? '') }}', {{ $material->quantity }}, '{{ $material->unit }}', {{ $material->price }}, '{{ addslashes($material->supplier ?? '') }}')"
                            class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                            aria-label="Edit Material">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="w-4 h-4">
                                <path
                                    d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z" />
                                <path
                                    d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z" />
                            </svg>
                        </button>

                        <!-- Delete Button -->
                        <button
                            onclick="openDeleteMaterialModal('{{ route('admin.materials.destroy', $material->material_id) }}')"
                            class="text-red-600 hover:text-red-800 transition p-1 rounded" aria-label="Delete Material">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="w-4 h-4">
                                <path fill-rule="evenodd"
                                    d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-6 text-gray-500 bg-white">No materials found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $materials->links() }}
    </div>
</div>

<!-- Add Material Modal -->
<div id="addMaterialModal"
    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-blue-900 text-white">
            <h2 class="text-2xl font-semibold">Add Material</h2>
            <button id="closeAddMaterialModal" class="text-white hover:text-gray-200 text-2xl">&times;</button>
        </div>

        <div class="p-6 space-y-6">
            <form id="addMaterialForm" action="{{ route('admin.materials.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Material Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="material_name"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Supplier</label>
                        <input type="text" name="supplier"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3"
                        class="mt-1 block w-full border-2 border-gray-300 rounded focus:border-indigo-600 focus:outline-none p-2"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantity <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="quantity" min="0"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="unit" placeholder="e.g., pcs, kg, box"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price (₱) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="price" step="0.01" min="0"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2"
                            required>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
            <button id="cancelAddMaterial"
                class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
            <button type="submit" form="addMaterialForm"
                class="py-2 px-4 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-md">Save</button>
        </div>
    </div>
</div>

<!-- Edit Material Modal -->
<div id="editMaterialModal"
    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <form id="editMaterialForm" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-blue-900 text-white">
                <h2 class="text-2xl font-semibold">Edit Material</h2>
                <button type="button" onclick="closeEditMaterialModal()"
                    class="text-white hover:text-gray-200 text-2xl">&times;</button>
            </div>

            <div class="p-6 space-y-6" id="editMaterialModalContent">
                <!-- Content will be dynamically inserted here -->
            </div>

            <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
                <button type="button" onclick="closeEditMaterialModal()"
                    class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
                <button type="submit"
                    class="py-2 px-4 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Material Modal -->
<div id="deleteMaterialModal"
    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Delete Material</h2>
            <button onclick="closeDeleteMaterialModal()"
                class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="p-6 space-y-4 text-gray-700">
            <p>Are you sure you want to delete this material? This action cannot be undone.</p>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end gap-4">
            <button onclick="closeDeleteMaterialModal()"
                class="px-4 py-2 rounded-md bg-gray-300 text-gray-700 hover:bg-gray-400">Cancel</button>
            <form id="deleteMaterialForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Add Material Modal
    const addModal = document.getElementById('addMaterialModal');
    document.getElementById('openAddMaterialModal')?.addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddMaterialModal').addEventListener('click', () => addModal.classList.add('hidden'));
    document.getElementById('cancelAddMaterial').addEventListener('click', () => addModal.classList.add('hidden'));

    // Edit Material Modal
    function openEditMaterialModal(id, name, description, quantity, unit, price, supplier) {
        const form = document.getElementById('editMaterialForm');
        const modalContent = document.getElementById('editMaterialModalContent');
        
        form.action = "{{ url('admin/materials') }}/" + id;
        
        modalContent.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium text-gray-700">Material Name <span class="text-red-500">*</span></label>
                    <input type="text" name="material_name" value="${name}" 
                           class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2" required>
                </div>
                <div>
                    <label class="block font-medium text-gray-700">Supplier</label>
                    <input type="text" name="supplier" value="${supplier}" 
                           class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2">
                </div>
            </div>
            <div>
                <label class="block font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3"
                    class="mt-1 block w-full border-2 border-gray-300 rounded focus:border-indigo-600 focus:outline-none p-2">${description}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block font-medium text-gray-700">Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" value="${quantity}" min="0"
                           class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2" required>
                </div>
                <div>
                    <label class="block font-medium text-gray-700">Unit <span class="text-red-500">*</span></label>
                    <input type="text" name="unit" value="${unit}"
                           class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2" required>
                </div>
                <div>
                    <label class="block font-medium text-gray-700">Price (₱) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="${price}" step="0.01" min="0"
                           class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2" required>
                </div>
            </div>
        `;
        
        document.getElementById('editMaterialModal').classList.remove('hidden');
    }

    function closeEditMaterialModal() {
        document.getElementById('editMaterialModal').classList.add('hidden');
        document.getElementById('editMaterialForm').reset();
    }

    // Delete Material Modal
    function openDeleteMaterialModal(actionUrl) {
        const form = document.getElementById('deleteMaterialForm');
        form.action = actionUrl;
        document.getElementById('deleteMaterialModal').classList.remove('hidden');
    }

    function closeDeleteMaterialModal() {
        document.getElementById('deleteMaterialModal').classList.add('hidden');
    }

    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            addModal.classList.add('hidden');
            closeEditMaterialModal();
            closeDeleteMaterialModal();
        }
    });
</script>
@endsection