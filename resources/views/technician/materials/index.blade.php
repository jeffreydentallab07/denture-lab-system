@extends('layouts.technician')

@section('title', 'Materials Inventory')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Materials Inventory</h1>
            <p class="text-gray-600">View available materials for your work</p>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow mb-6 p-4">
            <div class="flex gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Search materials..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <select id="statusFilter"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">All Status</option>
                    <option value="available">Available</option>
                    <option value="low stock">Low Stock</option>
                    <option value="out of stock">Out of Stock</option>
                </select>
            </div>
        </div>

        <!-- Materials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($materials as $material)
            <div class="material-card bg-white rounded-lg shadow hover:shadow-lg transition"
                data-status="{{ $material->status }}" data-name="{{ strtolower($material->material_name) }}">
                <div class="p-6">
                    <!-- Material Icon/Image -->
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>

                    <!-- Material Info -->
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $material->material_name }}</h3>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Category:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $material->category ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Unit:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $material->unit }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Price:</span>
                            <span class="text-sm font-medium text-green-600">₱{{ number_format($material->price, 2)
                                }}</span>
                        </div>
                    </div>

                    <!-- Quantity Bar -->
                    <div class="mb-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-600">Quantity</span>
                            <span class="text-xs font-bold 
                                {{ $material->quantity == 0 ? 'text-red-600' : 
                                   ($material->quantity <= 10 ? 'text-orange-600' : 'text-green-600') }}">
                                {{ $material->quantity }} {{ $material->unit }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                            $maxQuantity = 100; // Assuming max display quantity
                            $percentage = min(($material->quantity / $maxQuantity) * 100, 100);
                            @endphp
                            <div class="h-2 rounded-full transition-all
                                {{ $material->quantity == 0 ? 'bg-red-500' : 
                                   ($material->quantity <= 10 ? 'bg-orange-500' : 'bg-green-500') }}"
                                style="width: {{ $percentage }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <span
                        class="inline-block px-3 py-1 text-xs rounded-full font-medium
                        {{ $material->status === 'available' ? 'bg-green-100 text-green-800' : 
                           ($material->status === 'low stock' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($material->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <p class="text-gray-500">No materials found</p>
            </div>
            @endforelse
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800 mb-1">Important Note</h4>
                    <p class="text-xs text-blue-700">When you add materials to an appointment, the quantity will be
                        automatically deducted from the inventory. Please ensure you select the correct materials and
                        quantities.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    filterMaterials();
});

document.getElementById('statusFilter').addEventListener('change', function(e) {
    filterMaterials();
});

function filterMaterials() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const cards = document.querySelectorAll('.material-card');
    
    cards.forEach(card => {
        const name = card.dataset.name;
        const status = card.dataset.status;
        
        const matchesSearch = name.includes(searchTerm);
        const matchesStatus = statusFilter === 'all' || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection