<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Clinic Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .notification-popup-transition {
            transition: transform 0.2s ease-out, opacity 0.2s ease-out;
        }
    </style>
</head>

<body class="h-screen flex bg-white text-[12px]">


    <aside class="w-48 bg-blue-900 text-white flex flex-col fixed top-0 left-0 h-full">
        <div class="h-20 px-3 border-b border-blue-700 flex items-center justify-center">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-12 object-contain">
        </div>

        <nav class="mt-4 flex-grow space-y-1">
            <a href="{{ route('clinic.case-orders.index') }}"
                class="flex items-center space-x-2 p-2 rounded-l-lg hover:bg-gray-300 hover:text-blue-900 {{ request()->routeIs('clinic.case-orders.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v12a2 2 0 01-2 2z" />
                </svg>
                <span>Case Orders</span>
            </a>

            <a href="{{ route('clinic.appointments.index') }}"
                class="flex items-center space-x-2 p-2 rounded-l-lg hover:bg-gray-300 hover:text-blue-900 {{ request()->routeIs('clinic.appointments.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        d="M8 7V3m8 4V3m-9 8h10m-10 4h10M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Appointments</span>
            </a>

            <a href="{{ route('clinic.patients.index') }}"
                class="flex items-center space-x-2 p-2 rounded-l-lg hover:bg-gray-300 hover:text-blue-900 {{ request()->routeIs('clinic.patients.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        d="M5.121 17.804A8.962 8.962 0 0112 15c2.5 0 4.735 1.03 6.379 2.684M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Patients</span>
            </a>

            <a href="{{ route('clinic.dentists.index') }}"
                class="flex items-center space-x-2 p-2 rounded-l-lg hover:bg-gray-300 hover:text-blue-900 {{ request()->routeIs('clinic.dentists.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zM6 20v-1a4 4 0 014-4h4a4 4 0 014 4v1H6z" />
                </svg>
                <span>Dentists</span>
            </a>

            <a href="{{ route('clinic.billing.index') }}"
                class="flex items-center space-x-2 p-2 rounded-l-lg hover:bg-gray-300 hover:text-blue-900 {{ request()->routeIs('clinic.billing.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v2m14 0v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9m14 0h-4" />
                </svg>
                <span>Billing</span>
            </a>
        </nav>

        <div class="p-4 border-t border-blue-700 text-xs space-y-2">
            <p class="uppercase tracking-wide text-indigo-300 font-semibold">Follow Us</p>
            <div class="flex items-center gap-3 text-indigo-200">
                <a href="https://www.facebook.com/jeffrey.dental.lab.2025" target="_blank" class="hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M22 12a10 10 0 1 0-11.5 9.95v-7.05H8v-3h2.5V9.5a3.5 3.5 0 0 1 3.7-3.8c1 0 2 .1 2 .1v2.3h-1.2c-1.2 0-1.6.8-1.6 1.6v1.8H17l-.3 3h-2.5v7.05A10 10 0 0 0 22 12" />
                    </svg>
                </a>
                <a href="#" class="hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M23 3a10.9 10.9 0 0 1-3.14.86A4.48 4.48 0 0 0 22.4.36a9.08 9.08 0 0 1-2.88 1.1A4.52 4.52 0 0 0 16.62 0a4.48 4.48 0 0 0-4.5 4.5c0 .35.04.7.12 1.03A12.94 12.94 0 0 1 1.64.88 4.48 4.48 0 0 0 3.04 6.5a4.48 4.48 0 0 1-2.04-.56v.06a4.52 4.52 0 0 0 3.6 4.41 4.48 4.48 0 0 1-2.03.08 4.48 4.48 0 0 0 4.18 3.12A9 9 0 0 1 0 19.54a12.73 12.73 0 0 0 6.92 2.03c8.3 0 12.84-6.87 12.84-12.84 0-.2 0-.41-.01-.61A9.22 9.22 0 0 0 24 4.56a9.1 9.1 0 0 1-2.6.71A4.52 4.52 0 0 0 23 3z" />
                    </svg>
                </a>
            </div>
            <p class="mt-2 text-indigo-300 text-[12px]">@JeffreyDentalLab</p>
        </div>
    </aside>

    <div class="flex-grow flex flex-col h-full ml-48">

        <header class="bg-white p-3 flex items-center justify-between shadow-md z-10">

            <div class="flex items-center gap-2 border border-gray-300 rounded-lg w-96 p-2 bg-white">
                <svg class="w-4 h-4 text-gray-300 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5z" />
                </svg>
                <input type="text" placeholder="Search..." class="outline-none w-full text-[12px]">
            </div>


            <div class="flex items-center gap-2">

                <a href="{{ route('clinic.dashboard') }}"
                    class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M3 12l9-9 9 9M4 10v10h5v-6h6v6h5V10" />
                    </svg>
                </a>
                <div id="notification-container" class="relative">
                    <button id="notification-bell-btn"
                        class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-300 transition relative z-30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
                        </svg>

                        @if(isset($notificationCount) && $notificationCount > 0)
                        <span
                            class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                            {{ $notificationCount }}
                        </span>
                        @endif
                    </button>

                    <div id="notification-popup"
                        class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-300 hidden z-40 origin-top-right notification-popup-transition scale-95 opacity-0">
                        <div
                            class="p-4 border-b border-gray-300 flex justify-between items-center bg-gray-50 rounded-t-xl">
                            <h5 class="text-base font-semibold text-gray-700">Notifications</h5>
                            @if(isset($notificationCount) && $notificationCount > 0)
                            <span class="text-xs text-red-500 font-medium">{{ $notificationCount }} New</span>
                            @endif
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-gray-200">
                            @if(isset($clinicNotifications) && $clinicNotifications->count() > 0)
                            @foreach($clinicNotifications as $notification)
                            <a href="{{ $notification->link ?? '#' }}"
                                onclick="markAsRead(event, {{ $notification->id }})"
                                class="block p-3 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-2">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800">{{ $notification->title }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{
                                            $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                            @else
                            <div class="p-6 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
                                </svg>
                                <p class="text-sm">No notifications yet</p>
                            </div>
                            @endif
                        </div>

                        @if(isset($clinicNotifications) && $clinicNotifications->count() > 0)
                        <div class="p-2 border-t border-gray-200">
                            <a href="{{ route('clinic.notifications.index') }}"
                                class="block text-center text-blue-600 hover:text-blue-800 text-sm font-medium py-2">
                                View All Notifications
                            </a>
                        </div>
                        @endif
                    </div>
                </div>


                <div id="userDropdownContainer" class="relative">
                    <button id="userDropdownBtn"
                        class="flex items-center space-x-1 rounded-lg hover:bg-gray-300 transition p-1">
                        @if(Auth::guard('clinic')->user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::guard('clinic')->user()->profile_photo) }}"
                            alt="User Photo" class="w-6 h-6 rounded-full object-cover">
                        @else
                        <div
                            class="w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs">
                            {{ strtoupper(substr(Auth::guard('clinic')->user()->username, 0, 1)) }}
                        </div>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="userDropdownMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-2xl border border-gray-200 z-50">
                        <div class="p-3 border-b border-gray-200">
                            <p class="text-sm font-semibold text-gray-700">
                                {{ Auth::guard('clinic')->user()->username }}
                            </p>
                            <p class="text-xs text-gray-500">{{ Auth::guard('clinic')->user()->email }}</p>
                        </div>

                        <ul class="py-2">
                            <li>
                                <button id="openClinicSettingsBtn"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Settings
                                </button>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>


        <main class="flex-grow overflow-y-auto">
            <div>
                @yield('content')
            </div>
        </main>
    </div>


    <!-- Clinic Settings Modal -->
    <div id="clinicSettingsModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">

            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <img id="previewClinicPhoto"
                                class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-md"
                                src="{{ Auth::guard('clinic')->user()->profile_photo ? asset('storage/' . Auth::guard('clinic')->user()->profile_photo) : 'https://via.placeholder.com/150' }}"
                                alt="Clinic Profile">
                            <label
                                class="absolute bottom-0 right-0 bg-white rounded-full p-1 cursor-pointer hover:bg-gray-100">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" form="clinicSettingsForm" name="profile_photo" accept="image/*"
                                    class="hidden" onchange="previewClinicImage(event)">
                            </label>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Clinic Settings</h2>
                            <p class="text-sm text-blue-100">Manage your profile and security</p>
                        </div>
                    </div>
                    <button onclick="closeModal('clinicSettingsModal')" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form id="clinicSettingsForm" method="POST" action="{{ route('clinic.settings.update') }}"
                enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Profile Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b-2 border-blue-600">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <h3 class="text-lg font-bold text-gray-800">Profile Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Clinic Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="clinic_name"
                                value="{{ old('clinic_name', Auth::guard('clinic')->user()->clinic_name) }}" required
                                placeholder="Enter clinic name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('clinic_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email"
                                value="{{ old('email', Auth::guard('clinic')->user()->email) }}" required
                                placeholder="clinic@example.com"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
                            <input type="text" name="contact_number"
                                value="{{ old('contact_number', Auth::guard('clinic')->user()->contact_number ?? '') }}"
                                placeholder="09XX XXX XXXX"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('contact_number')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                            <input type="text" name="address"
                                value="{{ old('address', Auth::guard('clinic')->user()->address ?? '') }}"
                                placeholder="Clinic address"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="space-y-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-2 pb-2 border-b-2 border-blue-600">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h3 class="text-lg font-bold text-gray-800">Change Password</h3>
                        <span class="text-sm text-gray-500">(Optional)</span>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> Leave password fields blank if you don't want to change your
                            password.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                            <input type="password" name="password" placeholder="Enter new password (min. 8 characters)"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                    </div>
                </div>
            </form>

            <!-- Footer -->
            <div class="px-8 py-5 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
                <button type="button" onclick="closeModal('clinicSettingsModal')"
                    class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition">
                    Cancel
                </button>
                <button type="submit" form="clinicSettingsForm"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition">
                    Save Changes
                </button>
            </div>

        </div>
    </div>

    <script>
        function openModal(id) { 
    document.getElementById(id).classList.remove('hidden'); 
    document.getElementById(id).classList.add('flex'); 
}

function closeModal(id) { 
    document.getElementById(id).classList.remove('flex'); 
    document.getElementById(id).classList.add('hidden'); 
}

// Preview Clinic Photo
function previewClinicImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewClinicPhoto').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

// Mark notification as read
function markAsRead(event, notificationId) {
    fetch(`/clinic/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(response => {
        if (response.ok) {
            // Update notification count
            const countElement = document.querySelector('#notification-bell-btn span');
            if (countElement) {
                let count = parseInt(countElement.textContent);
                count = Math.max(0, count - 1);
                if (count === 0) {
                    countElement.remove();
                } else {
                    countElement.textContent = count;
                }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Notification popup toggle
    const bellButton = document.getElementById('notification-bell-btn');
    const popup = document.getElementById('notification-popup');
    const container = document.getElementById('notification-container');

    if (bellButton && popup) {
        function closePopup() {
            popup.classList.add('hidden', 'scale-95', 'opacity-0');
            popup.classList.remove('scale-100', 'opacity-100');
        }

        function openPopup() {
            popup.classList.remove('hidden');
            // Force reflow
            void popup.offsetWidth;
            popup.classList.add('scale-100', 'opacity-100');
            popup.classList.remove('scale-95', 'opacity-0');
        }

        bellButton.addEventListener('click', function(e) {
            e.stopPropagation();
            if (popup.classList.contains('hidden')) {
                openPopup();
            } else {
                closePopup();
            }
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!popup.classList.contains('hidden') && !container.contains(e.target)) {
                closePopup();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
                closePopup();
            }
        });
    }

    // User dropdown and settings modal
    const userBtn = document.getElementById('userDropdownBtn');
    const userMenu = document.getElementById('userDropdownMenu');
    const openSettingsBtn = document.getElementById('openClinicSettingsBtn');
    const modal = document.getElementById('clinicSettingsModal');

    if (userBtn && userMenu) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function(e) {
            if (!userMenu.contains(e.target) && !userBtn.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }

    if (openSettingsBtn && modal) {
        openSettingsBtn.addEventListener('click', function() {
            userMenu.classList.add('hidden');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    }

    // Close modal on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    });
});
    </script>
</body>

</html>