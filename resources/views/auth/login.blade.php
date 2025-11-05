<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jeffrey Dental Laboratory</title>
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

  <!-- Header -->
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="flex items-center space-x-3">
        <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="h-12 w-auto">
      </div>
      <nav class="hidden md:flex space-x-6 items-center">
        <a href="#home" class="text-gray-700 hover:text-[#189ab4] font-medium transition">Home</a>
        <a href="#services" class="text-gray-700 hover:text-[#189ab4] font-medium transition">Services</a>
        <a href="#contact" class="text-gray-700 hover:text-[#189ab4] font-medium transition">
          Contact Us
        </a>
        <a href="#login"
          class="bg-[#189ab4] text-white px-6 py-2 rounded-lg hover:bg-[#127a95] transition font-medium">Login</a>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section id="home" class="relative bg-gradient-to-br from-teal-50 to-blue-50 py-20">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
          <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6">
            Your Smile,<br>
            <span class="text-[#189ab4]">Our Masterpiece</span>
          </h1>
          <p class="text-xl text-gray-600 mb-8">
            A modern system for dental clinics and laboratories to manage appointments,
            case orders, schedules, deliveries, and billing all in one place.
          </p>
          <div class="flex flex-wrap gap-4">
            <a href="#signup"
              class="bg-[#189ab4] text-white px-8 py-4 rounded-lg hover:bg-[#127a95] transition font-bold shadow-lg">
              Register Your Clinic
            </a>
            <a href="#login"
              class="bg-white text-[#189ab4] px-8 py-4 rounded-lg hover:bg-gray-50 transition font-bold shadow-lg border-2 border-[#189ab4]">
              Login
            </a>
          </div>
        </div>
        <div class="hidden md:block">
          <div class="relative">
            <img src="{{ asset('images/bg.jpg') }}" alt="Dental Lab" class="rounded-2xl shadow-2xl">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-4xl font-bold text-center text-gray-900 mb-12">Our Services</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-6 bg-gradient-to-br from-teal-50 to-blue-50 rounded-xl shadow-lg hover:shadow-xl transition">
          <div class="text-4xl mb-4">🦷</div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Case Management</h3>
          <p class="text-gray-600">Efficient tracking and management of all dental case orders from start to finish.</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl shadow-lg hover:shadow-xl transition">
          <div class="text-4xl mb-4">📅</div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Appointment Scheduling</h3>
          <p class="text-gray-600">Seamless scheduling system for pickups, deliveries, and consultations.</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl shadow-lg hover:shadow-xl transition">
          <div class="text-4xl mb-4">🚚</div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Delivery Tracking</h3>
          <p class="text-gray-600">Real-time tracking of all deliveries and pickups with our dedicated riders.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Unified Login Section -->
  <section id="login" class="py-20 bg-gradient-to-br from-gray-50 to-teal-50">
    <div class="max-w-lg mx-auto px-6">

      <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10">

        <!-- Logo & Title -->
        <div class="text-center mb-8">
          <div class="inline-block p-4 bg-gradient-to-br from-[#189ab4] to-blue-600 rounded-full mb-4">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
            </svg>
          </div>
          <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
          <p class="text-gray-600">Sign in to your account</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
          <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd" />
            </svg>
            <p class="text-red-700 text-sm font-medium">{{ $errors->first() }}</p>
          </div>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
          {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
          <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
          @csrf

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email or Username</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <input type="text" name="email_or_username" placeholder="Enter your email or username"
                value="{{ old('email_or_username') }}" required autofocus
                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#189ab4] focus:border-[#189ab4] transition">
            </div>
            @error('email_or_username')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required
                minlength="8"
                class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#189ab4] focus:border-[#189ab4] transition">
              <button type="button" onclick="togglePassword()"
                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#189ab4] transition">
                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

          <div class="flex items-center justify-between text-sm">
            <a href="{{ route('password.request') }}"
              class="text-[#189ab4] hover:text-[#127a95] font-medium transition">
              Forgot Password?
            </a>
          </div>

          <button type="submit"
            class="w-full bg-gradient-to-r from-[#189ab4] to-blue-600 text-white rounded-lg font-bold py-3 hover:from-[#127a95] hover:to-blue-700 transition shadow-lg transform hover:scale-[1.02] duration-200">
            Sign In
          </button>

          <!-- Info Text -->
          <div class="pt-4 border-t border-gray-200">
            <p class="text-center text-sm text-gray-600">
              <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                  clip-rule="evenodd" />
              </svg>
              For Lab Staff, Clinics, Technicians & Riders
            </p>
          </div>

          <!-- Clinic Registration -->
          <div class="text-center pt-4">
            <p class="text-sm text-gray-600 mb-2">New clinic?</p>
            <a href="#signup"
              class="inline-block bg-white text-[#189ab4] border-2 border-[#189ab4] px-6 py-2 rounded-lg hover:bg-[#189ab4] hover:text-white transition font-semibold">
              Register Your Clinic
            </a>
          </div>
        </form>

      </div>
    </div>
  </section>
  <!-- Clinic Signup Section -->
  <section id="signup" class="py-20 bg-gradient-to-br from-blue-50 via-white to-cyan-50">
    <div class="max-w-5xl mx-auto px-6">

      <!-- Header -->
      <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4">
          <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
        </div>
        <h2 class="text-4xl font-bold text-gray-900 mb-3">Register Your Clinic</h2>
      </div>

      <!-- Form Card -->
      <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        <!-- Form Header -->
        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-8 py-6">
          <h3 class="text-2xl font-bold text-white flex items-center">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Clinic Registration Form
          </h3>
          <p class="text-blue-100 text-sm mt-2">* Required fields</p>
        </div>

        <form action="{{ route('clinic.register') }}" method="POST" class="p-8 md:p-10">
          @csrf

          <!-- Clinic Information Section -->
          <div class="mb-8">
            <div class="flex items-center mb-6 pb-3 border-b-2 border-gray-200">
              <div class="bg-blue-100 rounded-lg p-2 mr-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <h4 class="text-xl font-bold text-gray-800">Clinic Information</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Clinic Name -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Clinic Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                  </div>
                  <input type="text" name="clinic_name" value="{{ old('clinic_name') }}"
                    placeholder="Bright Smile Dental Clinic" required
                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                @error('clinic_name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

              <!-- Owner Name -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Owner Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <input type="text" name="owner_name" value="{{ old('owner_name') }}" placeholder="Dr. Juan Dela Cruz"
                    required
                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                @error('owner_name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

              <!-- Address -->
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Address <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute top-3 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <input type="text" name="address" value="{{ old('address') }}"
                    placeholder="123 Main Street, Cagayan de Oro City" required
                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                @error('address')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <!-- Contact Information Section -->
          <div class="mb-8">
            <div class="flex items-center mb-6 pb-3 border-b-2 border-gray-200">
              <div class="bg-green-100 rounded-lg p-2 mr-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <h4 class="text-xl font-bold text-gray-800">Contact Information</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Email -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Email Address <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <input type="email" name="email" value="{{ old('email') }}" placeholder="clinic@example.com" required
                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

              <!-- Contact Number -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Contact Number <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                  </div>
                  <input type="tel" name="contact_number" value="{{ old('contact_number') }}"
                    placeholder="09XX XXX XXXX" required
                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                @error('contact_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <!-- Account Security Section -->
          <div class="mb-8">
            <div class="flex items-center mb-6 pb-3 border-b-2 border-gray-200">
              <div class="bg-purple-100 rounded-lg p-2 mr-3">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <h4 class="text-xl font-bold text-gray-800">Account Security</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Username -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Username <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <input type="text" name="username" value="{{ old('username') }}" placeholder="brightsmile_clinic"
                    required
                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                @error('username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div></div>

              <!-- Password -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                  <input type="password" name="password" placeholder="Minimum 8 characters" required minlength="8"
                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
              </div>

              <!-- Confirm Password -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <input type="password" name="password_confirmation" placeholder="Re-enter password" required
                    minlength="8"
                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
              </div>
            </div>
          </div>

          <button type="submit"
            class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg font-bold py-4 hover:from-blue-700 hover:to-cyan-700 transition shadow-lg text-lg flex items-center justify-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Register Clinic
          </button>

          <p class="text-center text-sm text-gray-600 mt-6">
            Already have an account?
            <a href="#login" class="text-blue-600 hover:underline font-semibold">Login here</a>
          </p>
        </form>
      </div>

      <!-- Info Banner -->
      <div class="mt-8 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-start">
          <svg class="w-6 h-6 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <h4 class="font-bold text-lg mb-1">Registration Process</h4>
            <p class="text-blue-100 text-sm">
              After submitting your registration, our admin team will review your application.
              You'll receive a notification once your account is approved. This usually takes 1-2 business days.
            </p>
          </div>
        </div>
      </div>

    </div>
  </section>


  <!-- Contact Section (paste near end of page, before </body>) -->
  <section id="contact" class="py-20 flex flex-col justify-center items-center bg-slate-50 px-6 relative">
    <h2 class="text-3xl font-bold text-sky-900 mb-6">Contact Us</h2>

    <div class="flex justify-center w-full">
      <div class="grid md:grid-cols-3 gap-8 w-full max-w-5xl">
        <!-- PHONE CARD -->
        <div
          class="bg-white rounded-2xl shadow-lg p-6 text-center transition-transform duration-300 hover:-translate-y-2">
          <h3 class="text-xl font-semibold text-sky-800 mb-4">Phone Number</h3>
          <div class="flex justify-center items-center">
            <div class="flex flex-col items-center space-y-3">
              <a href="tel:+639067732353"
                class="flex items-center justify-center bg-gray-100 rounded-xl shadow-sm w-44 h-12 px-3 hover:scale-105 transition">
                <img src="{{ asset('images/tml.png') }}" alt="TM" class="w-5 h-5 mr-3">
                <span class="text-gray-700 font-medium">0906 773 2353</span>
              </a>

              <a href="tel:0987654321"
                class="flex items-center justify-center bg-gray-100 rounded-xl shadow-sm w-44 h-12 px-3 hover:scale-105 transition">
                <img src="{{ asset('images/smart.png') }}" alt="Smart" class="w-5 h-5 mr-3">
                <span class="text-gray-700 font-medium">09 8765 4321</span>
              </a>

              <a href="tel:0911223344"
                class="flex items-center justify-center bg-gray-100 rounded-xl shadow-sm w-44 h-12 px-3 hover:scale-105 transition">
                <img src="{{ asset('images/dito.png') }}" alt="DITO" class="w-5 h-5 mr-3">
                <span class="text-gray-700 font-medium">09 1122 3344</span>
              </a>
            </div>
          </div>
        </div>

        <!-- ADDRESS CARD -->
        <div
          class="bg-white rounded-2xl shadow-lg p-6 text-center transition-transform duration-300 hover:-translate-y-2">
          <h3 class="text-xl font-semibold text-sky-800 mb-4">Address</h3>
          <button onclick="openModal()"
            class="flex flex-col items-center justify-center w-64 p-4 rounded-xl shadow-md bg-white text-black transition-all duration-300 hover:scale-105">
            <span class="font-medium text-sm text-center">
              Zone 7 Bulua District 1<br>
              9000 Cagayan de Oro City<br>
              Misamis Oriental, Philippines
            </span>
          </button>
        </div>

        <!-- SOCIAL / EMAIL CARD -->
        <div
          class="bg-white rounded-2xl shadow-lg p-6 text-center transition-transform duration-300 hover:-translate-y-2">
          <h3 class="text-xl font-semibold text-sky-800 mb-6">Social Media</h3>
          <div class="flex justify-center space-x-4">
            <a href="https://www.facebook.com/jeffrey.dental.lab.2025" target="_blank" rel="noopener"
              class="flex items-center justify-center bg-white rounded-xl shadow-sm w-12 h-12 hover:scale-105 transition">
              <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                alt="Facebook" class="w-5 h-5">
            </a>
            <a href="#"
              class="flex items-center justify-center bg-white rounded-xl shadow-sm w-12 h-12 hover:scale-105 transition">
              <img src="https://upload.wikimedia.org/wikipedia/commons/e/e7/Instagram_logo_2016.svg" alt="Instagram"
                class="w-5 h-5">
            </a>
            <a href="mailto:jeffreydentallab143@gmail.com"
              class="flex items-center justify-center bg-white rounded-xl shadow-sm w-12 h-12 hover:scale-105 transition">
              <img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/Mail_%28iOS%29.svg" alt="Email"
                class="w-5 h-5">
            </a>
            <a href="#"
              class="flex items-center justify-center bg-white rounded-xl shadow-sm w-12 h-12 hover:scale-105 transition">
              <img src="https://upload.wikimedia.org/wikipedia/commons/c/ca/LinkedIn_logo_initials.png" alt="LinkedIn"
                class="w-5 h-5">
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- MAP MODAL (hidden by default) -->
  <div id="mapModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-2xl p-6 rounded-2xl shadow-2xl relative">
      <button onclick="closeModal()" aria-label="Close map"
        class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
      <h2 class="text-lg font-semibold text-sky-700 mb-4 text-center">Google Maps Location</h2>
      <iframe
        src="https://www.google.com/maps?q=Zone+7+Bulua+District+1+Cagayan+de+Oro+City+Misamis+Oriental+Philippines&output=embed"
        width="100%" height="360" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      <div class="mt-4 text-center">
        <a href="https://www.google.com/maps/search/?api=1&query=Zone+7+Bulua+District+1+Cagayan+de+Oro+City+Misamis+Oriental+Philippines"
          target="_blank" rel="noopener" class="px-4 py-2 bg-sky-700 text-white rounded-lg hover:opacity-90">Open in
          Google Maps</a>
      </div>
    </div>
  </div>


  <!-- Footer -->
  <footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
      <h3 class="text-2xl font-bold mb-2">Jeffrey Dental Laboratory</h3>
      <p class="text-gray-400 mb-6">Your trusted partner in creating perfect smiles</p>
      <p class="text-gray-500 text-sm">&copy; 2024 Jeffrey Dental Laboratory. All rights reserved.</p>
    </div>
  </footer>

  <!-- JavaScript -->
  <script>
    function togglePassword() {
      const input = document.getElementById('loginPassword');
      const icon = document.getElementById('eyeIcon');
      
      if (input.type === "password") {
        input.type = "text";
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        icon.classList.add("text-[#189ab4]");
      } else {
        input.type = "password";
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        icon.classList.remove("text-[#189ab4]");
      }
    }

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });


    function openModal() {
    const modal = document.getElementById('mapModal');
    if (modal) modal.classList.remove('hidden', 'opacity-0'), modal.classList.add('flex');
    // prevent background scroll
    document.documentElement.style.overflow = 'hidden';
  }

  function closeModal() {
    const modal = document.getElementById('mapModal');
    if (modal) modal.classList.add('hidden'), modal.classList.remove('flex');
    document.documentElement.style.overflow = '';
  }

  // Close modal when clicking outside the content
  document.addEventListener('click', function (e) {
    const modal = document.getElementById('mapModal');
    if (!modal || modal.classList.contains('hidden')) return;
    const content = modal.querySelector('.rounded-2xl');
    if (content && !content.contains(e.target) && !e.target.closest('button[onclick="openModal()"]')) {
      closeModal();
    }
  });

  // Smooth scrolling for in-page nav links like #contact
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href === '#' || href === '#!') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        // update URL without jumping
        history.replaceState(null, '', href);
      }
    });
  });
  </script>

</body>

</html>