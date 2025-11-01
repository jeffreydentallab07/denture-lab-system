@extends('layouts.technician')

@section('title', 'My Appointments')

@section('content')
<div class="p-3 md:p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <div class="mb-4 md:mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">My Appointments</h1>
            <p class="text-sm md:text-base text-gray-600">All your assigned appointments</p>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow mb-4 md:mb-6">
            <div class="flex overflow-x-auto border-b scrollbar-hide">
                <button onclick="filterAppointments('all')"
                    class="filter-tab flex-shrink-0 px-4 md:px-6 py-3 font-medium text-sm md:text-base text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 active">
                    All
                </button>
                <button onclick="filterAppointments('pending')"
                    class="filter-tab flex-shrink-0 px-4 md:px-6 py-3 font-medium text-sm md:text-base text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600">
                    Pending
                </button>
                <button onclick="filterAppointments('in-progress')"
                    class="filter-tab flex-shrink-0 px-4 md:px-6 py-3 font-medium text-sm md:text-base text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600">
                    In Progress
                </button>
                <button onclick="filterAppointments('completed')"
                    class="filter-tab flex-shrink-0 px-4 md:px-6 py-3 font-medium text-sm md:text-base text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600">
                    Completed
                </button>
            </div>
        </div>

        <!-- Appointments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @forelse($appointments as $appointment)
            <div class="appointment-card bg-white rounded-lg shadow hover:shadow-lg transition"
                data-status="{{ $appointment->work_status }}">
                <div class="p-4 md:p-6">
                    <div class="flex justify-between items-start mb-3 md:mb-4">
                        <div>
                            <p class="text-xs text-gray-500">Appointment #</p>
                            <p class="text-base md:text-lg font-bold text-gray-800">
                                APT-{{ str_pad($appointment->appointment_id, 5, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                        <span
                            class="px-2 py-1 text-xs rounded-full font-medium
                            {{ $appointment->work_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                               ($appointment->work_status === 'in-progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst(str_replace('-', ' ', $appointment->work_status)) }}
                        </span>
                    </div>

                    <div class="space-y-2 mb-3 md:mb-4">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            <p class="text-xs md:text-sm text-gray-700 flex-1 break-words">
                                {{ $appointment->caseOrder->clinic->clinic_name }}
                            </p>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="text-xs md:text-sm text-gray-700 flex-1">
                                {{ $appointment->caseOrder->case_type }}
                            </p>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-xs md:text-sm text-gray-700 flex-1">
                                <span class="hidden md:inline">{{ $appointment->schedule_datetime->format('M d, Y h:i
                                    A') }}</span>
                                <span class="md:hidden">{{ $appointment->schedule_datetime->format('M d, Y') }}</span>
                            </p>
                        </div>
                    </div>

                    <div
                        class="border-t pt-3 md:pt-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <p class="text-xs text-gray-500">
                            {{ $appointment->materialUsages->count() }} material(s) used
                        </p>
                        <a href="{{ route('technician.appointments.show', $appointment->appointment_id) }}"
                            class="text-blue-600 hover:text-blue-700 text-xs md:text-sm font-medium inline-flex items-center gap-1">
                            <span>View Details</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 md:py-12">
                <svg class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 md:mb-4 text-gray-300" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                <p class="text-gray-500 text-sm md:text-base">No appointments found</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    /* Hide scrollbar but keep functionality */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    function filterAppointments(status) {
        const cards = document.querySelectorAll('.appointment-card');
        const tabs = document.querySelectorAll('.filter-tab');
        
        // Update tab styles
        tabs.forEach(tab => {
            tab.classList.remove('border-blue-600', 'text-blue-600');
            tab.classList.add('border-transparent', 'text-gray-600');
        });
        event.target.classList.add('border-blue-600', 'text-blue-600');
        event.target.classList.remove('border-transparent', 'text-gray-600');
        
        // Filter cards
        cards.forEach(card => {
            if (status === 'all' || card.dataset.status === status) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endsection