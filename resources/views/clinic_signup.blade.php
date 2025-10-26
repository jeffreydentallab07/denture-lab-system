<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Clinic Sign Up - Denture System</title>
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

  <div class="w-full max-w-4xl h-[600px] bg-white rounded-2xl shadow-lg overflow-hidden flex">

    <!-- LEFT SIDE -->
    <div class="w-1/2 relative flex flex-col items-center justify-center text-center text-white"
      style="background-image: url('{{ asset('images/bg.jpg') }}'); background-size: cover; background-position: center;">
      <div class="absolute inset-0 bg-black/40"></div>
      <div class="relative z-10 px-6">
        <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="w-28 mx-auto mb-4">
        <h2 class="text-xl md:text-2xl font-bold mb-3">Join Denture System</h2>
        <p class="text-sm md:text-base leading-relaxed">
          Register your clinic to manage appointments, case orders, billing, and deliveries.
        </p>
      </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="w-1/2 p-6 flex flex-col justify-center bg-white overflow-y-auto">
      <h2 class="text-xl font-bold text-[#189ab4] mb-4 text-center">Create Clinic Account</h2>

      <!-- Validation Errors -->
      @if ($errors->any())
      <div class="mb-3 text-red-600 text-sm">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      @if(session('success'))
      <div class="mb-3 text-green-600 text-sm text-center">
        {{ session('success') }}
      </div>
      @endif

      <!-- FORM -->
      <form id="clinicSignupForm" action="{{ route('clinic.signup') }}" method="POST" class="space-y-3">
        @csrf

        <!-- Clinic Name -->
        <input type="text" name="clinic_name" id="clinic_name" placeholder="Clinic Name"
          value="{{ old('clinic_name') }}" required
          class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4]">
        <p id="clinicNameError" class="text-red-600 text-sm mt-1 hidden">Clinic name can only contain letters, numbers,
          and spaces.</p>

        <!-- Owner Name -->
        <input type="text" name="owner_name" id="owner_name" placeholder="Owner Name" value="{{ old('owner_name') }}"
          required class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4]">
        <p id="ownerNameError" class="text-red-600 text-sm mt-1 hidden">Owner name can only contain letters and spaces.
        </p>

        <!-- Address -->
        <input type="text" name="address" placeholder="Address (optional)" value="{{ old('address') }}"
          class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4]">

        <!-- Contact Number -->
        <input type="text" name="contact_number" placeholder="Contact Number (optional)"
          value="{{ old('contact_number') }}"
          class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4]">

        <!-- Email -->
        <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required
          class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-[#189ab4]">
        <p id="emailError" class="text-red-600 text-sm mt-1 hidden"></p>

        <!-- Password -->
        <div class="relative">
          <input type="password" name="password" id="password" placeholder="Password" required
            class="w-full border-b border-gray-300 p-2 pr-10 focus:outline-none focus:border-[#189ab4]">
          <button type="button" id="togglePassword"
            class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-[#189ab4]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                       9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
          <p id="passwordError" class="text-red-600 text-sm mt-1 hidden">Password must be at least 8 characters.</p>
        </div>

        <!-- Confirm Password -->
        <div class="relative">
          <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password"
            required class="w-full border-b border-gray-300 p-2 pr-10 focus:outline-none focus:border-[#189ab4]">
          <button type="button" id="toggleConfirmPassword"
            class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-[#189ab4]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                       9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
          <p id="confirmError" class="text-red-600 text-sm mt-1 hidden">Passwords do not match.</p>
        </div>

        <!-- Submit -->
        <button type="submit"
          class="w-full bg-[#189ab4] text-white rounded-md font-bold py-2 hover:bg-[#127a95] transition">
          Create Account
        </button>
      </form>

      <p class="text-center text-gray-700 text-sm mt-3">
        Already have an account?
        <a href="{{ route('clinic_index') }}" class="text-[#189ab4] hover:underline">Login here</a>
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
    const clinicNameInput = document.getElementById("clinic_name");
    const clinicNameError = document.getElementById("clinicNameError");
    const ownerNameInput = document.getElementById("owner_name");
    const ownerNameError = document.getElementById("ownerNameError");
    let emailTimeout;

    // Password toggle
    togglePassword.addEventListener("click", () => {
      passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    });

    toggleConfirmPassword.addEventListener("click", () => {
      confirmInput.type = confirmInput.type === "password" ? "text" : "password";
    });

    // Password validation
    passwordInput.addEventListener("input", () => {
      passwordError.classList.toggle("hidden", passwordInput.value.length >= 8);
      confirmError.classList.toggle("hidden", passwordInput.value === confirmInput.value || confirmInput.value === "");
    });

    confirmInput.addEventListener("input", () => {
      confirmError.classList.toggle("hidden", passwordInput.value === confirmInput.value);
    });

    // Real-time email check
    emailInput.addEventListener("input", () => {
      clearTimeout(emailTimeout);
      const pattern = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/i;
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

    // Clinic name validation
    clinicNameInput.addEventListener("input", () => {
      const pattern = /^[a-zA-Z0-9\\s]*$/;
      clinicNameError.classList.toggle("hidden", pattern.test(clinicNameInput.value));
    });

    // Owner name validation
    ownerNameInput.addEventListener("input", () => {
      const pattern = /^[a-zA-Z\\s]*$/;
      ownerNameError.classList.toggle("hidden", pattern.test(ownerNameInput.value));
    });
  </script>

</body>

</html>