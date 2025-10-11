@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <a href="{{ route('admin.riders.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Riders
        </a>

        <div class="bg-white rounded-lg shadow p-6">
            <!-- Header with Photo -->
            <div class="flex items-center gap-6 mb-6 pb-6 border-b">
                <img src="{{ $rider->photo ? asset('storage/' . $rider->photo) : asset('images/default-avatar.png') }}"
                    alt="{{ $rider->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $rider->name }}</h1>
                    <p class="text-gray-600">{{ $rider->email }}</p>
                    <p class="text-gray-600">{{ $rider->contact_number }}</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Total Deliveries</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $rider->deliveries_count }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $rider->deliveries->where('delivery_status', 'delivered')->count() }}
                    </p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">
                        {{ $rider->deliveries->whereIn('delivery_status', ['pending', 'in-transit'])->count() }}
                    </p>
                </div>
            </div>

            <!-- Recent Deliveries -->
            <h2 class="text-xl font-bold mb-4">Recent Deliveries</h2>
            <div class="space-y-2">
                @forelse($rider->deliveries->take(10) as $delivery)
                <div class="border-l-4 border-blue-500 pl-4 py-2 bg-gray-50 rounded">
                    <p class="font-semibold">{{ $delivery->appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $delivery->delivery_address }}</p>
                    <p class="text-sm text-gray-600">{{ $delivery->delivery_date ?
                        \Carbon\Carbon::parse($delivery->delivery_date)->format('M d, Y') : 'N/A' }}</p>
                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $delivery->delivery_status
                        }}</span>
                </div>
                @empty
                <p class="text-gray-500">No deliveries assigned yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection