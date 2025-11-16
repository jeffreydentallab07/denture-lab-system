<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Technician Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .notification-popup-transition {
            transition: transform 0.2s ease-out, opacity 0.2s ease-out;
        }

        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>

<body class="h-screen flex bg-white text-[12px]">

    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="w-48 bg-blue-900 text-white flex flex-col fixed top-0 left-0 h-full z-40 transform -translate-x-full md:translate-x-0 sidebar-transition">
        <div class="h-20 px-3 border-b border-blue-700 flex items-center justify-between">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-12 object-contain">
            <!-- Close button (mobile only) -->
            <button id="close-sidebar-btn" class="md:hidden text-white p-2 hover:bg-blue-800 rounded">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="mt-4 flex-grow space-y-1 overflow-y-auto">
            <a href="{{ route('technician.dashboard') }}"
                class="flex items-center space-x-2 p-2 rounded-l-lg transition duration-150 
                          hover:bg-gray-300 hover:text-blue-900 
                          {{ request()->routeIs('technician.dashboard') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('technician.appointments.index') }}"
                class="flex items-center space-x-2 p-2 rounded-l-lg transition duration-150 
                          hover:bg-gray-300 hover:text-blue-900 
                          {{ request()->routeIs('technician.appointments.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <span>My Appointments</span>
            </a>

            <a href="{{ route('technician.materials.index') }}"
                class="flex items-center space-x-2 p-2 rounded-l-lg transition duration-150 
                          hover:bg-gray-300 hover:text-blue-900 
                          {{ request()->routeIs('technician.materials.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path
                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                    <path d="M3.3 7l8.7 5 8.7-5" />
                    <path d="M12 22V12" />
                </svg>
                <span>Materials</span>
            </a>

            <a href="{{ route('technician.work-history') }}"
                class="flex items-center space-x-2 p-2 rounded-l-lg transition duration-150 
                          hover:bg-gray-300 hover:text-blue-900 
                          {{ request()->routeIs('technician.work-history') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span>Work History</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col h-full md:ml-48">

        <!-- Header -->
        <header class="bg-white p-3 flex items-center justify-between md:justify-end shadow-md z-10">

            <!-- Hamburger Menu (Mobile Only) -->
            <button id="hamburger-btn" class="md:hidden text-gray-700 p-2 hover:bg-gray-100 rounded">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Header Actions -->
            <div class="flex items-center gap-2">

                <!-- Dashboard Icon -->
                <a href="{{ route('technician.dashboard') }}"
                    class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-700" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z" />
                    </svg>
                </a>

                <!-- Notifications -->
                <div id="notification-container" class="relative">
                    <button id="notification-bell-btn"
                        class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-300 transition relative z-30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
                        </svg>

                        @if(isset($notificationCount) && $notificationCount > 0)
                        <span id="notification-count"
                            class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                            {{ $notificationCount }}
                        </span>
                        @endif
                    </button>

                    <!-- Notification Popup -->
                    <div id="notification-popup"
                        class="absolute right-0 mt-3 w-80 max-w-[calc(100vw-2rem)] bg-white rounded-xl shadow-2xl border border-gray-300 hidden z-40 origin-top-right scale-95 opacity-0 transition-all duration-200">
                        <div
                            class="p-4 border-b border-gray-300 flex justify-between items-center bg-gray-50 rounded-t-xl">
                            <h5 class="text-base font-semibold text-gray-700">Notifications</h5>
                            @if(isset($notificationCount) && $notificationCount > 0)
                            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-blue-600 hover:underline">Mark all as
                                    read</button>
                            </form>
                            @endif
                        </div>

                        <div class="max-h-96 overflow-y-auto">
                            @if(isset($notifications) && $notifications->count() > 0)
                            @foreach($notifications as $notification)
                            <a href="{{ $notification->link }}" onclick="markAsRead(event, {{ $notification->id }})"
                                class="block p-3 hover:bg-gray-50 border-b border-gray-100 transition">
                                <div class="flex items-start gap-2">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mt-1.5 flex-shrink-0"></div>
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
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                <p class="text-sm">No new notifications</p>
                            </div>
                            @endif
                        </div>

                        <div class="p-2 border-t border-gray-200 text-center bg-gray-50 rounded-b-xl">
                            <a href="{{ route('technician.notifications.index') }}"
                                class="text-xs font-medium text-blue-900 hover:text-blue-700">
                                View All Notifications
                            </a>
                        </div>
                    </div>
                </div>


                <!-- User Menu -->
                <div class="relative">
                    <button id="userMenuButton"
                        class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 text-[12px]">
                        <div
                            class="w-6 h-6 bg-blue-600 text-white flex items-center justify-center rounded-full font-bold text-[10px]">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                    </button>
                    <div id="userDropdownMenu"
                        class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-md border hidden z-50">
                        <a href="{{ route('settings.index') }}"
                            class="w-full text-left flex items-center px-3 py-2 hover:bg-gray-100 text-[12px]">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left flex items-center px-3 py-2 text-red-500 hover:bg-gray-100 text-[12px]">Sign
                                out</button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow overflow-y-auto">
            <div>
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile Sidebar Toggle
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            mobileOverlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('hidden');
        }

        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', openSidebar);
        }

        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', closeSidebar);
        }

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', closeSidebar);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });

        // Notifications
        function markAsRead(event, notificationId) {
            fetch(`/technician/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => {
                if (response.ok) {
                    const countElement = document.getElementById('notification-count');
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

        const bellBtn = document.getElementById('notification-bell-btn');
        const popup = document.getElementById('notification-popup');
        const container = document.getElementById('notification-container');

        bellBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if(popup.classList.contains('hidden')) {
                popup.classList.remove('hidden');
                void popup.offsetWidth; 
                popup.classList.add('scale-100', 'opacity-100');
                popup.classList.remove('scale-95', 'opacity-0');
            } else {
                popup.classList.add('scale-95', 'opacity-0');
                popup.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => { popup.classList.add('hidden'); }, 200);
            }
        });

        document.addEventListener('click', (e) => {
            if (!popup.classList.contains('hidden') && !container.contains(e.target)) {
                popup.classList.add('scale-95', 'opacity-0');
                popup.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => { popup.classList.add('hidden'); }, 200);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
                popup.classList.add('scale-95', 'opacity-0');
                popup.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => { popup.classList.add('hidden'); }, 200);
            }
        });

        const userMenuButton = document.getElementById('userMenuButton');
        const userDropdownMenu = document.getElementById('userDropdownMenu');

        if (userMenuButton && userDropdownMenu) {
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdownMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!userMenuButton.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                    userDropdownMenu.classList.add('hidden');
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !userDropdownMenu.classList.contains('hidden')) {
                    userDropdownMenu.classList.add('hidden');
                }
            });
        }
    </script>

</body>

</html>