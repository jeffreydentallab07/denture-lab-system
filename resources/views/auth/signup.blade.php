<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up - Denture Laboratory Management</title>
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

  <div class="w-full max-w-4xl h-[600px] bg-white rounded-2xl shadow-lg overflow-hidden flex">


    <div class="w-1/2 relative flex flex-col items-center justify-center text-center text-white"
      style="background-image: url('{{ asset('images/bg.jpg') }}'); background-size: cover; background-position: center;">
      <div class="absolute inset-0 bg-black/40"></div>
      <div class="relative z-10 px-6">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-28 mx-auto mb-4">
        <h2 class="text-xl md:text-2xl font-bold mb-3">Join Denture System</h2>
        <p class="text-sm md:text-base leading-relaxed">
          Sign up your laboratory admin account and start managing case orders and clinic transactions.
        </p>
      </div>
    </div>

    <div class="w-1/2 p-6 flex flex-col justify-center bg-white overflow-y-auto">
      <h1 class="text-xl font-bold text-[#189ab4] mb-4 text-center">Create Laboratory Account</h1>


      @if ($errors->any())
      <div class="mb-3 text-red-600 text-sm">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif


      <form id="signupForm" action="{{ url('/signup') }}" method="POST" class="space-y-3">
        @csrf
        <input type="text" name="name" id="name" placeholder="Full Name" value="{{ old('name') }}" required
          class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4]">
        <p id="nameError" class="text-red-600 text-sm mt-1 hidden">Name can only contain letters and spaces.</p>
        <input type="email" name="email" id="email" placeholder="Email" required value="{{ old('email') }}"
          class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4]">
        <p id="emailError" class="text-red-600 text-sm mt-1 hidden"></p>
        <div class="relative">
          <input type="password" name="password" id="password" placeholder="Password" required
            class="w-full border-b border-gray-300 p-2 pr-10 focus:outline-none focus:border-[#189ab4]">
          <button type="button" id="togglePassword"
            class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-[#189ab4]">
            <svg id="eyeIconPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                       9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
          <p id="passwordError" class="text-red-600 text-sm mt-1 hidden">
            Password must be at least 8 characters.
          </p>
        </div>


        <div class="relative">
          <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password"
            required class="w-full border-b border-gray-300 p-2 pr-10 focus:outline-none focus:border-[#189ab4]">
          <button type="button" id="toggleConfirmPassword"
            class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-[#189ab4]">
            <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                       9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
          <p id="confirmError" class="text-red-600 text-sm mt-1 hidden">
            Passwords do not match.
          </p>
        </div>


        <button type="submit"
          class="w-full bg-[#189ab4] text-white font-bold py-2 rounded-md hover:bg-[#127a95] transition">
          Sign Up
        </button>
      </form>

      <p class="text-center text-gray-700 text-sm mt-3">
        Already have an account?
        <a href="{{ route('login') }}" class="text-[#189ab4] hover:underline">Login here</a>
      </p>
    </div>
  </div>

  <script>
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const confirmInput = document.getElementById("password_confirmation");
    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
    const passwordError = document.getElementById("passwordError");
    const confirmError = document.getElementById("confirmError");
    const emailInput = document.getElementById("email");
    const emailError = document.getElementById("emailError");
    const nameInput = document.getElementById("name");
    const nameError = document.getElementById("nameError");
    let emailTimeout;

    togglePassword.addEventListener("click", () => {
      passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    });

   
    toggleConfirmPassword.addEventListener("click", () => {
      confirmInput.type = confirmInput.type === "password" ? "text" : "password";
    });

   
    passwordInput.addEventListener("input", () => {
      passwordError.classList.toggle("hidden", passwordInput.value.length >= 8);
      confirmError.classList.toggle("hidden", passwordInput.value === confirmInput.value || confirmInput.value === "");
    });

    confirmInput.addEventListener("input", () => {
      confirmError.classList.toggle("hidden", passwordInput.value === confirmInput.value);
    });
    emailInput.addEventListener("input", () => {
      clearTimeout(emailTimeout);

      const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/i;
      if (!pattern.test(emailInput.value)) {
        emailError.textContent = "Invalid email format.";
        emailError.classList.remove("hidden");
        return;
      }

      emailTimeout = setTimeout(() => {
        fetch("{{ route('check.email') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({ email: emailInput.value })
        })
        .then(res => res.json())
        .then(data => {
          if (!data.available) {
            emailError.textContent = "This email is already in use.";
            emailError.classList.remove("hidden");
          } else {
            emailError.classList.add("hidden");
          }
        })
        .catch(err => console.error("Error checking email:", err));
      }, 300);
    });


    nameInput.addEventListener("input", () => {
      const pattern = /^[a-zA-Z\s]*$/;
      if (!pattern.test(nameInput.value)) {
        nameError.classList.remove("hidden");
      } else {
        nameError.classList.add("hidden");
      }
    });
  </script>

</body>

</html>