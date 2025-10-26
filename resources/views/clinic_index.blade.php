<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Clinic Login - Denture System</title>
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

  <div class="w-full max-w-4xl h-[550px] bg-white rounded-2xl shadow-lg overflow-hidden flex">

    <!-- Left side background -->
    <div class="w-1/2 relative flex flex-col items-center justify-center text-center text-white"
      style="background-image: url('{{ asset('images/bg.jpg') }}'); background-size: cover; background-position: center;">
      <div class="absolute inset-0 bg-black/40"></div>
      <div class="relative z-10 px-6">
        <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="w-32 mx-auto mb-4">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Welcome to Denture System</h2>
        <p class="text-base md:text-lg leading-relaxed">
          A secure portal for clinics to manage appointments, case orders, billing, and deliveries with ease.
        </p>
      </div>
    </div>

    <!-- Right side login form -->
    <div class="w-1/2 p-8 flex flex-col justify-center bg-white">
      <h2 class="text-2xl font-bold text-[#189ab4] mb-6 text-center">Clinic Login</h2>

      <form action="{{ route('login') }}" method="POST" class="space-y-4" novalidate>
        @csrf

        <!-- Email -->
        <div>
          <input type="email" name="email" id="email" placeholder="Enter Email" required
            pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:border-[#189ab4] focus:ring-2 focus:ring-[#189ab4]/30">
          @error('email')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div class="relative">
          <input type="password" name="password" id="password" placeholder="Enter Password" required minlength="8"
            class="w-full border border-gray-300 rounded-md p-3 pr-10 focus:outline-none focus:border-[#189ab4] focus:ring-2 focus:ring-[#189ab4]/30">

          <button type="button" id="togglePassword"
            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-[#189ab4]">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                   9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
          @error('password')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Remember Me / Forgot -->
        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center space-x-2">
            <input type="checkbox" name="remember" class="text-[#189ab4] focus:ring-[#189ab4]">
            <span class="text-gray-700">Remember Me</span>
          </label>
          <a href="#" class="text-[#189ab4] hover:underline">Forgot Password?</a>
        </div>

        <!-- Submit button -->
        <button type="submit"
          class="w-full bg-[#189ab4] text-white rounded-md font-bold py-3 hover:bg-[#127a95] transition">
          Login
        </button>

      </form>

      <!-- Session messages -->
      @if(session('success'))
      <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
      @endif
      @if(session('error'))
      <div class="mt-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
      @endif

      <!-- Signup link -->
      <p class="text-center text-gray-700 text-sm mt-4">
        Don’t have an account?
        <a href="{{ route('clinic.signup') }}" class="text-[#189ab4] hover:underline">Sign up here</a>
      </p>
    </div>
  </div>

  <script>
    // Toggle password visibility
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const eyeIcon = document.getElementById("eyeIcon");

    togglePassword.addEventListener("click", () => {
      const isPassword = passwordInput.getAttribute("type") === "password";
      passwordInput.setAttribute("type", isPassword ? "text" : "password");
      eyeIcon.classList.toggle("text-[#189ab4]");
    });
  </script>
</body>

</html>