@extends('layouts.app')

@section('page-title', 'Add Material')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
    <form action="{{ route('admin.materials.store') }}" method="POST" class="space-y-4" id="materialForm">
        @csrf

        <div>
            <label class="block font-medium mb-1">Material Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full border-b border-gray-300 focus:outline-none focus:border-[#189ab4] p-2">
        </div>

        <div>
            <label class="block font-medium mb-1">Price (₱) <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required min="0.01"
                class="w-full border-b border-gray-300 focus:outline-none focus:border-[#189ab4] p-2">
            <small id="priceError" class="error-message text-red-500 hidden">
                Price must be a number greater than 0.
            </small>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-green-500 text-white px-5 py-2 rounded hover:bg-green-600">Add
                Material</button>
            <a href="{{ route('materials.index') }}" class="text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
    const priceField = document.getElementById("price");
    const priceError = document.getElementById("priceError");

    priceField.addEventListener("input", () => {
        const value = parseFloat(priceField.value);
        if (isNaN(value) || value <= 0) {
            priceField.classList.add("border-red-500", "animate-shake");
            priceField.classList.remove("border-green-500");
            priceError.classList.remove("hidden");
        } else {
            priceField.classList.remove("border-red-500", "animate-shake");
            priceField.classList.add("border-green-500");
            priceError.classList.add("hidden");
        }
    });
});
</script>

<style>
    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        20%,
        60% {
            transform: translateX(-6px);
        }

        40%,
        80% {
            transform: translateX(6px);
        }
    }

    .animate-shake {
        animation: shake 0.3s;
    }

    .error-message {
        font-size: 0.8rem;
    }
</style>
@endsection