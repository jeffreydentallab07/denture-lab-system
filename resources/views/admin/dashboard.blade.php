@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Clinics</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalClinics }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Case Orders</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalCaseOrders }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Appointments</h3>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalAppointments }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Technicians</h3>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $totalTechnicians }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Riders</h3>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $totalRiders }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Materials</h3>
            <p class="text-3xl font-bold text-teal-600 mt-2">{{ $totalMaterials }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Appointments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Appointments</h2>
            <div class="space-y-3">
                @forelse($recentAppointments as $appointment)
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <p class="font-semibold">{{ $appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $appointment->schedule_datetime->format('M d, Y h:i A') }}</p>
                    <div class="text-xs w-fit px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $appointment->work_status
                        }}</div>
                </div>
                @empty
                <p class="text-gray-500">No appointments yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Pending Case Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Pending Case Orders</h2>
            <div class="space-y-3">
                @forelse($pendingCaseOrders as $order)
                <div class="border-l-4 border-yellow-500 pl-4 py-2">
                    <p class="font-semibold">{{ $order->clinic->clinic_name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">Patient: {{ $order->patient->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $order->case_type }}</p>
                </div>
                @empty
                <p class="text-gray-500">No pending orders.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection