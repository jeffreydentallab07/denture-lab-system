@extends('layouts.technician')

@section('title', 'Technician Dashboard')

@section('content')
<div class="fixed top-4 right-4 z-50 space-y-2">
    @if(session('success'))
    <div id="successToast"
        class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-down">
        <span class="font-medium">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-white font-bold">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div id="errorToast"
        class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-down">
        <span class="font-medium">{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-white font-bold">&times;</button>
    </div>
    @endif
</div>

<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Technician Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Total Assigned</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalAssigned }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Pending Work</h3>
                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pendingWork }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Completed</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $completedWork }}</p>
            </div>
        </div>

        <!-- Today's Appointments -->
        @if($todayAppointments->count() > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gray-800">📅 Today's Appointments</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($todayAppointments as $apt)
                    <div class="border-l-4 border-blue-500 pl-4 py-3 bg-gray-50 rounded">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $apt->caseOrder->clinic->clinic_name }}</p>
                                <p class="text-sm text-gray-600">APT-{{ str_pad($apt->appointment_id, 5, '0',
                                    STR_PAD_LEFT) }}</p>
                                <p class="text-sm text-gray-600">{{ $apt->schedule_datetime->format('h:i A') }}</p>
                            </div>
                            <a href="{{ route('technician.appointments.show', $apt->appointment_id) }}"
                                class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                                View Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Appointments Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gray-800">My Appointments</h2>
            </div>

            @if($appointments->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                <p>No appointments assigned to you.</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-blue-900 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left">Appointment #</th>
                            <th class="px-6 py-3 text-left">Clinic</th>
                            <th class="px-6 py-3 text-left">Case Type</th>
                            <th class="px-6 py-3 text-left">Schedule</th>
                            <th class="px-6 py-3 text-left">Work Status</th>
                            <th class="px-6 py-3 text-left">Materials Used</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($appointments as $appointment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                APT-{{ str_pad($appointment->appointment_id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $appointment->caseOrder->case_type ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $appointment->schedule_datetime->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4">
                                <form
                                    action="{{ route('technician.appointment.update', $appointment->appointment_id) }}"
                                    method="POST" id="statusForm_{{ $appointment->appointment_id }}">
                                    @csrf
                                    <select name="work_status"
                                        onchange="confirmStatusChange(this, {{ $appointment->appointment_id }})"
                                        class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-blue-500 focus:border-blue-500">
                                        <option value="pending" {{ $appointment->work_status == 'pending' ? 'selected' :
                                            '' }}>Pending</option>
                                        <option value="in-progress" {{ $appointment->work_status == 'in-progress' ?
                                            'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $appointment->work_status == 'completed' ?
                                            'selected' : '' }}>Completed</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $appointment->materialUsages->count() }} material(s)
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('technician.appointments.show', $appointment->appointment_id) }}"
                                    class="text-blue-600 hover:underline text-sm">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Confirm Status Change</h2>
        <p class="text-gray-600 mb-6" id="confirmMessage">Are you sure you want to update the status?</p>
        <div class="flex justify-end gap-3">
            <button id="cancelBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmBtn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Confirm</button>
        </div>
    </div>
</div>

<script>
    let currentForm = null;
let previousValue = null;

function confirmStatusChange(selectElement, appointmentId) {
    const newValue = selectElement.value;
    
    if (previousValue === null) {
        previousValue = selectElement.value;
    }
    
    if (newValue === previousValue) {
        return; // No change
    }
    
    const form = document.getElementById('statusForm_' + appointmentId);
    currentForm = form;
    
    let message = 'Are you sure you want to update the status?';
    if (newValue === 'completed') {
        message = 'Are you sure you want to mark this appointment as completed? This action will finalize the work.';
    }
    
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmModal').classList.remove('hidden');
}

document.getElementById('confirmBtn').addEventListener('click', function() {
    if (currentForm) {
        currentForm.submit();
    }
    document.getElementById('confirmModal').classList.add('hidden');
});

document.getElementById('cancelBtn').addEventListener('click', function() {
    if (currentForm) {
        const select = currentForm.querySelector('select[name="work_status"]');
        select.value = previousValue;
    }
    document.getElementById('confirmModal').classList.add('hidden');
    currentForm = null;
});

// Auto-hide toasts
document.addEventListener('DOMContentLoaded', function() {
    const toastSuccess = document.getElementById('successToast');
    if (toastSuccess) setTimeout(() => {
        toastSuccess.style.opacity = '0';
        setTimeout(() => toastSuccess.remove(), 500);
    }, 3000);

    const toastError = document.getElementById('errorToast');
    if (toastError) setTimeout(() => {
        toastError.style.opacity = '0';
        setTimeout(() => toastError.remove(), 500);
    }, 3000);
});
</script>
@endsection