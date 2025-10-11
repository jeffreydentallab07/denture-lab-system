@extends('layouts.app')

@section('page-title', 'Edit Material')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <a href="{{ route('admin.materials.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Materials
        </a>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Material - {{ $material->material_name }}</h1>

            @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.materials.update', $material->material_id) }}" method="POST"
                class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Current Status Alert -->
                <div
                    class="p-4 rounded-lg border-l-4 
                    {{ $material->status === 'out of stock' ? 'bg-red-50 border-red-500' : 
                       ($material->status === 'low stock' ? 'bg-orange-50 border-orange-500' : 'bg-green-50 border-green-500') }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 {{ $material->status === 'out of stock' ? 'text-red-500' : ($material->status === 'low stock' ? 'text-orange-500' : 'text-green-500') }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <span
                            class="font-semibold {{ $material->status === 'out of stock' ? 'text-red-700' : ($material->status === 'low stock' ? 'text-orange-700' : 'text-green-700') }}">
                            Current Status: {{ ucfirst($material->status) }}
                        </span>
                    </div>
                    @if($material->status !== 'available')
                    <p
                        class="text-sm mt-1 {{ $material->status === 'out of stock' ? 'text-red-600' : 'text-orange-600' }}">
                        This material needs restocking. Please update the quantity below.
                    </p>
                    @endif
                </div>

                <!-- Material Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Material Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="material_name" value="{{ old('material_name', $material->material_name) }}"
                        required
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Quantity <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-4">
                        <input type="number" name="quantity" value="{{ old('quantity', $material->quantity) }}" required
                            step="0.01" min="0"
                            class="flex-1 border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"
                            id="quantityInput" onchange="updateStatusPreview()">
                        <span class="text-gray-600 font-medium">{{ $material->unit }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Current quantity: <strong>{{ $material->quantity }} {{
                            $material->unit }}</strong></p>
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="unit" value="{{ old('unit', $material->unit) }}" required
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"
                        placeholder="e.g., kg, pcs, liters">
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Price (₱) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">₱</span>
                        <input type="number" name="price" value="{{ old('price', $material->price) }}" required
                            step="0.01" min="0"
                            class="w-full border-2 border-gray-300 rounded-lg p-3 pl-8 focus:border-blue-500 focus:outline-none">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Current price: <strong>₱{{ number_format($material->price, 2)
                            }}</strong></p>
                </div>

                <!-- Status Preview -->
                <div id="statusPreview" class="hidden p-4 rounded-lg border-2">
                    <p class="text-sm font-medium">New Status Preview:</p>
                    <p id="statusText" class="text-lg font-bold mt-1"></p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 mb-1">Status Auto-Update</h4>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• Status will be automatically updated based on quantity</li>
                                <li>• Quantity = 0 → Out of Stock</li>
                                <li>• Quantity < 10 → Low Stock</li>
                                <li>• Quantity ≥ 10 → Available</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.materials.index') }}"
                        class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Update Material
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateStatusPreview() {
    const quantity = parseFloat(document.getElementById('quantityInput').value) || 0;
    const preview = document.getElementById('statusPreview');
    const statusText = document.getElementById('statusText');
    
    let status, statusClass;
    
    if (quantity === 0) {
        status = 'Out of Stock';
        statusClass = 'text-red-600 bg-red-50 border-red-200';
    } else if (quantity < 10) {
        status = 'Low Stock';
        statusClass = 'text-orange-600 bg-orange-50 border-orange-200';
    } else {
        status = 'Available';
        statusClass = 'text-green-600 bg-green-50 border-green-200';
    }
    
    preview.className = 'p-4 rounded-lg border-2 ' + statusClass;
    statusText.textContent = status;
    preview.classList.remove('hidden');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateStatusPreview();
});
</script>
@endsection