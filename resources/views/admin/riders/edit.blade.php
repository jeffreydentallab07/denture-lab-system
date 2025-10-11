@extends('layouts.app')

@section('page-title', 'Edit Rider')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
    <form action="{{ route('riders.update', $rider->rider_id) }}" method="POST" id="riderForm" class="space-y-6">
        @csrf
        @method('PUT')


        <div>
            <label class="block font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="f_name" id="f_name" value="{{ old('f_name', $rider->f_name) }}"
                pattern="^[A-Za-z\s.\-]+$" required
                class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4] bg-transparent">
            <small id="nameError" class="text-red-500 text-sm hidden">Name can only contain letters, spaces, dots, and
                hyphens.</small>
        </div>


        <div>
            <label class="block font-medium mb-1">Contact Number <span class="text-red-500">*</span></label>
            <div class="flex">
                <span class="px-3 py-2 bg-gray-100 border-b border-gray-300 text-gray-600 select-none">+63</span>
                <input type="text" name="contact_number" id="contact_number"
                    value="{{ old('contact_number', $rider->contact_number) }}" pattern="^[0-9]{10}$" required
                    class="flex-1 border-b border-gray-300 p-2 focus:outline-none bg-transparent"
                    placeholder="9123456789">
            </div>
            <small id="contactError" class="text-red-500 text-sm hidden">Must be 10 digits after +63.</small>
        </div>

        <div>
            <label class="block font-medium mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email', $rider->email) }}"
                pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$" required
                class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4] bg-transparent">
            <small id="emailError" class="text-red-500 text-sm hidden">Email must be a valid Gmail address.</small>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="bg-[#189ab4] text-white px-5 py-2 rounded hover:bg-[#127a95] font-semibold transition">Update
                Rider</button>
            <a href="{{ route('admin.riders.index') }}" class="text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("riderForm");
    const fields = {
        f_name: { regex: /^[A-Za-z\s.\-]+$/, errorEl: document.getElementById("nameError") },
        contact_number: { regex: /^[0-9]{10}$/, errorEl: document.getElementById("contactError") },
        email: { regex: /^[a-zA-Z0-9._%+-]+@gmail\.com$/, errorEl: document.getElementById("emailError") }
    };

    Object.keys(fields).forEach(id => {
        const input = document.getElementById(id);
        const { regex, errorEl } = fields[id];
        input.addEventListener("input", () => {
            if (!regex.test(input.value.trim())) {
                input.classList.add("border-red-500","animate-shake");
                input.classList.remove("border-green-500");
                errorEl.classList.remove("hidden");
            } else {
                input.classList.remove("border-red-500","animate-shake");
                input.classList.add("border-green-500");
                errorEl.classList.add("hidden");
            }
        });
    });

    form.addEventListener("submit", (e) => {
        let valid = true;
        Object.keys(fields).forEach(id => {
            const input = document.getElementById(id);
            const { regex, errorEl } = fields[id];
            if (!regex.test(input.value.trim())) {
                input.classList.add("border-red-500","animate-shake");
                errorEl.classList.remove("hidden");
                valid = false;
            }
        });
        if (!valid) e.preventDefault();
    });
});
</script>
<style>
    .animate-shake {
        animation: shake 0.3s;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-4px);
        }

        50% {
            transform: translateX(4px);
        }

        75% {
            transform: translateX(-4px);
        }
    }
</style>
@endsection