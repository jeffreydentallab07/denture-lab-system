@extends('layouts.clinic')

@section('title', 'Notifications')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
                <p class="text-gray-600">Stay updated with your case orders and appointments</p>
            </div>
            @if($notificationCount > 0)
            <form action="{{ route('clinic.notifications.markAllAsRead') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    Mark All as Read
                </button>
            </form>
            @endif
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <!-- Unread Count -->
        @if($notificationCount > 0)
        <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
            <p class="text-sm text-blue-800">
                <strong>{{ $notificationCount }}</strong> unread notification{{ $notificationCount > 1 ? 's' : '' }}
            </p>
        </div>
        @endif

        <!-- Notifications List -->
        <div class="bg-white rounded-lg shadow">
            @forelse($allNotifications as $notification)
            <a href="{{ $notification->link }}" onclick="markAsRead(event, {{ $notification->id }})"
                class="block border-b last:border-b-0 hover:bg-gray-50 transition">
                <div class="p-4 flex items-start gap-4">
                    <!-- Unread Indicator -->
                    @if(!$notification->read)
                    <div class="w-3 h-3 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    @else
                    <div class="w-3 h-3 bg-gray-300 rounded-full mt-1.5 flex-shrink-0"></div>
                    @endif

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 {{ !$notification->read ? 'font-bold' : '' }}">
                                    {{ $notification->title }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ $notification->created_at->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            @if(!$notification->read)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">
                                New
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Arrow -->
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
            @empty
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
                <p class="text-gray-500 text-lg font-medium">No notifications</p>
                <p class="text-gray-400 text-sm mt-1">You're all caught up!</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($allNotifications->hasPages())
        <div class="mt-6">
            {{ $allNotifications->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function markAsRead(event, notificationId) {
    fetch(`/clinic/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(response => {
        if (response.ok) {
            // Update notification count in header if exists
            const countElement = document.querySelector('#notification-count');
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
@endsection