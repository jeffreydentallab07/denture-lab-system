@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <a href="{{ route('admin.technicians.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Technicians
        </a>

        <div class="bg-white rounded-lg shadow p-6">
            <!-- Header with Photo -->
            <div class="flex items-center gap-6 mb-6 pb-6 border-b">
                <img src="{{ $technician->photo ? asset('storage/' . $technician->photo) : asset('images/default-avatar.png') }}"
                    alt="{{ $technician->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $technician->name }}</h1>
                    <p class="text-gray-600">{{ $technician->email }}</p>
                    <p class="text-gray-600">{{ $technician->contact_number }}</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Total Appointments</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $technician->appointments_count }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $technician->appointments->where('work_status', 'completed')->count() }}
                    </p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">
                        {{ $technician->appointments->whereIn('work_status', ['pending', 'in-progress'])->count() }}
                    </p>
                </div>
            </div>

            <!-- Recent Appointments -->
            <h2 class="text-xl font-bold mb-4">Recent Appointments</h2>
            <div class="space-y-2">
                @forelse($technician->appointments->take(10) as $appointment)
                <div class="border-l-4 border-blue-500 pl-4 py-2 bg-gray-50 rounded">
                    <p class="font-semibold">{{ $appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $appointment->schedule_datetime->format('M d, Y h:i A') }}</p>
                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $appointment->work_status
                        }}</span>
                </div>
                @empty
                <p class="text-gray-500">No appointments assigned yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection