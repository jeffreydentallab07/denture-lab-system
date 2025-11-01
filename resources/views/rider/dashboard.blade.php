@extends('layouts.rider')

@section('content')
<div class="p-3 md:p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-4 md:mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Rider Dashboard</h1>
            <p class="text-sm md:text-base text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Total Pickups</h3>
                <p class="text-2xl md:text-3xl font-bold text-blue-600 mt-2">{{ $totalPickups }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Pending</h3>
                <p class="text-2xl md:text-3xl font-bold text-yellow-600 mt-2">{{ $pendingPickups }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Completed</h3>
                <p class="text-2xl md:text-3xl font-bold text-green-600 mt-2">{{ $pickedUpCount }}</p>
            </div>
        </div>

        <!-- Today's Pickups -->
        @if($todayPickups->count() > 0)
        <div class="bg-white rounded-lg shadow mb-4 md:mb-6">
            <div class="p-4 md:p-6 border-b">
                <h2 class="text-lg md:text-xl font-bold text-gray-800">📅 Today's Pickups</h2>
            </div>
            <div class="p-4 md:p-6">
                <div class="space-y-3 md:space-y-4">
                    @foreach($todayPickups as $pickup)
                    <div
                        class="border-l-4 {{ $pickup->status === 'pending' ? 'border-yellow-500' : ($pickup->status === 'picked-up' ? 'border-blue-500' : 'border-green-500') }} pl-3 md:pl-4 py-3 bg-gray-50 rounded">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 text-sm md:text-base">{{
                                    $pickup->caseOrder->clinic->clinic_name }}</p>
                                <p class="text-xs md:text-sm text-gray-600">CASE-{{ str_pad($pickup->case_order_id, 5,
                                    '0', STR_PAD_LEFT) }}</p>
                                <p class="text-xs md:text-sm text-gray-600">📍 {{ $pickup->pickup_address }}</p>
                                <p class="text-xs text-gray-500 mt-1">Patient: {{ $pickup->caseOrder->patient->name ??
                                    'N/A' }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span
                                    class="px-3 py-1 text-xs rounded-full font-medium text-center
                                    {{ $pickup->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $pickup->status === 'pending' ? 'Pending Pickup' : 'Picked Up & Delivered' }}
                                </span>

                                @if($pickup->status === 'pending')
                                <button onclick="updateStatus({{ $pickup->pickup_id }}, 'picked-up')"
                                    class="px-3 py-2 bg-green-600 text-white rounded text-xs hover:bg-green-700 whitespace-nowrap">
                                    Mark as Picked Up
                                </button>
                                @else
                                <span class="text-green-600 text-xs text-center">✓ Completed</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- All Pickups -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 md:p-6 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h2 class="text-lg md:text-xl font-bold text-gray-800">All Pickups</h2>
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterPickups('all')"
                        class="px-3 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300 filter-btn active">All</button>
                    <button onclick="filterPickups('pending')"
                        class="px-3 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300 filter-btn">Pending</button>
                    <button onclick="filterPickups('picked-up')"
                        class="px-3 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300 filter-btn">Picked Up</button>
                </div>
            </div>

            <!-- Mobile Cards View -->
            <div class="block md:hidden">
                @forelse($pickups as $pickup)
                <div class="border-b pickup-row p-4" data-status="{{ $pickup->status }}">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">CASE-{{ str_pad($pickup->case_order_id, 5,
                                '0', STR_PAD_LEFT) }}</p>
                            <p class="text-sm text-gray-700">{{ $pickup->caseOrder->clinic->clinic_name }}</p>
                        </div>
                        <span
                            class="px-2 py-1 text-xs rounded-full font-medium
                            {{ $pickup->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                               ($pickup->status === 'picked-up' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst(str_replace('-', ' ', $pickup->status)) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 mb-1">📍 {{ Str::limit($pickup->pickup_address, 40) }}</p>
                    <p class="text-xs text-gray-500 mb-3">{{ $pickup->pickup_date->format('M d, Y') }}</p>

                    @if($pickup->status === 'pending')
                    <button onclick="updateStatus({{ $pickup->pickup_id }}, 'picked-up')"
                        class="w-full px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-xs">
                        Mark as Picked Up
                    </button>
                    @else
                    <span class="text-green-600 text-xs font-medium">✓ Completed</span>
                    @endif
                </div>
                @empty
                <div class="p-8 text-center text-gray-500 text-sm">No pickups assigned yet.</div>
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
                        <tr class="pickup-row" data-status="{{ $pickup->status }}">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                CASE-{{ str_pad($pickup->case_order_id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $pickup->caseOrder->clinic->clinic_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($pickup->pickup_address, 30) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $pickup->pickup_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full font-medium
                                    {{ $pickup->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($pickup->status === 'picked-up' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst(str_replace('-', ' ', $pickup->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($pickup->status === 'pending')
                                <button onclick="updateStatus({{ $pickup->pickup_id }}, 'picked-up')"
                                    class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs">
                                    Mark as Picked Up
                                </button>
                                @else
                                <span class="text-green-600 text-xs font-medium">✓ Completed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">No pickups assigned yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
        buttons.forEach(btn => btn.classList.remove('active', 'bg-blue-500', 'text-white'));
        event.target.classList.add('active', 'bg-blue-500', 'text-white');
        
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

<style>
    .filter-btn.active {
        background-color: #3b82f6;
        color: white;
    }
</style>
@endsection