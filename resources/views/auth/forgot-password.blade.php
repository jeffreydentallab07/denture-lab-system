<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Jeffrey Dental Laboratory</title>
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-teal-50">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="w-24 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-[#189ab4] mb-2">Forgot Password?</h1>
            <p class="text-gray-600">Enter your email address and we'll send you a link to reset your password.</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-green-700 text-sm">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-red-700 text-sm">{{ $errors->first() }}</p>
            </div>
        </div>
        @endif

        <!-- Forgot Password Form -->
        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    placeholder="Enter your email address" required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[#189ab4] focus:ring-2 focus:ring-[#189ab4]/30">
                @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-[#189ab4] text-white rounded-lg font-bold py-3 hover:bg-[#127a95] transition shadow-lg">
                Send Reset Link
            </button>

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-[#189ab4] hover:underline text-sm font-medium">
                    ← Back to Login
                </a>
            </div>
        </form>

        <!-- Additional Info -->
        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>Note:</strong> The password reset link will expire in 60 minutes. If you don't receive an email,
                please check your spam folder.
            </p>
        </div>

    </div>

</body>

</html>