<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome - Denture Laboratory Management</title>
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

  <div class="w-full max-w-4xl h-[550px] bg-white rounded-2xl shadow-lg overflow-hidden flex">


    <div class="w-1/2 relative flex flex-col items-center justify-center text-center text-white"
      style="background-image: url('images/bg.jpg'); background-size: cover; background-position: center;">
      <div class="absolute inset-0 bg-black/40"></div>
      <div class="relative z-10 px-6">
        <img src="images/logo2.png" alt="Logo" class="w-40 mx-auto mb-4">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Jeffrey Dental Laboratory</h2>
        <p class="text-base md:text-lg leading-relaxed">
          A modern system designed for dental clinics and laboratories to manage appointments,
          case orders, schedules, deliveries, and billing all in one place.
        </p>
      </div>
    </div>


    <div class="w-1/2 p-8 flex flex-col justify-center bg-white">
      <h2 class="text-2xl font-bold text-[#189ab4] mb-6 text-center">Login</h2>

      <form action="{{ url('/login') }}" method="POST" class="space-y-4" novalidate>
        @csrf


        <div>
          <input type="email" name="email" id="email" placeholder="Enter Email" required
            pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:border-[#189ab4] focus:ring-2 focus:ring-[#189ab4]/30">
          <p id="emailError" class="text-red-600 text-sm mt-1 hidden">
            Please enter a valid email (must contain @ and domain, no spaces, lowercase only).
          </p>
        </div>

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
        </div>
        <p id="passwordError" class="text-red-600 text-sm mt-1 hidden">
          Password must be at least 8 characters long.
        </p>


        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center space-x-2">
            <input type="checkbox" name="remember" class="text-[#189ab4] focus:ring-[#189ab4]">
            <span class="text-gray-700">Remember Me</span>
          </label>
          <a href="#" class="text-[#189ab4] hover:underline">Forgot Password?</a>
        </div>


        <button type="submit"
          class="w-full bg-[#189ab4] text-white rounded-md font-bold py-3 hover:bg-[#127a95] transition">
          Login
        </button>
      </form>

      @if(session('success'))
      <div id="toast-success"
        class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-gray-700 bg-green-100 rounded-lg shadow-lg z-50 opacity-100 transition-opacity duration-500"
        role="alert">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd"
            d="M16.707 5.293a1 1 0 010 1.414L8.414 15l-4.121-4.121a1 1 0 111.414-1.414L8.414 12.172l7.293-7.293a1 1 0 011.414 0z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="ml-3 text-sm font-medium">{{ session('success') }}</span>
      </div>
      @endif

      @if ($errors->any())
      <div class="mt-4 text-red-600 text-sm">
        {{ $errors->first() }}
      </div>
      @endif

    </div>
  </div>


  <script>
    const passwordInput = document.getElementById("password");
    const passwordError = document.getElementById("passwordError");

    passwordInput.addEventListener("input", () => {
      if (passwordInput.value.length > 0 && passwordInput.value.length < 8) {
        passwordError.classList.remove("hidden");
      } else {
        passwordError.classList.add("hidden");
      }
    });

   
    const emailInput = document.getElementById("email");
    const emailError = document.getElementById("emailError");

    emailInput.addEventListener("input", () => {
      if (emailInput.validity.patternMismatch || emailInput.validity.typeMismatch) {
        emailError.classList.remove("hidden");
      } else {
        emailError.classList.add("hidden");
      }
    });

   
    const togglePassword = document.getElementById("togglePassword");
    const eyeIcon = document.getElementById("eyeIcon");

    togglePassword.addEventListener("click", () => {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);

    
      eyeIcon.classList.toggle("text-[#189ab4]");
    });
    setTimeout(() => {
    const toast = document.getElementById("toast-success");
    if (toast) {
      toast.classList.add("opacity-0"); 
      setTimeout(() => toast.remove(), 500); 
    }
  }, 3000);
  </script>
  </script>

</body>

</html>