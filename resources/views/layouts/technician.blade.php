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
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 w-64 h-full bg-blue-900 text-white flex flex-col z-40 transform -translate-x-full md:translate-x-0 sidebar-transition">
        <div class="h-16 md:h-20 px-4 border-b border-blue-700 flex items-center justify-between">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-10 md:h-12 object-contain">
            <!-- Close button (mobile only) -->
            <button id="close-sidebar-btn" class="md:hidden text-white p-2 hover:bg-blue-800 rounded">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="mt-4 flex-grow space-y-1 px-3 overflow-y-auto">
            <a href="{{ route('technician.dashboard') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition
                      {{ request()->routeIs('technician.dashboard') ? 'bg-white text-blue-900 font-semibold' : 'text-white hover:bg-blue-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('technician.appointments.index') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition
                      {{ request()->routeIs('technician.appointments.*') ? 'bg-white text-blue-900 font-semibold' : 'text-white hover:bg-blue-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path
                        d="M8 7V3m8 4V3m-9 8h10m-10 4h10M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>My Appointments</span>
            </a>

            <a href="{{ route('technician.materials.index') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition
                      {{ request()->routeIs('technician.materials.*') ? 'bg-white text-blue-900 font-semibold' : 'text-white hover:bg-blue-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Materials</span>
            </a>

            <a href="{{ route('technician.work-history') }}"
                class="flex items-center space-x-3 p-3 rounded-lg transition
                      {{ request()->routeIs('technician.work-history') ? 'bg-white text-blue-900 font-semibold' : 'text-white hover:bg-blue-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span>Work History</span>
            </a>
        </nav>

        <!-- User Profile at Bottom -->
        <div class="p-4 border-t border-blue-700">
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-avatar.png') }}"
                    alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-white">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-blue-200">Technician</p>
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

    <!-- Main Content Wrapper -->
    <div class="min-h-screen md:ml-64">
        <!-- Header -->
        <header class="bg-white p-3 md:p-4 flex items-center justify-between shadow-sm sticky top-0 z-20">
            <div class="flex items-center gap-2 md:gap-4">
                <!-- Hamburger Menu (Mobile Only) -->
                <button id="hamburger-btn" class="md:hidden text-gray-700 p-2 hover:bg-gray-100 rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-lg md:text-xl font-bold text-gray-800">Technician Portal</h1>
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
                        @if(isset($notificationCount) && $notificationCount > 0)
                        <span
                            class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                            {{ $notificationCount }}
                        </span>
                        @endif
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notification-popup"
                        class="absolute right-0 mt-3 w-80 max-w-[calc(100vw-2rem)] bg-white rounded-xl shadow-2xl border border-gray-300 hidden z-50 origin-top-right transition-all">
                        <div
                            class="p-3 md:p-4 border-b border-gray-300 flex justify-between items-center bg-gray-50 rounded-t-xl">
                            <h5 class="text-sm md:text-base font-semibold text-gray-700">Notifications</h5>
                            @if(isset($notificationCount) && $notificationCount > 0)
                            <span class="text-xs text-red-500 font-medium">{{ $notificationCount }} New</span>
                            @endif
                        </div>

                        <div class="max-h-80 md:max-h-96 overflow-y-auto divide-y divide-gray-200">
                            @if(isset($notifications) && $notifications->count() > 0)
                            @foreach($notifications as $notification)
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
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                <p class="text-sm">No notifications</p>
                            </div>
                            @endif
                        </div>

                        @if(isset($notifications) && $notifications->count() > 0)
                        <div class="p-2 border-t border-gray-200 text-center bg-gray-50 rounded-b-xl">
                            <a href="{{ route('technician.notifications.index') }}"
                                class="text-xs font-medium text-blue-900 hover:text-blue-700">
                                View All Notifications
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Current Date/Time (Hidden on mobile) -->
                <div class="hidden md:block text-sm text-gray-600">
                    {{ now()->format('l, M d, Y') }}
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main>
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
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('hidden');
            document.body.style.overflow = '';
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

        // Notification popup
        document.addEventListener('DOMContentLoaded', function() {
            const bellButton = document.getElementById('notification-bell-btn');
            const popup = document.getElementById('notification-popup');
            const container = document.getElementById('notification-container');

            if (bellButton && popup) {
                function closePopup() {
                    popup.classList.add('hidden');
                }

                function openPopup() {
                    popup.classList.remove('hidden');
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
        });

        // Mark notification as read
        function markAsRead(event, notificationId) {
            fetch(`/technician/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => {
                if (response.ok) {
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
    </script>
</body>

</html>