@extends('layouts.clinic')

@section('title', 'Appointments')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">My Appointments</h1>
            <p class="text-gray-600">Track the progress of your case orders</p>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Total Appointments</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $appointments->total() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Pending</h3>
                <p class="text-3xl font-bold text-yellow-600 mt-2">
                    {{ $appointments->where('work_status', 'pending')->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">In Progress</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $appointments->where('work_status', 'in-progress')->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Completed</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    {{ $appointments->where('work_status', 'completed')->count() }}
                </p>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="flex border-b">
                <button onclick="filterAppointments('all')"
                    class="filter-tab px-6 py-3 font-medium text-blue-600 border-b-2 border-blue-600">
                    All
                </button>
                <button onclick="filterAppointments('pending')"
                    class="filter-tab px-6 py-3 font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent">
                    Pending
                </button>
                <button onclick="filterAppointments('in-progress')"
                    class="filter-tab px-6 py-3 font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent">
                    In Progress
                </button>
                <button onclick="filterAppointments('completed')"
                    class="filter-tab px-6 py-3 font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent">
                    Completed
                </button>
            </div>
        </div>

        <!-- Appointments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($appointments as $appointment)
            <div class="appointment-card bg-white rounded-lg shadow hover:shadow-lg transition"
                data-status="{{ $appointment->work_status }}">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-xs text-gray-500">Appointment #</p>
                            <p class="text-lg font-bold text-gray-800">APT-{{ str_pad($appointment->appointment_id, 5,
                                '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <span
                            class="px-3 py-1 text-xs rounded-full font-medium
                            {{ $appointment->work_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                               ($appointment->work_status === 'in-progress' ? 'bg-blue-100 text-blue-800' : 
                               ($appointment->work_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                            {{ ucfirst(str_replace('-', ' ', $appointment->work_status)) }}
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="space-y-3 mb-4">
                        <div>
                            <p class="text-xs text-gray-500">Case Number</p>
                            <p class="font-semibold text-gray-800">CASE-{{ str_pad($appointment->case_order_id, 5, '0',
                                STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Patient</p>
                            <p class="font-semibold text-gray-800">{{ $appointment->caseOrder->patient->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Case Type</p>
                            <p class="font-medium text-gray-700">{{ $appointment->caseOrder->case_type }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Technician</p>
                            <p class="font-medium text-gray-700">{{ $appointment->technician->name ?? 'Not assigned' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>{{ $appointment->schedule_datetime->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>

                    <!-- Progress Indicator -->
                    @if($appointment->work_status === 'in-progress')
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>Work in progress</span>
                            <span>{{ $appointment->materialUsages->count() }} materials used</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 50%"></div>
                        </div>
                    </div>
                    @endif

                    @if($appointment->work_status === 'completed')
                    <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-green-700 font-medium">✓ Work Completed</span>
                            @if($appointment->billing)
                            <span class="text-xs text-green-600">Billing Created</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Action Button -->
                    <div class="border-t pt-4">
                        <a href="{{ route('clinic.appointments.show', $appointment->appointment_id) }}"
                            class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <p class="text-gray-500">No appointments found</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($appointments->hasPages())
        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
        @endif
    </div>
</div>

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
    event.target.classList.remove('border-transparent');
    
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