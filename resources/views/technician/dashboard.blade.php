@extends('layouts.technician')

@section('title', 'Technician Dashboard')

@section('content')
<div class="fixed top-4 right-4 z-50 space-y-2">
    @if(session('success'))
    <div id="successToast"
        class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-down">
        <span class="font-medium text-sm">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-white font-bold">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div id="errorToast"
        class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-down">
        <span class="font-medium text-sm">{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-white font-bold">&times;</button>
    </div>
    @endif
</div>

<div class="p-3 md:p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-4 md:mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Technician Dashboard</h1>
            <p class="text-sm md:text-base text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Total Assigned</h3>
                <p class="text-2xl md:text-3xl font-bold text-blue-600 mt-2">{{ $totalAssigned }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Pending Work</h3>
                <p class="text-2xl md:text-3xl font-bold text-yellow-600 mt-2">{{ $pendingWork }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Completed</h3>
                <p class="text-2xl md:text-3xl font-bold text-green-600 mt-2">{{ $completedWork }}</p>
            </div>
        </div>

        <!-- Today's Appointments -->
        @if($todayAppointments->count() > 0)
        <div class="bg-white rounded-lg shadow mb-4 md:mb-6">
            <div class="p-4 md:p-6 border-b">
                <h2 class="text-lg md:text-xl font-bold text-gray-800">📅 Today's Appointments</h2>
            </div>
            <div class="p-3 md:p-6">
                <div class="space-y-3 md:space-y-4">
                    @foreach($todayAppointments as $apt)
                    <div class="border-l-4 border-blue-500 pl-3 md:pl-4 py-3 bg-gray-50 rounded">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 text-sm md:text-base">{{
                                    $apt->caseOrder->clinic->clinic_name }}</p>
                                <p class="text-xs md:text-sm text-gray-600">APT-{{ str_pad($apt->appointment_id, 5, '0',
                                    STR_PAD_LEFT) }}</p>
                                <p class="text-xs md:text-sm text-gray-600">{{ $apt->schedule_datetime->format('h:i A')
                                    }}</p>
                            </div>
                            <a href="{{ route('technician.appointments.show', $apt->appointment_id) }}"
                                class="px-3 py-2 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 text-center">
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
            <div class="p-4 md:p-6 border-b">
                <h2 class="text-lg md:text-xl font-bold text-gray-800">My Appointments</h2>
            </div>

            @if($appointments->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <svg class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                <p class="text-sm">No appointments assigned to you.</p>
            </div>
            @else
            <!-- Mobile Card View -->
            <div class="block md:hidden">
                @foreach($appointments as $appointment)
                <div class="border-b p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">APT-{{ str_pad($appointment->appointment_id,
                                5, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-sm text-gray-700">{{ $appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-2 text-xs text-gray-600 mb-3">
                        <p><span class="font-medium">Case Type:</span> {{ $appointment->caseOrder->case_type ?? 'N/A' }}
                        </p>
                        <p><span class="font-medium">Schedule:</span> {{ $appointment->schedule_datetime->format('M d, Y
                            h:i A') }}</p>
                        <p><span class="font-medium">Materials:</span> {{ $appointment->materialUsages->count() }}
                            material(s)</p>
                    </div>

                    <form action="{{ route('technician.appointment.update', $appointment->appointment_id) }}"
                        method="POST" id="statusForm_{{ $appointment->appointment_id }}" class="mb-2">
                        @csrf
                        <label class="text-xs font-medium text-gray-700 block mb-1">Work Status</label>
                        <select name="work_status"
                            onchange="confirmStatusChange(this, {{ $appointment->appointment_id }})"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ $appointment->work_status == 'pending' ? 'selected' : ''
                                }}>Pending</option>
                            <option value="in-progress" {{ $appointment->work_status == 'in-progress' ? 'selected' : ''
                                }}>In Progress</option>
                            <option value="completed" {{ $appointment->work_status == 'completed' ? 'selected' : ''
                                }}>Completed</option>
                        </select>
                    </form>

                    <a href="{{ route('technician.appointments.show', $appointment->appointment_id) }}"
                        class="block w-full text-center px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                        View Details
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
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
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-4 md:p-6">
        <h2 class="text-base md:text-lg font-semibold text-gray-700 mb-3 md:mb-4">Confirm Status Change</h2>
        <p class="text-sm md:text-base text-gray-600 mb-4 md:mb-6" id="confirmMessage">Are you sure you want to update
            the status?</p>
        <div class="flex justify-end gap-3">
            <button id="cancelBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-sm">Cancel</button>
            <button id="confirmBtn"
                class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 text-sm">Confirm</button>
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
            return;
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