<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Rider Dashboard - Denture Lab')</title>
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

<body class="h-screen flex bg-gray-50">

    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="w-64 bg-blue-900 text-white flex flex-col fixed top-0 left-0 h-full z-40 transform -translate-x-full md:translate-x-0 sidebar-transition">
        <!-- Logo -->
        <div class="h-16 md:h-20 px-4 border-b border-blue-700 flex items-center justify-between">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-10 md:h-12 object-contain">
            <!-- Close button (mobile only) -->
            <button id="close-sidebar-btn" class="md:hidden text-white p-2 hover:bg-blue-800 rounded">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="mt-4 flex-grow space-y-1 px-3 overflow-y-auto">
            <a href="{{ route('rider.dashboard') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition
                      {{ request()->routeIs('rider.dashboard') ? 'bg-white text-blue-900 font-semibold' : 'text-white hover:bg-blue-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('rider.pickups.index') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition
                      {{ request()->routeIs('rider.pickups.*') ? 'bg-white text-blue-900 font-semibold' : 'text-white hover:bg-blue-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M10 17h4V5H2v12h2" />
                    <path d="M22 17h-4V9h3l1 2z" />
                    <circle cx="7.5" cy="17.5" r="2.5" />
                    <circle cx="17.5" cy="17.5" r="2.5" />
                </svg>
                <span>My Pickups</span>
            </a>

            <a href="{{ route('rider.deliveries.index') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition text-white hover:bg-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M10 17h4V5H2v12h2" />
                    <path d="M22 17h-4V9h3l1 2z" />
                    <circle cx="7.5" cy="17.5" r="2.5" />
                    <circle cx="17.5" cy="17.5" r="2.5" />
                </svg>
                <span>My Deliveries</span>
            </a>
        </nav>

        <!-- User Profile at Bottom -->
        <div class="p-4 border-t border-blue-700">
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-avatar.png') }}"
                    alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-white">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-blue-200">Rider</p>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('settings.index') }}"
                    class="block w-full text-left px-3 py-2 text-sm text-white hover:bg-blue-800 rounded transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-3 py-2 text-sm text-red-300 hover:bg-blue-800 rounded transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col h-full md:ml-64">
        <!-- Header -->
        <header class="bg-white p-3 md:p-4 flex items-center justify-between shadow-sm z-10">
            <div class="flex items-center gap-2 md:gap-4">
                <!-- Hamburger Menu (Mobile Only) -->
                <button id="hamburger-btn" class="md:hidden text-gray-700 p-2 hover:bg-gray-100 rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-lg md:text-xl font-bold text-gray-800">Rider Portal</h1>
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <!-- Notifications -->
                <div id="notification-container" class="relative">
                    <button id="notification-bell-btn"
                        class="flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-lg hover:bg-gray-100 transition relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
                        </svg>

                        @if($notificationCount > 0)
                        <span id="notification-count"
                            class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                            {{ $notificationCount }}
                        </span>
                        @endif
                    </button>

                    <!-- Notification Popup -->
                    <div id="notification-popup"
                        class="absolute right-0 mt-3 w-80 max-w-[calc(100vw-2rem)] bg-white rounded-xl shadow-2xl border border-gray-300 hidden z-40 origin-top-right scale-95 opacity-0 notification-popup-transition">
                        <div
                            class="p-3 md:p-4 border-b border-gray-300 flex justify-between items-center bg-gray-50 rounded-t-xl">
                            <h5 class="text-sm md:text-base font-semibold text-gray-700">Notifications</h5>
                            @if($notificationCount > 0)
                            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-blue-600 hover:underline">Mark all as
                                    read</button>
                            </form>
                            @endif
                        </div>

                        <div class="max-h-80 md:max-h-96 overflow-y-auto">
                            @forelse($notifications as $notification)
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
                            @empty
                            <div class="p-6 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                <p class="text-sm">No new notifications</p>
                            </div>
                            @endforelse
                        </div>

                        <div class="p-2 border-t border-gray-200 text-center bg-gray-50 rounded-b-xl">
                            <a href="{{ route('notifications.index') }}"
                                class="text-xs font-medium text-blue-900 hover:text-blue-700">
                                View All Notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-grow overflow-y-auto">
            @yield('content')
        </main>
    </div>

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

        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });

        // Notifications
        function markAsRead(event, notificationId) {
            fetch(`/notifications/${notificationId}/mark-read`, {
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

        if (bellBtn && popup && container) {
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
        }
    </script>

</body>

</html>