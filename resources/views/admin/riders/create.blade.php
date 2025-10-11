@extends('layouts.app')

@section('page-title', 'Rider List')

@section('content')
<div class="mx-[15px] mt-4">

    <!-- Add Rider Button -->
    <button id="openAddRiderModal"
        class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition mb-4">
        + Add Rider
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

    <!-- Riders Table -->
    <div class="overflow-x-auto rounded-xl shadow-lg">
        <table class="min-w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Photo</th>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Contact Number</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riders as $rider)
                <tr class="bg-white hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <img src="{{ $rider->photo ? asset('storage/' . $rider->photo) : asset('images/default-avatar.png') }}"
                            alt="{{ $rider->name }}" class="w-12 h-12 object-cover rounded-full mx-auto">
                    </td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $rider->name }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $rider->email }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $rider->contact_number ?? 'N/A' }}</td>
                    <td class="px-6 py-3 flex gap-2">
                        <button
                            onclick="openEditRiderModal({{ $rider->id }}, '{{ addslashes($rider->name) }}', '{{ $rider->contact_number ?? '' }}', '{{ addslashes($rider->email) }}', '{{ $rider->photo ?? '' }}')"
                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            Edit
                        </button>
                        <form action="{{ route('admin.riders.destroy', $rider->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this rider?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-gray-500 text-center bg-white">No riders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Rider Modal -->
    <div id="addRiderModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
            <button id="closeAddRiderModal"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Add Rider</h2>
            <form action="{{ route('admin.riders.store') }}" method="POST" id="riderFormModal" class="space-y-6">
                @csrf
                <div>
                    <label class="block font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="f_name" id="f_name_modal" value="{{ old('f_name') }}"
                        pattern="^[A-Za-z\s.\-]+$" required
                        class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4] bg-transparent">
                    <small id="nameError_modal" class="text-red-500 text-sm hidden">Name can only contain letters,
                        spaces, dots, and hyphens.</small>
                </div>
                <div>
                    <label class="block font-medium mb-1">Contact Number <span class="text-red-500">*</span></label>
                    <div class="flex">
                        <span
                            class="px-3 py-2 bg-gray-100 border-b border-gray-300 text-gray-600 select-none">+63</span>
                        <input type="text" name="contact_number" id="contact_number_modal"
                            value="{{ old('contact_number') }}" pattern="^[0-9]{10}$" required
                            class="flex-1 border-b border-gray-300 p-2 focus:outline-none bg-transparent"
                            placeholder="9123456789">
                    </div>
                    <small id="contactError_modal" class="text-red-500 text-sm hidden">Must be 10 digits after
                        +63.</small>
                </div>
                <div>
                    <label class="block font-medium mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email_modal" value="{{ old('email') }}"
                        pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$" required
                        class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4] bg-transparent">
                    <small id="emailError_modal" class="text-red-500 text-sm hidden">Email must be a valid Gmail
                        address.</small>
                </div>
                <div class="flex items-center gap-4 mt-4">
                    <button type="submit"
                        class="bg-[#189ab4] text-white px-5 py-2 rounded hover:bg-[#127a95] font-semibold transition">Add
                        Rider</button>
                    <button type="button" id="cancelAddRider" class="text-gray-600 hover:underline">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
    const addModal = document.getElementById('addRiderModal');
    document.getElementById('openAddRiderModal').addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddRiderModal').addEventListener('click', () => addModal.classList.add('hidden'));
    document.getElementById('cancelAddRider').addEventListener('click', () => addModal.classList.add('hidden'));

    // Validation logic for modal inputs
    const fields = {
        f_name_modal: { regex: /^[A-Za-z\s.\-]+$/, errorEl: document.getElementById("nameError_modal") },
        contact_number_modal: { regex: /^[0-9]{10}$/, errorEl: document.getElementById("contactError_modal") },
        email_modal: { regex: /^[a-zA-Z0-9._%+-]+@gmail\.com$/, errorEl: document.getElementById("emailError_modal") }
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

    document.getElementById("riderFormModal").addEventListener("submit", (e) => {
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