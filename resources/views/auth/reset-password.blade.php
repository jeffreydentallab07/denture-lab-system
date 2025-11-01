<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Jeffrey Dental Laboratory</title>
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-teal-50">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="w-24 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-[#189ab4] mb-2">Reset Password</h1>
            <p class="text-gray-600">Enter your new password below.</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    @foreach($errors->all() as $error)
                    <p class="text-red-700 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Reset Password Form -->
        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Hidden Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', request()->email) }}"
                    placeholder="Enter your email address" required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-[#189ab4] focus:ring-2 focus:ring-[#189ab4]/30">
                @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        placeholder="Enter new password (min. 8 characters)" required minlength="8"
                        class="w-full border border-gray-300 rounded-lg p-3 pr-12 focus:outline-none focus:border-[#189ab4] focus:ring-2 focus:ring-[#189ab4]/30">
                    <button type="button" onclick="togglePassword('password', 'eyeIcon1')"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-[#189ab4]">
                        <svg id="eyeIcon1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm New
                    Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="Re-enter new password" required minlength="8"
                        class="w-full border border-gray-300 rounded-lg p-3 pr-12 focus:outline-none focus:border-[#189ab4] focus:ring-2 focus:ring-[#189ab4]/30">
                    <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-[#189ab4]">
                        <svg id="eyeIcon2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Password Requirements:</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        At least 8 characters long
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Passwords must match
                    </li>
                </ul>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-[#189ab4] text-white rounded-lg font-bold py-3 hover:bg-[#127a95] transition shadow-lg">
                Reset Password
            </button>

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-[#189ab4] hover:underline text-sm font-medium">
                    ← Back to Login
                </a>
            </div>
        </form>

    </div>

    <!-- JavaScript -->
    <script>
        function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      
      if (input.type === "password") {
        input.type = "text";
        icon.classList.add("text-[#189ab4]");
      } else {
        input.type = "password";
        icon.classList.remove("text-[#189ab4]");
      }
    }

    // Real-time password match validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');

    confirmPassword.addEventListener('input', () => {
      if (confirmPassword.value !== password.value) {
        confirmPassword.setCustomValidity('Passwords do not match');
      } else {
        confirmPassword.setCustomValidity('');
      }
    });
    </script>

</body>

</html>