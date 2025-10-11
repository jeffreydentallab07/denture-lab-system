@extends('layouts.clinic')


@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-md rounded-2xl shadow-lg p-6 max-w-3xl mx-auto">

    <h2 class="text-2xl font-bold text-[#05445e] dark:text-[#83c5be] mb-4">Clinic Settings</h2>
    <p class="text-gray-600 dark:text-gray-400 mb-6">Update your clinic preferences and account information here.</p>


    <form action="{{ route('clinic.settings.update') }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')


        <div>
            <label for="clinic_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Clinic
                Name</label>
            <input type="text" name="clinic_name" id="clinic_name"
                value="{{ old('clinic_name', auth()->user()->clinic_name ?? '') }}"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#189ab4]">
        </div>


        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#189ab4]">
        </div>


        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">New
                Password</label>
            <input type="password" name="password" id="password"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#189ab4]">
            <small class="text-gray-500 dark:text-gray-400">Leave blank if you don’t want to change your
                password.</small>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2 bg-[#189ab4] text-white font-semibold rounded-lg shadow-md hover:bg-[#05445e] transition">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('darkModeToggle')?.addEventListener('change', function() {
        localStorage.setItem('darkMode', this.checked);
        location.reload();
    });
</script>
@endsection