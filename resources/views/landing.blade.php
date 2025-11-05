<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <title>Denture Laboratory System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <style>
    @keyframes slideIn {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .slide-in {
      animation: slideIn 1s ease forwards;
    }

    .shake {
      animation: shake 0.3s;
    }

    @keyframes shake {

      0%,
      100% {
        transform: translateX(0);
      }

      25%,
      75% {
        transform: translateX(-5px);
      }

      50% {
        transform: translateX(5px);
      }
    }
  </style>
</head>

<body class="relative">

  <section class="relative w-full min-h-screen bg-cover bg-center"
    style="background-image: url('{{ asset('images/bg.jpg') }}');">
    <div class="absolute inset-0 bg-teal-900/60"></div>

    <header class="absolute top-0 left-0 w-full z-20 px-8 py-6 flex items-center justify-between">
      <a href="#home" class="flex items-center space-x-2">
        <img src="{{ asset('images/logo2.png') }}" alt="Dental Lab Logo" class="h-11 w-auto">
      </a>

      <nav class="hidden md:flex space-x-6 items-center text-white">
        <a href="#services" class="text-gray-100 hover:text-blue-900 font-medium transition">Our Services</a>
        <a href="#about" class="text-gray-100 hover:text-blue-900 font-medium transition">About Us</a>
        <a href="#contact" class="text-gray-100 hover:text-blue-900 font-medium transition">Contact Us</a>
        <a id="loginBtn" href="#"
          class="text-gray-100 hover:bg-blue-900 hover:text-white px-4 py-2 rounded-lg font-medium transition">
          Login
        </a>
      </nav>

      <!-- Laboratory Login Modal -->
      <div id="labLoginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-lg overflow-hidden relative">
          <button id="closeLabLoginModal" class="absolute top-4 right-4 text-gray-500 hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <div class="flex flex-col md:flex-row h-[550px]">
            <div class="w-full md:w-1/2 relative flex flex-col items-center justify-center text-center text-white"
              style="background-image: url('{{ asset('images/bg.jpg') }}'); background-size: cover; background-position: center;">
              <div class="absolute inset-0 bg-black/40"></div>
              <div class="relative z-10 px-6">
                <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="w-32 mx-auto mb-4">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Jeffrey Dental Laboratory</h2>
                <p class="text-sm md:text-base leading-relaxed">
                  A modern system for dental clinics and laboratories to manage appointments,
                  case orders, schedules, deliveries, and billing all in one place.
                </p>
              </div>
            </div>

            <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col justify-center bg-white overflow-y-auto">
              <h2 class="text-2xl font-bold text-[#189ab4] mb-6 text-center">Laboratory Login</h2>

              @if ($errors->any())
              <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-600 text-sm">{{ $errors->first() }}</p>
              </div>
              @endif

              <form action="{{ route('login') }}" method="POST" id="labLoginForm" class="space-y-4">
                @csrf
                <div>
                  <input type="email" name="email" id="labEmail" placeholder="Enter Email" required
                    pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                    class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:border-[#189ab4] focus:ring-2 focus:ring-[#189ab4]/30">
                  <p id="labEmailError" class="text-red-600 text-sm mt-1 hidden">
                    Please enter a valid email address.
                  </p>
                </div>

                <div class="relative">
                  <input type="password" name="password" id="labPassword" placeholder="Enter Password" required
                    minlength="6"
                    class="w-full border border-gray-300 rounded-md p-3 pr-10 focus:outline-none focus:border-[#189ab4] focus:ring-2 focus:ring-[#189ab4]/30">
                  <button type="button" id="toggleLabPassword"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-[#189ab4]">
                    <svg id="labEyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor" class="w-5 h-5">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 
                     9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                  <p id="labPasswordError" class="text-red-600 text-sm mt-1 hidden">
                    Password must be at least 6 characters long.
                  </p>
                </div>

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
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12 md:py-24 slide-in">
      <div class="bg-white rounded-2xl shadow-xl md:flex md:items-center md:justify-between overflow-hidden">
        <div class="p-8 md:w-1/2 space-y-6">
          <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Imong Ngisi, Our Masterpiece</h1>
          <p class="text-gray-700 text-lg md:text-xl">Your trusted partner in creating perfect smiles</p>
        </div>

        <div class="p-8 md:w-1/2 flex flex-col justify-between space-y-6">
          <div class="relative w-full">
            <input type="text" id="search" placeholder=" "
              class="peer block w-full rounded-full bg-gray-100 px-4 pt-5 pb-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500" />
            <label for="search" class="absolute left-4 top-2 text-gray-500 text-sm transition-all 
                peer-placeholder-shown:top-5 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base 
                peer-focus:top-2 peer-focus:text-teal-500 peer-focus:text-sm">
              Search for Dental services...
            </label>
            <svg xmlns="http://www.w3.org/2000/svg"
              class="absolute right-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

          <button id="openSignupModal" class="bg-blue-900 text-white px-20 py-2.5 rounded-lg font-semibold shadow-md 
                       transform transition duration-300 ease-in-out 
                       hover:bg-green-600 hover:text-white 
                       hover:scale-105 hover:-translate-y-2 hover:shadow-xl hover:shadow-green-400/50 text-center">
            Register Clinic
          </button>
        </div>
      </div>
    </div>

    <!-- Clinic Signup Modal -->
    <div id="signupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
      <div
        class="bg-white w-full max-w-2xl rounded-2xl shadow-lg overflow-hidden relative max-h-[90vh] overflow-y-auto">
        <button id="closeSignupModal" class="absolute top-4 right-4 text-gray-500 hover:text-red-500 z-10">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <div class="flex flex-col md:flex-row">
          <div class="w-full md:w-1/2 relative flex flex-col items-center justify-center text-center text-white"
            style="background-image: url('{{ asset('images/bg.jpg') }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 px-4 py-8">
              <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="w-24 mx-auto mb-3">
              <h2 class="text-lg md:text-xl font-bold mb-2">Join Denture System</h2>
              <p class="text-xs md:text-sm leading-relaxed">
                Register your clinic to manage appointments, case orders, billing, and deliveries.
              </p>
            </div>
          </div>

          <div class="w-full md:w-1/2 p-4 flex flex-col justify-center bg-white">
            <h2 class="text-lg font-bold text-[#189ab4] mb-3 text-center">Clinic Registration</h2>

            @if ($errors->any())
            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg">
              <ul class="text-red-600 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif

            @if (session('success'))
            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
              <p class="text-green-600 text-sm">{{ session('success') }}</p>
            </div>
            @endif

            <form id="clinicSignupForm" action="{{ route('clinic.signup.post') }}" method="POST" class="space-y-2">
              @csrf

              <div>
                <input type="text" name="username" id="username" placeholder="Username *" required
                  value="{{ old('username') }}"
                  class="w-full border-b border-gray-300 p-1.5 focus:outline-none focus:border-[#189ab4]">
                <small class="text-red-500 text-xs hidden" id="usernameError">Username is required.</small>
              </div>

              <div>
                <input type="text" name="clinic_name" id="clinic_name" placeholder="Clinic Name *" required
                  value="{{ old('clinic_name') }}"
                  class="w-full border-b border-gray-300 p-1.5 focus:outline-none focus:border-[#189ab4]">
                <small class="text-red-500 text-xs hidden" id="clinicNameError">Clinic name is required.</small>
              </div>

              <div>
                <input type="text" name="address" id="address" placeholder="Address (optional)"
                  value="{{ old('address') }}"
                  class="w-full border-b border-gray-300 p-1.5 focus:outline-none focus:border-[#189ab4]">
              </div>

              <div>
                <input type="text" name="contact_number" id="contact_number" placeholder="Contact Number (optional)"
                  value="{{ old('contact_number') }}"
                  class="w-full border-b border-gray-300 p-1.5 focus:outline-none focus:border-[#189ab4]">
              </div>

              <div>
                <input type="email" name="email" id="signup_email" placeholder="Email *" required
                  value="{{ old('email') }}"
                  class="w-full border-b border-gray-300 p-1.5 focus:outline-none focus:border-[#189ab4]">
                <small class="text-red-500 text-xs hidden" id="emailError">Please enter a valid email.</small>
              </div>

              <div>
                <input type="password" name="password" id="signup_password" placeholder="Password *" required
                  minlength="6" class="w-full border-b border-gray-300 p-1.5 focus:outline-none focus:border-[#189ab4]">
                <small class="text-red-500 text-xs hidden" id="passwordError">Password must be at least 6
                  characters.</small>
              </div>

              <div>
                <input type="password" name="password_confirmation" id="password_confirmation"
                  placeholder="Confirm Password *" required minlength="6"
                  class="w-full border-b border-gray-300 p-1.5 focus:outline-none focus:border-[#189ab4]">
                <small class="text-red-500 text-xs hidden" id="confirmPasswordError">Passwords do not match.</small>
              </div>

              <button type="submit"
                class="w-full bg-[#189ab4] text-white rounded-md font-bold py-2 hover:bg-[#127a95] transition mt-4">
                Create Account
              </button>
            </form>

            <p class="text-center text-gray-700 text-xs mt-2">
              Already have an account?
              <a href="#" id="openLoginFromSignup" class="text-[#189ab4] hover:underline">Login here</a>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Clinic Login Modal -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
      <div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg overflow-hidden relative">
        <button id="closeLoginModal" class="absolute top-4 right-4 text-gray-500 hover:text-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <div class="flex flex-col md:flex-row">
          <div class="w-full md:w-1/2 relative flex flex-col items-center justify-center text-center text-white"
            style="background-image: url('{{ asset('images/bg.jpg') }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 px-4 py-8">
              <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="w-24 mx-auto mb-3">
              <h2 class="text-lg md:text-xl font-bold mb-2">Welcome Back</h2>
              <p class="text-xs md:text-sm leading-relaxed">
                Log in to manage your clinic's appointments, case orders, billing, and deliveries.
              </p>
            </div>
          </div>

          <div class="w-full md:w-1/2 p-4 flex flex-col justify-center bg-white overflow-y-auto">
            <h2 class="text-lg font-bold text-[#189ab4] mb-3 text-center">Clinic Login</h2>

            @if ($errors->any())
            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg">
              <p class="text-red-600 text-sm">{{ $errors->first() }}</p>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-2">
              @csrf
              <input type="email" name="email" placeholder="Enter Email" required
                class="w-full border-b border-gray-300 p-1.5 focus:outline-none focus:border-[#189ab4]">
              <input type="password" name="password" placeholder="Enter Password" required
                class="w-full border-b border-gray-300 p-1.5 focus:outline-none focus:border-[#189ab4]">
              <button type="submit"
                class="w-full bg-[#189ab4] text-white rounded-md font-bold py-2 hover:bg-[#127a95] transition mt-4">
                Login
              </button>
            </form>

            <p class="text-center text-gray-700 text-xs mt-2">
              Don't have an account?
              <a href="#" id="openSignupFromLogin" class="text-[#189ab4] hover:underline">Sign up here</a>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Section -->
    <section id="contact" class="py-20 flex flex-col justify-center items-center bg-gray-200 px-6 relative">
      <h2 class="text-3xl font-bold text-blue-900 mb-6">Contact Us</h2>

      <div class="flex justify-center w-full">
        <div class="grid md:grid-cols-3 gap-8 w-full max-w-5xl">
          <div
            class="bg-white rounded-3xl shadow-2xl p-6 text-center transition-transform duration-300 hover:-translate-y-3">
            <h3 class="text-xl font-semibold text-blue-900 text-9 mb-4">Phone Number</h3>
            <div class="flex justify-center items-center space-x-3">
              <div class="flex flex-col items-center space-y-4">
                <a href="tel:+639067732353" class="flex items-center justify-center bg-gray-100 rounded-xl shadow-md w-40 h-12 
          hover:bg-gradient-to-r hover:from-red-500 hover:to-blue-500 hover:scale-105 transition">
                  <img src="{{ asset('images/tml.png') }}" alt="TM" class="w-6 h-6 mr-3">
                  <span class="text-gray-700 font-medium hover:text-white transition">0906 773 2353</span>
                </a>
                <a href="tel:0987654321" class="flex items-center justify-center bg-gray-100 rounded-xl shadow-md w-40 h-12 
          hover:bg-green-500 hover:scale-105 transition">
                  <img src="{{ asset('images/smart.png') }}" alt="Smart" class="w-6 h-6 mr-3">
                  <span class="text-gray-700 font-medium hover:text-white transition">09 8765 4321</span>
                </a>
                <a href="tel:0911223344" class="flex items-center justify-center bg-gray-100 rounded-xl shadow-md w-40 h-12 
          hover:bg-red-500 hover:scale-105 transition">
                  <img src="{{ asset('images/dito.png') }}" alt="DITO" class="w-6 h-6 mr-3">
                  <span class="text-gray-700 font-medium hover:text-white transition">09 1122 3344</span>
                </a>
              </div>
            </div>
          </div>

          <div
            class="bg-white rounded-3xl shadow-2xl p-6 text-center transition-transform duration-300 hover:-translate-y-3">
            <h3 class="text-xl font-semibold text-9 text-blue-900 mb-4">Address</h3>
            <button onclick="openModal()" class="flex flex-col items-center justify-center w-64 p-4 rounded-xl shadow-md 
           bg-white text-black transition-all duration-300
           hover:text-white 
           hover:bg-gradient-to-r hover:from-green-500 hover:via-red-500 hover:via-yellow-400 hover:to-blue-500
           hover:scale-105">
              <span class="font-medium text-sm text-center">
                Zone 7 Bulua District 1<br>
                9000 Cagayan de Oro City<br>
                Misamis Oriental Philippines
              </span>
            </button>
          </div>

          <div id="mapModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-2xl relative">
              <button onclick="closeModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
              <h2 class="text-lg font-semibold text-blue-600 mb-4 text-center">Google Maps Location</h2>
              <iframe
                src="https://www.google.com/maps?q=Zone+7+Bulua+District+1+Cagayan+de+Oro+City+Misamis+Oriental+Philippines&output=embed"
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
              <div class="mt-4 text-center">
                <a href="https://www.google.com/maps/search/?api=1&query=Zone+7+Bulua+District+1+Cagayan+de+Oro+City+Misamis+Oriental+Philippines"
                  target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-9">Open in Google Maps</a>
              </div>
            </div>
          </div>

          <div
            class="bg-white rounded-3xl shadow-2xl p-6 text-center transition-transform duration-300 hover:-translate-y-3">
            <h3 class="text-xl font-semibold text-blue-900 text-9 mb-6">Social Media</h3>
            <div class="flex justify-center space-x-4">
              <a href="https://www.facebook.com/jeffrey.dental.lab.2025" target="_blank"
                class="flex items-center justify-center bg-white rounded-xl shadow-md w-12 h-12 hover:bg-blue-600 hover:scale-105 transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                  alt="Facebook" class="w-6 h-6">
              </a>
              <a href="#"
                class="flex items-center justify-center bg-white rounded-xl shadow-md w-12 h-12 hover:bg-gradient-to-tr hover:from-pink-500 hover:to-yellow-400 hover:scale-105 transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/e/e7/Instagram_logo_2016.svg" alt="Instagram"
                  class="w-6 h-6">
              </a>
              <a href="mailto:jeffreydentallab143@gmail.com"
                class="flex items-center justify-center bg-white rounded-xl shadow-md w-12 h-12 hover:bg-red-500 hover:scale-105 transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/Mail_%28iOS%29.svg" alt="Email"
                  class="w-6 h-6">
              </a>
              <a href="#"
                class="flex items-center justify-center bg-white rounded-xl shadow-md w-12 h-12 hover:bg-blue-800 hover:scale-105 transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/ca/LinkedIn_logo_initials.png" alt="LinkedIn"
                  class="w-6 h-6">
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>

  <section id="services" class="py-20 flex flex-col justify-center items-center bg-gray-200 px-6">
    <div class="text-center max-w-2xl">
      <h2 class="text-3xl font-bold text-blue-900 mb-6">Our Services</h2>
      <p class="text-gray-700">
        We provide high-quality dental prosthetics, denture repairs, and custom dental lab solutions for clinics and
        patients.
      </p>
    </div>
  </section>

  <section id="about" class="py-12 bg-gray-100 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-8">
      <h2 class="text-3xl font-bold text-blue-900">About Us</h2>
    </div>

    <div class="max-w-4xl mx-auto w-full flex flex-col md:flex-row bg-white rounded-2xl shadow-lg overflow-hidden">
      <div class="md:w-1/2 p-4 flex justify-center items-center">
        <div class="relative w-full h-auto flex justify-center">
          <img src="{{ asset('images/s.png') }}" alt="team"
            class="max-h-80 md:max-h-full w-auto object-contain rounded-xl shadow-lg">
        </div>
      </div>

      <div class="md:w-1/2 p-6 flex flex-col justify-center bg-blue-900 text-white">
        <h3 class="text-xs font-semibold uppercase tracking-wider mb-1 opacity-80">Who We Are</h3>
        <h2 class="text-2xl md:text-3xl font-bold leading-snug mb-4">
          Precision Crafted Smiles
        </h2>
        <p class="text-sm mb-6 opacity-90">
          Our dental laboratory focuses on excellence and precision in crafting dentures and prosthetics. Trusted by
          dental clinics and patients alike.
        </p>
        <a href="#learn-more"
          class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm font-medium rounded-full shadow-sm text-teal-600 bg-white hover:bg-gray-100 transition duration-150 ease-in-out">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
              clip-rule="evenodd" />
            <path fill-rule="evenodd"
              d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
              clip-rule="evenodd" />
          </svg>
          Learn More
        </a>
      </div>
    </div>
  </section>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script>
    // Modal Controls
    const signupModal = document.getElementById('signupModal');
    const loginModal = document.getElementById('loginModal');
    const openSignupModalBtn = document.getElementById('openSignupModal'); 
    const closeSignupModalBtn = document.getElementById('closeSignupModal');
    const openLoginFromSignup = document.getElementById('openLoginFromSignup');
    const closeLoginModalBtn = document.getElementById('closeLoginModal');
    const openSignupFromLogin = document.getElementById('openSignupFromLogin');

    openSignupModalBtn.addEventListener('click', () => {
      signupModal.classList.remove('hidden');
      signupModal.classList.add('flex');
    });

    closeSignupModalBtn.addEventListener('click', () => {
      signupModal.classList.add('hidden');
      signupModal.classList.remove('flex');
    });

    openLoginFromSignup.addEventListener('click', (e) => {
      e.preventDefault();
      signupModal.classList.add('hidden');
      signupModal.classList.remove('flex');
      loginModal.classList.remove('hidden');
      loginModal.classList.add('flex');
    });

    openSignupFromLogin.addEventListener('click', (e) => {
      e.preventDefault();
      loginModal.classList.add('hidden');
      loginModal.classList.remove('flex');
      signupModal.classList.remove('hidden');
      signupModal.classList.add('flex');
    });

    closeLoginModalBtn.addEventListener('click', () => {
      loginModal.classList.add('hidden');
      loginModal.classList.remove('flex');
    });

    window.addEventListener('click', (e) => {
      if (e.target === signupModal) {
        signupModal.classList.add('hidden');
        signupModal.classList.remove('flex');
      }
      if (e.target === loginModal) {
        loginModal.classList.add('hidden');
        loginModal.classList.remove('flex');
      }
    });

    // Laboratory Login Modal
    document.getElementById("loginBtn").addEventListener("click", function(e) {
      e.preventDefault();
      document.getElementById("labLoginModal").classList.remove("hidden");
      document.getElementById("labLoginModal").classList.add("flex");
    });

    document.getElementById("closeLabLoginModal").addEventListener("click", function() {
      document.getElementById("labLoginModal").classList.add("hidden");
      document.getElementById("labLoginModal").classList.remove("flex");
    });

    // Lab Login Validation
    const labPassword = document.getElementById("labPassword");
    const labPasswordError = document.getElementById("labPasswordError");
    labPassword.addEventListener("input", () => {
      if (labPassword.value.length > 0 && labPassword.value.length < 6) {
        labPasswordError.classList.remove("hidden");
        labPassword.classList.add("border-red-500");
      } else {
        labPasswordError.classList.add("hidden");
        labPassword.classList.remove("border-red-500");
      }
    });

    const labEmail = document.getElementById("labEmail");
    const labEmailError = document.getElementById("labEmailError");
    labEmail.addEventListener("input", () => {
      if (labEmail.validity.patternMismatch || labEmail.validity.typeMismatch) {
        labEmailError.classList.remove("hidden");
        labEmail.classList.add("border-red-500");
      } else {
        labEmailError.classList.add("hidden");
        labEmail.classList.remove("border-red-500");
      }
    });

    const toggleLabPassword = document.getElementById("toggleLabPassword");
    const labEyeIcon = document.getElementById("labEyeIcon");
    toggleLabPassword.addEventListener("click", () => {
      const type = labPassword.getAttribute("type") === "password" ? "text" : "password";
      labPassword.setAttribute("type", type);
      labEyeIcon.classList.toggle("text-[#189ab4]");
    });

    // Clinic Signup Form Validation
    const clinicSignupForm = document.getElementById('clinicSignupForm');
    const usernameInput = document.getElementById('username');
    const clinicNameInput = document.getElementById('clinic_name');
    const emailInput = document.getElementById('signup_email');
    const passwordInput = document.getElementById('signup_password');
    const confirmPasswordInput = document.getElementById('password_confirmation');

    // Real-time validation
    usernameInput.addEventListener('input', () => {
      const error = document.getElementById('usernameError');
      if (usernameInput.value.trim().length < 3) {
        error.classList.remove('hidden');
        usernameInput.classList.add('border-red-500', 'shake');
      } else {
        error.classList.add('hidden');
        usernameInput.classList.remove('border-red-500', 'shake');
      }
    });

    clinicNameInput.addEventListener('input', () => {
      const error = document.getElementById('clinicNameError');
      if (clinicNameInput.value.trim().length < 2) {
        error.classList.remove('hidden');
        clinicNameInput.classList.add('border-red-500', 'shake');
      } else {
        error.classList.add('hidden');
        clinicNameInput.classList.remove('border-red-500', 'shake');
      }
    });

    emailInput.addEventListener('input', () => {
      const error = document.getElementById('emailError');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(emailInput.value)) {
        error.classList.remove('hidden');
        emailInput.classList.add('border-red-500', 'shake');
      } else {
        error.classList.add('hidden');
        emailInput.classList.remove('border-red-500', 'shake');
      }
    });

    passwordInput.addEventListener('input', () => {
      const error = document.getElementById('passwordError');
      if (passwordInput.value.length < 6) {
        error.classList.remove('hidden');
        passwordInput.classList.add('border-red-500', 'shake');
      } else {
        error.classList.add('hidden');
        passwordInput.classList.remove('border-red-500', 'shake');
      }
    });

    confirmPasswordInput.addEventListener('input', () => {
      const error = document.getElementById('confirmPasswordError');
      if (confirmPasswordInput.value !== passwordInput.value) {
        error.classList.remove('hidden');
        confirmPasswordInput.classList.add('border-red-500', 'shake');
      } else {
        error.classList.add('hidden');
        confirmPasswordInput.classList.remove('border-red-500', 'shake');
      }
    });

    // Form submission validation
    clinicSignupForm.addEventListener('submit', (e) => {
      let isValid = true;

      // Validate username
      if (usernameInput.value.trim().length < 3) {
        document.getElementById('usernameError').classList.remove('hidden');
        usernameInput.classList.add('border-red-500', 'shake');
        isValid = false;
      }

      // Validate clinic name
      if (clinicNameInput.value.trim().length < 2) {
        document.getElementById('clinicNameError').classList.remove('hidden');
        clinicNameInput.classList.add('border-red-500', 'shake');
        isValid = false;
      }

      // Validate email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(emailInput.value)) {
        document.getElementById('emailError').classList.remove('hidden');
        emailInput.classList.add('border-red-500', 'shake');
        isValid = false;
      }

      // Validate password
      if (passwordInput.value.length < 6) {
        document.getElementById('passwordError').classList.remove('hidden');
        passwordInput.classList.add('border-red-500', 'shake');
        isValid = false;
      }

      // Validate password confirmation
      if (confirmPasswordInput.value !== passwordInput.value) {
        document.getElementById('confirmPasswordError').classList.remove('hidden');
        confirmPasswordInput.classList.add('border-red-500', 'shake');
        isValid = false;
      }

      if (!isValid) {
        e.preventDefault();
      }
    });

    // Map modal
    function openModal() {
      document.getElementById('mapModal').classList.remove('hidden');
      document.getElementById('mapModal').classList.add('flex');
    }

    function closeModal() {
      document.getElementById('mapModal').classList.add('hidden');
      document.getElementById('mapModal').classList.remove('flex');
    }
  </script>
</body>

</html>