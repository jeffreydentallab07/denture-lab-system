@extends('layouts.rider')

@section('content')
<div class="p-3 md:p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-4 md:mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Pickups</h1>
            <p class="text-sm md:text-base text-gray-600">Manage all your pickup assignments</p>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Total Pickups</h3>
                <p class="text-xl md:text-3xl font-bold text-blue-600 mt-1 md:mt-2">{{ $totalPickups }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Pending</h3>
                <p class="text-xl md:text-3xl font-bold text-yellow-600 mt-1 md:mt-2">{{ $pendingPickups }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Completed</h3>
                <p class="text-xl md:text-3xl font-bold text-green-600 mt-1 md:mt-2">{{ $pickedUpCount }}</p>
            </div>
        </div>

        <!-- Today's Pickups -->
        @if($todayPickups->count() > 0)
        <div class="bg-white rounded-lg shadow mb-4 md:mb-6">
            <div class="p-4 md:p-6 border-b bg-gradient-to-r from-blue-50 to-blue-100">
                <h2 class="text-lg md:text-xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-base md:text-xl">Today's Pickups ({{ $todayPickups->count() }})</span>
                </h2>
                <p class="text-xs md:text-sm text-gray-600 mt-1">Priority pickups scheduled for today</p>
            </div>
            <div class="p-3 md:p-6">
                <div class="space-y-3 md:space-y-4">
                    @foreach($todayPickups as $pickup)
                    <div
                        class="border-l-4 {{ $pickup->status === 'pending' ? 'border-yellow-500 bg-yellow-50' : 'border-green-500 bg-green-50' }} pl-3 md:pl-4 py-3 md:py-4 rounded-r-lg shadow-sm hover:shadow-md transition">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-3">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="font-bold text-base md:text-lg text-gray-800">
                                        {{ $pickup->caseOrder->clinic->clinic_name }}
                                    </span>
                                    <span
                                        class="px-2 py-1 text-xs rounded-full font-medium
                                        {{ $pickup->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800' }}">
                                        {{ $pickup->status === 'pending' ? '⏳ Pending' : '✓ Picked Up' }}
                                    </span>
                                </div>
                                <p class="text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    <span class="text-blue-600">CASE-{{ str_pad($pickup->case_order_id, 5, '0',
                                        STR_PAD_LEFT) }}</span>
                                </p>
                                <div class="space-y-1 text-xs md:text-sm text-gray-600">
                                    <p class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="flex-1">{{ $pickup->pickup_address }}</span>
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Patient: {{ $pickup->caseOrder->patient->name ?? 'N/A' }}
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Time: {{ $pickup->pickup_time ?? 'Flexible' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-row md:flex-col gap-2 w-full md:w-auto">
                                @if($pickup->status === 'pending')
                                <button onclick="updateStatus({{ $pickup->pickup_id }}, 'picked-up')"
                                    class="flex-1 md:flex-none px-3 md:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs md:text-sm font-semibold transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Mark as Picked Up</span>
                                    <span class="sm:hidden">Pick Up</span>
                                </button>
                                <a href="{{ route('rider.pickups.show', $pickup->pickup_id) }}"
                                    class="flex-1 md:flex-none px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs md:text-sm font-semibold text-center transition">
                                    View Details
                                </a>
                                @else
                                <span
                                    class="w-full md:w-auto px-3 md:px-4 py-2 bg-green-100 text-green-700 rounded-lg text-xs md:text-sm font-medium text-center flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Completed
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Upcoming Pickups (Next 7 Days) -->
        @if($upcomingPickups->count() > 0)
        <div class="bg-white rounded-lg shadow mb-4 md:mb-6">
            <div class="p-4 md:p-6 border-b bg-gradient-to-r from-purple-50 to-purple-100">
                <h2 class="text-lg md:text-xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-base md:text-xl">Upcoming Pickups ({{ $upcomingPickups->count() }})</span>
                </h2>
                <p class="text-xs md:text-sm text-gray-600 mt-1">Scheduled for the next 7 days</p>
            </div>
            <div class="p-3 md:p-6">
                <div class="space-y-3 md:space-y-4">
                    @foreach($upcomingPickups as $pickup)
                    <div
                        class="border-l-4 border-purple-500 bg-purple-50 pl-3 md:pl-4 py-3 md:py-4 rounded-r-lg shadow-sm hover:shadow-md transition">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-3">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="font-bold text-base md:text-lg text-gray-800">
                                        {{ $pickup->caseOrder->clinic->clinic_name }}
                                    </span>
                                    <span
                                        class="px-2 py-1 text-xs bg-purple-200 text-purple-800 rounded-full font-medium">
                                        {{ $pickup->pickup_date->format('M d') }}
                                    </span>
                                </div>
                                <p class="text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    <span class="text-blue-600">CASE-{{ str_pad($pickup->case_order_id, 5, '0',
                                        STR_PAD_LEFT) }}</span>
                                </p>
                                <div class="space-y-1 text-xs md:text-sm text-gray-600">
                                    <p class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="flex-1">{{ $pickup->pickup_address }}</span>
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('rider.pickups.show', $pickup->pickup_id) }}"
                                class="w-full md:w-auto px-3 md:px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-xs md:text-sm font-semibold text-center transition">
                                View Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- All Pickups Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 md:p-6 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h2 class="text-lg md:text-xl font-bold text-gray-800">All Pickups History</h2>
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterPickups('all')"
                        class="flex-1 sm:flex-none px-3 md:px-4 py-2 text-xs md:text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600 filter-btn active transition">
                        All
                    </button>
                    <button onclick="filterPickups('pending')"
                        class="flex-1 sm:flex-none px-3 md:px-4 py-2 text-xs md:text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 filter-btn transition">
                        Pending
                    </button>
                    <button onclick="filterPickups('picked-up')"
                        class="flex-1 sm:flex-none px-3 md:px-4 py-2 text-xs md:text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 filter-btn transition">
                        Completed
                    </button>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="block md:hidden">
                @forelse($pickups as $pickup)
                <div class="border-b pickup-row p-4" data-status="{{ $pickup->status }}">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <p class="font-semibold text-blue-600 text-sm">CASE-{{ str_pad($pickup->case_order_id, 5,
                                '0', STR_PAD_LEFT) }}</p>
                            <p class="text-sm text-gray-800 font-medium">{{ $pickup->caseOrder->clinic->clinic_name }}
                            </p>
                        </div>
                        <span
                            class="px-2 py-1 text-xs rounded-full font-medium
                            {{ $pickup->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ $pickup->status === 'pending' ? 'Pending' : 'Picked Up' }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 mb-1">📍 {{ Str::limit($pickup->pickup_address, 50) }}</p>
                    <p class="text-xs text-gray-500 mb-3">📅 {{ $pickup->pickup_date->format('M d, Y') }}</p>

                    <div class="flex gap-2">
                        <a href="{{ route('rider.pickups.show', $pickup->pickup_id) }}"
                            class="flex-1 text-center px-3 py-2 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                            View Details
                        </a>
                        @if($pickup->status === 'pending')
                        <button onclick="updateStatus({{ $pickup->pickup_id }}, 'picked-up')"
                            class="flex-1 text-center px-3 py-2 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                            Complete
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <p class="text-sm">No pickups assigned yet.</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Case No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clinic</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pickup Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($pickups as $pickup)
                        <tr class="pickup-row hover:bg-gray-50" data-status="{{ $pickup->status }}">
                            <td class="px-6 py-4 text-sm font-semibold text-blue-600">
                                CASE-{{ str_pad($pickup->case_order_id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $pickup->caseOrder->clinic->clinic_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($pickup->pickup_address, 35) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $pickup->pickup_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs rounded-full font-medium
                                    {{ $pickup->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $pickup->status === 'pending' ? 'Pending' : 'Picked Up' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('rider.pickups.show', $pickup->pickup_id) }}"
                                        class="text-blue-600 hover:underline text-sm font-medium">
                                        View
                                    </a>
                                    @if($pickup->status === 'pending')
                                    <button onclick="updateStatus({{ $pickup->pickup_id }}, 'picked-up')"
                                        class="text-green-600 hover:underline text-sm font-medium">
                                        Mark Complete
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                                <p>No pickups assigned yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pickups->hasPages())
            <div class="p-4 md:p-6 border-t">
                {{ $pickups->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Hidden form for status updates -->
<form id="statusUpdateForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" id="statusInput">
</form>

<script>
    function updateStatus(pickupId, status) {
        if (confirm('Confirm that you have picked up this case from the clinic?')) {
            const form = document.getElementById('statusUpdateForm');
            form.action = `/rider/pickups/${pickupId}/update-status`;
            document.getElementById('statusInput').value = status;
            form.submit();
        }
    }

    function filterPickups(status) {
        const rows = document.querySelectorAll('.pickup-row');
        const buttons = document.querySelectorAll('.filter-btn');
        
        // Update button states
        buttons.forEach(btn => {
            btn.classList.remove('bg-blue-500', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        });
        event.target.classList.remove('bg-gray-200', 'text-gray-700');
        event.target.classList.add('bg-blue-500', 'text-white');
        
        // Filter rows
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection