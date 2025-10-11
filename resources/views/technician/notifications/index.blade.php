@extends('layouts.technician')

@section('title', 'Notifications')

@section('content')


<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
                <p class="text-gray-600">All your notifications</p>
            </div>
            @php
            $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->count();
            @endphp
            @if($unreadCount > 0)
            <form action="{{ route('technician.notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    Mark All as Read ({{ $unreadCount }})
                </button>
            </form>
            @endif
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-lg shadow">
            @forelse($notifications as $notification)
            <a href="{{ $notification->link ?? '#' }}" onclick="markNotificationAsRead(event, {{ $notification->id }})"
                class="block p-4 border-b hover:bg-gray-50 transition {{ $notification->read ? 'bg-white' : 'bg-blue-50' }}">
                <div class="flex items-start gap-3">
                    @if(!$notification->read)
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                    @endif
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h3 class="font-semibold text-gray-800">{{ $notification->title }}</h3>
                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                    </div>
                </div>
            </a>
            @empty
            <div class="p-12 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9">
                    </path>
                </svg>
                <p>No notifications yet</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination - Simple check -->
        {{-- <div class="mt-4">
            {{ $notifications->links() }}
        </div> --}}
    </div>
</div>

<script>
    function markNotificationAsRead(event, notificationId) {
    fetch(`/technician/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
}
</script>
@endsection