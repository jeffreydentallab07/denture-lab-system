@extends('layouts.technician')

@section('title', 'Work History')

@section('content')
<div class="p-3 md:p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <div class="mb-4 md:mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Work History</h1>
            <p class="text-sm md:text-base text-gray-600">All your completed work records</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-4 md:mb-6">
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Total Completed</h3>
                <p class="text-xl md:text-3xl font-bold text-green-600 mt-1 md:mt-2">{{ $completedAppointments->total()
                    }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">This Month</h3>
                <p class="text-xl md:text-3xl font-bold text-blue-600 mt-1 md:mt-2">
                    {{ $completedAppointments->filter(function($apt) {
                    return $apt->updated_at->isCurrentMonth();
                    })->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">This Week</h3>
                <p class="text-xl md:text-3xl font-bold text-purple-600 mt-1 md:mt-2">
                    {{ $completedAppointments->filter(function($apt) {
                    return $apt->updated_at->isCurrentWeek();
                    })->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Materials Used</h3>
                <p class="text-xl md:text-3xl font-bold text-orange-600 mt-1 md:mt-2">
                    {{ $completedAppointments->sum(function($apt) {
                    return $apt->materialUsages->count();
                    }) }}
                </p>
            </div>
        </div>

        <!-- Completed Appointments List -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 md:p-6 border-b">
                <h2 class="text-lg md:text-xl font-bold text-gray-800">Completed Work</h2>
            </div>

            @if($completedAppointments->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($completedAppointments as $appointment)
                <div class="p-4 md:p-6 hover:bg-gray-50 transition">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            <!-- Header -->
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base md:text-lg font-bold text-gray-800">
                                        APT-{{ str_pad($appointment->appointment_id, 5, '0', STR_PAD_LEFT) }}
                                    </h3>
                                    <p class="text-xs md:text-sm text-gray-600">
                                        CASE-{{ str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-3">
                                <div>
                                    <p class="text-xs text-gray-500">Clinic</p>
                                    <p class="text-xs md:text-sm font-medium text-gray-800">
                                        {{ $appointment->caseOrder->clinic->clinic_name }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Case Type</p>
                                    <p class="text-xs md:text-sm font-medium text-gray-800">
                                        {{ $appointment->caseOrder->case_type }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Patient</p>
                                    <p class="text-xs md:text-sm font-medium text-gray-800">
                                        {{ $appointment->caseOrder->patient->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Materials Used -->
                            @if($appointment->materialUsages->count() > 0)
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-2">Materials Used:</p>
                                <div class="flex flex-wrap gap-1.5 md:gap-2">
                                    @foreach($appointment->materialUsages as $usage)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 md:px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L11 4.323V3a1 1 0 011-1zm-5 8.274l-.818 2.552c-.25.78.074 1.623.736 2.115A3.989 3.989 0 007 16a3.989 3.989 0 002.082-1.06c.662-.492.986-1.335.736-2.114L9 10.274V6a1 1 0 00-2 0v4.274z">
                                            </path>
                                        </svg>
                                        <span class="truncate">{{ $usage->material->material_name }} ({{
                                            $usage->quantity_used }} {{ $usage->material->unit }})</span>
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Footer Info -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-xs text-gray-500">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Scheduled: {{ $appointment->schedule_datetime->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Completed: {{ $appointment->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="w-full md:w-auto md:ml-4">
                            <a href="{{ route('technician.appointments.show', $appointment->appointment_id) }}"
                                class="inline-flex items-center justify-center gap-2 w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs md:text-sm">
                                <span>View Details</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-4 md:p-6 border-t">
                {{ $completedAppointments->links() }}
            </div>
            @else
            <div class="p-8 md:p-12 text-center">
                <svg class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <p class="text-gray-500 text-sm md:text-base">No completed work yet</p>
                <p class="text-xs md:text-sm text-gray-400 mt-2">Your completed appointments will appear here</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection