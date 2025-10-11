@extends('layouts.app')

@section('page-title', 'Add Technician')

@section('content')
<main class="flex-1 p-6 overflow-y-auto">

    <!-- Success Toast -->
    @if(session('success'))
    <div id="toast-success"
        class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-gray-700 bg-green-100 rounded-lg shadow-lg z-50 transition-all transform translate-x-0">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M16.707 5.293a1 1 0 010 1.414L8.414 15l-4.121-4.121a1 1 0 111.414-1.414L8.414 12.172l7.293-7.293a1 1 0 011.414 0z"
                clip-rule="evenodd"></path>
        </svg>
        <span class="ml-3 text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Error Toast -->
    @if(session('error'))
    <div id="toast-error"
        class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-gray-700 bg-red-100 rounded-lg shadow-lg z-50 transition-all transform translate-x-0">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.707a1 1 0 00-1.414-1.414L8 10.586 6.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"></path>
        </svg>
        <span class="ml-3 text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <div class="max-w-2xl mx-auto bg-white p-10 border-2 border-gray-300 rounded-md shadow-lg">
        <form action="{{ route('admin.technicians.store') }}" method="POST" class="space-y-6 text-gray-700"
            id="technicianForm" novalidate enctype="multipart/form-data">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" pattern="^[A-Za-z\s.\-]+$"
                    title="Only letters, spaces, dots, and hyphens are allowed" required
                    class="validate-input w-full border-b border-gray-300 focus:outline-none focus:border-blue-500 p-2 bg-transparent">
                <small id="nameError" class="error-message text-red-500 hidden">
                    Name can only contain letters, spaces, dots, and hyphens.
                </small>
            </div>

            <!-- Contact Number -->
            <div>
                <label for="contact_number" class="block text-sm font-medium mb-1">
                    Contact Number <span class="text-red-500">*</span>
                </label>
                <div class="flex">
                    <span class="px-3 py-2 bg-gray-100 border-b border-gray-300 text-gray-600 select-none">+63</span>
                    <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}"
                        pattern="^[0-9]{10}$" title="Enter a valid Philippine number (10 digits after +63)" required
                        class="validate-input flex-1 border-b border-gray-300 focus:outline-none p-2 bg-transparent"
                        placeholder="9123456789" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                </div>
                <small id="contactError" class="error-message text-red-500 hidden">
                    Must be 10 digits after +63 (e.g., 9123456789).
                </small>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block font-medium mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$" title="Only Gmail addresses are allowed" required
                    class="validate-input w-full border-b border-gray-300 focus:outline-none focus:border-blue-500 p-2 bg-transparent">
                <small id="emailError" class="error-message text-red-500 hidden">
                    Email must be a valid Gmail address.
                </small>
            </div>

            <!-- Password -->
            <div class="relative">
                <label for="password" class="block font-medium mb-1">Password <span
                        class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" required minlength="6"
                    class="validate-input w-full border-b border-gray-300 focus:outline-none focus:border-blue-500 p-2 bg-transparent"
                    placeholder="Enter password">
                <button type="button"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-[#189ab4]"
                    onclick="togglePassword('password', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                               9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
                <small id="passwordError" class="error-message text-red-500 hidden">
                    Password is required and must be at least 6 characters.
                </small>
            </div>

            <!-- Confirm Password -->
            <div class="relative">
                <label for="password_confirmation" class="block font-medium mb-1">Confirm Password <span
                        class="text-red-500">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
                    class="validate-input w-full border-b border-gray-300 focus:outline-none focus:border-blue-500 p-2 bg-transparent"
                    placeholder="Confirm password">
                <button type="button"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-[#189ab4]"
                    onclick="togglePassword('password_confirmation', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                               9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
                <small id="passwordConfirmError" class="error-message text-red-500 hidden">
                    Passwords must match.
                </small>
            </div>

            <!-- Photo Upload -->
            <div>
                <label for="photo" class="block font-medium mb-1">Profile Photo</label>
                <input type="file" id="photo" name="photo" accept="image/*"
                    class="w-full border-b border-gray-300 focus:outline-none focus:border-blue-500 p-2 bg-transparent">
                <small id="photoError" class="error-message text-red-500 hidden">
                    Please upload a valid image file.
                </small>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 mt-6 justify-center">
                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                    Save
                </button>
                <a href="{{ route('admin.technicians.index') }}"
                    class="px-6 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    &larr; Back
                </a>
            </div>
        </form>
    </div>
</main>

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
        // Check password match
        const pass = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const confirmError = document.getElementById('passwordConfirmError');
        if(pass !== confirm){
            confirmError.classList.remove('hidden');
            valid = false;
        } else {
            confirmError.classList.add('hidden');
        }
        if (!valid) e.preventDefault();
    });

    // Auto-hide toasts
    ['toast-success', 'toast-error'].forEach(id => {
        const toast = document.getElementById(id);
        if(toast){
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-5');
                setTimeout(() => toast.remove(), 500); 
            }, 3000);
        }
    });
});

// Eye toggle function
function togglePassword(fieldId, button){
    const input = document.getElementById(fieldId);
    const svg = button.querySelector('svg');

    if(input.type === 'password'){
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
</script>

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
@endsection