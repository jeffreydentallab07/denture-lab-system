@extends($layout)

@section('title', 'Settings')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Settings</h1>
            <p class="text-gray-600">Manage your account settings and preferences</p>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center">
                        <div class="mb-4">
                            <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-avatar.png') }}"
                                alt="{{ Auth::user()->name }}"
                                class="w-32 h-32 rounded-full object-cover mx-auto border-4 border-blue-100">
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                        <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                        <span
                            class="inline-block px-3 py-1 mt-2 text-xs rounded-full font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Settings Forms -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Update Profile -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-bold text-gray-800">Profile Information</h3>
                        <p class="text-sm text-gray-600">Update your account profile information</p>
                    </div>
                    <form action="{{ route('settings.updateProfile') }}" method="POST" enctype="multipart/form-data"
                        class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                            @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                            @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Number
                            </label>
                            <input type="text" name="contact_number"
                                value="{{ old('contact_number', Auth::user()->contact_number) }}"
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"
                                placeholder="+63 XXX XXX XXXX">
                            @error('contact_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profile Photo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Profile Photo
                            </label>
                            <input type="file" name="photo" accept="image/*"
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                            <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG. Max size: 2MB</p>
                            @error('photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end pt-4 border-t">
                            <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-bold text-gray-800">Change Password</h3>
                        <p class="text-sm text-gray-600">Update your password to keep your account secure</p>
                    </div>
                    <form action="{{ route('settings.updatePassword') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Current Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="current_password" required
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                            @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="new_password" required
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                            @error('new_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="new_password_confirmation" required
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-yellow-800">Security Notice</h4>
                                    <p class="text-xs text-yellow-700 mt-1">
                                        Changing your password will log you out. You'll need to sign in again with your
                                        new password.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t">
                            <button type="submit"
                                class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Account Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Account Information</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Account Type:</span>
                            <span class="font-semibold text-gray-800">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Account Created:</span>
                            <span class="font-semibold text-gray-800">{{ Auth::user()->created_at->format('M d, Y')
                                }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated:</span>
                            <span class="font-semibold text-gray-800">{{ Auth::user()->updated_at->format('M d, Y')
                                }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection