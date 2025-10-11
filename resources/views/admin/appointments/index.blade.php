@extends('layouts.app')

@section('page-title', 'Appointments')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

    <!-- Header with Create Button -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Appointments</h1>
        <a href="{{ route('admin.appointments.create') }}"
            class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition">
            + Create Appointment
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
        {{ session('success') }}
    </div>
    @endif

    <!-- Appointments Table -->
    <div class="overflow-x-auto rounded-xl shadow-lg">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Appointment ID</th>
                    <th class="px-6 py-3 text-left">Case No.</th>
                    <th class="px-6 py-3 text-left">Clinic</th>
                    <th class="px-6 py-3 text-left">Patient</th>
                    <th class="px-6 py-3 text-left">Technician</th>
                    <th class="px-6 py-3 text-left">Schedule</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-3 font-semibold">APT-{{ str_pad($appointment->appointment_id, 5, '0',
                        STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.case-orders.show', $appointment->case_order_id) }}"
                            class="text-blue-600 hover:underline">
                            CASE-{{ str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) }}
                        </a>
                    </td>
                    <td class="px-6 py-3">{{ $appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</td>
                    <td class="px-6 py-3">{{ $appointment->caseOrder->patient->name ?? 'N/A' }}</td>
                    <td class="px-6 py-3">{{ $appointment->technician->name ?? 'N/A' }}</td>
                    <td class="px-6 py-3 text-sm">{{ $appointment->schedule_datetime->format('M d, Y h:i A') }}</td>
                    <td class="px-6 py-3">
                        <span
                            class="px-2 py-1 text-xs rounded-full font-medium
                        {{ $appointment->work_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                        ($appointment->work_status === 'in-progress' ? 'bg-blue-100 text-blue-800' : 
                        ($appointment->work_status === 'completed' ? 'bg-green-100 text-green-800' : 
                        ($appointment->work_status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                            {{ ucfirst(str_replace('-', ' ', $appointment->work_status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.appointments.show', $appointment->appointment_id) }}"
                            class="text-blue-600 hover:underline text-sm">
                            View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">No appointments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</div>
@endsection