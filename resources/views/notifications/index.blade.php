@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">All Notifications</h1>
        @if($notifications->where('read', false)->count() > 0)
        <form action="{{ route('notifications.markAllRead') }}" method="POST">
            @csrf
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Mark All as Read
            </button>
        </form>
        @endif
    </div>

    <div class="space-y-2">
        @forelse($notifications as $notification)
        <div class="bg-white p-4 rounded-lg shadow {{ $notification->read ? 'opacity-60' : '' }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        @if(!$notification->read)
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        @endif
                        <h3 class="font-semibold">{{ $notification->title }}</h3>
                    </div>
                    <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if($notification->link)
                <a href="{{ $notification->link }}" onclick="markAsRead(event, {{ $notification->id }})"
                    class="text-blue-600 hover:underline text-sm">
                    View
                </a>
                @endif
            </div>
        </div>
        @empty
        <p class="text-center text-gray-500 py-8">No notifications</p>
        @endforelse
    </div>

    {{-- <div class="mt-4">
        {{ $notifications->links() }}
    </div> --}}
</div>
@endsection