@extends('layouts.app')

@section('page-title', 'Edit Clinic')

@section('content')

@if ($errors->any())
<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
    <ul>
        @foreach ($errors->all() as $error)
        <li>• {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-6">
    <form action="{{ route('clinics.update', $clinic->clinic_id) }}" method="POST" class="space-y-6"
        id="editClinicForm">
        @csrf
        @method('PUT')

        <div>
            <label for="user_id" class="block font-semibold mb-1">User (Clinic Owner)</label>
            <select name="user_id" id="user_id" class="w-full border rounded p-2" required>
                @foreach (\App\Models\User::where('role', 'clinic')->get() as $user)
                <option value="{{ $user->id }}" {{ $clinic->user_id == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} (ID: {{ $user->id }})
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="name" class="block font-semibold mb-1">Clinic Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $clinic->name) }}"
                class="w-full border rounded p-2" required />
            <p class="hidden text-red-500 text-sm mt-1" id="nameError">Only letters, spaces, dots, and hyphens are
                allowed.</p>
        </div>

        <div>
            <label for="address" class="block font-semibold mb-1">Address</label>
            <textarea name="address" id="address" rows="3" class="w-full border rounded p-2"
                required>{{ old('address', $clinic->address) }}</textarea>
        </div>

        <div>
            <label for="contact_number" class="block font-semibold mb-1">Contact Number</label>
            <input type="text" name="contact_number" id="contact_number"
                value="{{ old('contact_number', $clinic->contact_number) }}" class="w-full border rounded p-2"
                required />
            <p class="hidden text-red-500 text-sm mt-1" id="phoneError">Must be in +63XXXXXXXXXX format (10 digits after
                +63).</p>
        </div>

        <div>
            <label for="email" class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $clinic->email) }}"
                class="w-full border rounded p-2" required />
            <p class="hidden text-red-500 text-sm mt-1" id="emailError">Email must be a valid Gmail address
                (@gmail.com).</p>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                Update Clinic
            </button>
            <a href="{{ route('admin.clinics.index') }}"
                class="px-6 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
            const nameField = document.getElementById("name");
            const phoneField = document.getElementById("contact_number");
            const emailField = document.getElementById("email");

            const nameError = document.getElementById("nameError");
            const phoneError = document.getElementById("phoneError");
            const emailError = document.getElementById("emailError");

            function validateName() {
                const regex = /^[A-Za-z.\-\s]+$/;
                if (!regex.test(nameField.value.trim())) {
                    nameField.classList.add("border-red-500", "animate-shake");
                    nameError.classList.remove("hidden");
                    nameField.classList.remove("border-green-500");
                } else {
                    nameField.classList.remove("border-red-500", "animate-shake");
                    nameError.classList.add("hidden");
                    nameField.classList.add("border-green-500");
                }
            }

            function validatePhone() {
                const regex = /^\+63\d{10}$/;
                if (!regex.test(phoneField.value.trim())) {
                    phoneField.classList.add("border-red-500", "animate-shake");
                    phoneError.classList.remove("hidden");
                    phoneField.classList.remove("border-green-500");
                } else {
                    phoneField.classList.remove("border-red-500", "animate-shake");
                    phoneError.classList.add("hidden");
                    phoneField.classList.add("border-green-500");
                }
            }

            function validateEmail() {
                const regex = /^[A-Za-z0-9._%+-]+@gmail\.com$/;
                if (!regex.test(emailField.value.trim())) {
                    emailField.classList.add("border-red-500", "animate-shake");
                    emailError.classList.remove("hidden");
                    emailField.classList.remove("border-green-500");
                } else {
                    emailField.classList.remove("border-red-500", "animate-shake");
                    emailError.classList.add("hidden");
                    emailField.classList.add("border-green-500");
                }
            }

            nameField.addEventListener("input", validateName);
            phoneField.addEventListener("input", validatePhone);
            emailField.addEventListener("input", validateEmail);
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
</style>

@endsection