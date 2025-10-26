<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Clinic Dashboard</title>
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 text-gray-800">

  <!-- NAVBAR -->
  <nav class="fixed top-0 left-0 right-0 bg-white shadow-md z-30">
    <div class="max-w-full mx-auto px-6 py-3 flex items-center justify-between">

      <!-- Logo -->
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-50 object-cover shadow">
      </div>

      <!-- Icons -->
      <div class="flex items-center gap-6">

        <!-- Notifications -->
        <button class="relative text-gray-600 hover:text-[#189ab4]">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 
                     0118 14.158V11a6 6 0 10-12 0v3.159c0 
                     .538-.214 1.055-.595 1.436L4 17h5m6 
                     0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          <span class="absolute top-0 right-0 inline-flex h-2 w-2 rounded-full bg-red-600"></span>
        </button>

        <!-- Dashboard -->
        <a href="{{ route('clinic.dashboard') }}" class="text-gray-600 hover:text-[#189ab4]">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h5v-6h6v6h5V10" />
          </svg>
        </a>

        <!-- Profile Settings -->
        <div x-data="{ open: false }" class="relative">
          <button @click="open = !open" class="text-gray-600 hover:text-[#189ab4]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 
                       002.573 1.066c1.528-.89 3.31.892 2.42 2.42a1.724 1.724 0 001.065 2.573
                       c1.757.426 1.757 2.924 0 3.35a1.724 1.724 0 00-1.065 2.573c.89 1.528-.892
                       3.31-2.42 2.42a1.724 1.724 0 00-2.573 1.065c-.426 1.757-2.924 1.757-3.35
                       0a1.724 1.724 0 00-2.573-1.065c-1.528.89-3.31-.892-2.42-2.42a1.724 1.724
                       0 00-1.065-2.573c-1.757-.426-1.757-2.924 0-3.35a1.724 1.724 0
                       001.065-2.573c-.89-1.528.892-3.31 2.42-2.42.996.58 2.147.254 2.573-1.066z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </button>

          <!-- Settings Modal -->
          <div x-show="open" @click.outside="open = false"
            class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg p-5 z-50">
            <h3 class="text-lg font-semibold mb-3">Profile Settings</h3>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label class="block text-sm font-medium">Clinic Name</label>
                <input type="text" name="clinic_name" value="{{ Auth::guard('clinic')->user()->clinic_name }}"
                  class="w-full px-3 py-2 border rounded-md" required>
              </div>

              <div class="mb-3">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ Auth::guard('clinic')->user()->email }}"
                  class="w-full px-3 py-2 border rounded-md" required>
              </div>

              <div class="mb-3">
                <label class="block text-sm font-medium">Profile Photo</label>
                <input type="file" name="profile_photo" class="w-full border rounded-md p-2">
              </div>

              <div class="flex justify-end gap-2">
                <button type="button" @click="open = false" class="px-4 py-2 bg-gray-200 rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-[#189ab4] text-white rounded-md">Update</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="text-gray-600 hover:text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 
                       01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
            </svg>
          </button>
        </form>

        <!-- User info -->
        <div class="flex items-center gap-3">
          <img
            src="{{ Auth::guard('clinic')->user()->profile_photo ? asset('storage/' . Auth::guard('clinic')->user()->profile_photo) : asset('images/user.png') }}"
            alt="User" class="h-9 w-9 rounded-full border-2 border-gray-300 shadow">
          <span class="font-semibold text-gray-700">{{ Auth::guard('clinic')->user()->clinic_name }}</span>
        </div>
      </div>
    </div>
  </nav>

  <!-- SIDEBAR & MAIN CONTENT -->
  <aside
    class="w-60 fixed top-16 bottom-0 left-0 flex flex-col justify-between bg-white/40 backdrop-blur-lg shadow-xl z-10">
    <!-- ... your sidebar links ... -->
  </aside>

  <main class="ml-60 mt-16 flex-1 p-8 overflow-auto">
    @yield('content')
  </main>

</body>

</html>