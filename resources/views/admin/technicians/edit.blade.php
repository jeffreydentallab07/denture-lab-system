@extends('layouts.app')

@section('page-title', 'Edit Technician')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Technician</h1>

    <form action="{{ route('technicians.update', $technician->technician_id) }}" method="POST" class="space-y-4"
        id="technicianForm" novalidate>
        @csrf
        @method('PUT')


        <div>
            <label for="name" class="block font-medium">Full Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $technician->name) }}"
                pattern="^[A-Za-z\s.\-]+$" title="Only letters, spaces, dots, and hyphens are allowed"
                class="validate-input w-full border rounded p-2 @error('name') border-red-500 @enderror">
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <small id="nameError" class="error-message text-red-500 hidden">Name can only contain letters, spaces, dots,
                and hyphens.</small>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Contact Number <span class="text-red-500">*</span></label>
            <div class="flex">
                <span class="px-3 py-2 bg-gray-100 border-b border-gray-300 text-gray-600 select-none">+63</span>
                <input type="text" id="contact_number" name="contact_number"
                    value="{{ old('contact_number', $technician->contact_number) }}" required pattern="^[0-9]{10}$"
                    title="Enter a valid Philippine number (10 digits after +63)"
                    class="validate-input flex-1 border-b border-gray-300 focus:outline-none focus:border-blue-500 p-2 bg-transparent"
                    placeholder="9123456789">
            </div>
            @error('contact_number')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <small id="contactError" class="error-message text-red-500 hidden">Must be 10 digits after +63 (e.g.,
                9123456789).</small>
        </div>

        <div>
            <label for="email" class="block font-medium">Email <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email', $technician->email) }}"
                pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$" title="Only Gmail addresses are allowed"
                class="validate-input w-full border rounded p-2 @error('email') border-red-500 @enderror">
            @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <small id="emailError" class="error-message text-red-500 hidden">Email must be a valid Gmail
                address.</small>
        </div>



        <div class="flex gap-3 mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
            <a href="{{ route('admin.technicians.index') }}"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">← Back</a>
        </div>
    </form>
</div>

<style>
    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25%,
        75% {
            transform: translateX(-4px);
        }

        50% {
            transform: translateX(4px);
        }
    }

    input.invalid {
        border-color: red !important;
        animation: shake 0.3s;
    }

    input.valid {
        border-color: green !important;
    }

    .error-message {
        font-size: 0.8rem;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("technicianForm");
    const inputs = document.querySelectorAll(".validate-input");

    inputs.forEach(input => {
        const errorEl = document.getElementById(input.id + "Error");

        input.addEventListener("input", () => {
            if (!input.checkValidity()) {
                input.classList.add("invalid");
                input.classList.remove("valid");
                if (errorEl) errorEl.classList.remove("hidden");
            } else {
                input.classList.remove("invalid");
                input.classList.add("valid");
                if (errorEl) errorEl.classList.add("hidden");
            }
        });
    });

    form.addEventListener("submit", (e) => {
        let valid = true;
        inputs.forEach(input => {
            const errorEl = document.getElementById(input.id + "Error");
            if (!input.checkValidity()) {
                input.classList.add("invalid");
                input.classList.remove("valid");
                if (errorEl) errorEl.classList.remove("hidden");
                valid = false;
            }
        });
        if (!valid) e.preventDefault();
    });
});
</script>
@endsection