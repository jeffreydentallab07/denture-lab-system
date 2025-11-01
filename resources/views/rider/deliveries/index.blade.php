@extends('layouts.rider')

@section('title', 'My Deliveries')

@section('content')
<div class="p-3 md:p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-4 md:mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Deliveries</h1>
            <p class="text-sm md:text-base text-gray-600">Manage all your delivery assignments</p>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Total Deliveries</h3>
                <p class="text-xl md:text-3xl font-bold text-orange-600 mt-1 md:mt-2">{{ $totalDeliveries }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Ready to Deliver</h3>
                <p class="text-xl md:text-3xl font-bold text-yellow-600 mt-1 md:mt-2">{{ $pendingDeliveries }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">In Transit</h3>
                <p class="text-xl md:text-3xl font-bold text-blue-600 mt-1 md:mt-2">{{ $inTransit }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-3 md:p-6">
                <h3 class="text-gray-500 text-xs md:text-sm font-medium">Delivered</h3>
                <p class="text-xl md:text-3xl font-bold text-green-600 mt-1 md:mt-2">{{ $completedDeliveries }}</p>
            </div>
        </div>

        <!-- Today's Deliveries -->
        @if($todayDeliveries->count() > 0)
        <div class="bg-white rounded-lg shadow mb-4 md:mb-6">
            <div class="p-4 md:p-6 border-b bg-gradient-to-r from-orange-50 to-orange-100">
                <h2 class="text-lg md:text-xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-base md:text-xl">Today's Deliveries ({{ $todayDeliveries->count() }})</span>
                </h2>
                <p class="text-xs md:text-sm text-gray-600 mt-1">Priority deliveries scheduled for today</p>
            </div>
            <div class="p-3 md:p-6">
                <div class="space-y-3 md:space-y-4">
                    @foreach($todayDeliveries as $delivery)
                    <div class="border-l-4 
                        {{ $delivery->delivery_status === 'ready to deliver' ? 'border-yellow-500 bg-yellow-50' : 
                           ($delivery->delivery_status === 'in transit' ? 'border-blue-500 bg-blue-50' : 'border-green-500 bg-green-50') }} 
                        pl-3 md:pl-4 py-3 md:py-4 rounded-r-lg shadow-sm hover:shadow-md transition">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-3">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="font-bold text-base md:text-lg text-gray-800">
                                        {{ $delivery->appointment->caseOrder->clinic->clinic_name }}
                                    </span>
                                    <span
                                        class="px-2 py-1 text-xs rounded-full font-medium
                                        {{ $delivery->delivery_status === 'ready to deliver' ? 'bg-yellow-200 text-yellow-800' : 
                                           ($delivery->delivery_status === 'in transit' ? 'bg-blue-200 text-blue-800' : 'bg-green-200 text-green-800') }}">
                                        {{ $delivery->delivery_status === 'ready to deliver' ? '📦 Ready' :
                                        ($delivery->delivery_status === 'in transit' ? '🚚 In Transit' : '✓ Delivered')
                                        }}
                                    </span>
                                </div>
                                <p class="text-xs md:text-sm font-medium text-gray-700 mb-1">
                                    <span class="text-orange-600">DEL-{{ str_pad($delivery->delivery_id, 5, '0',
                                        STR_PAD_LEFT) }}</span> |
                                    CASE-{{ str_pad($delivery->appointment->case_order_id, 5, '0', STR_PAD_LEFT) }}
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
                                        <span class="flex-1">{{ $delivery->appointment->caseOrder->clinic->address ??
                                            'N/A' }}</span>
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Patient: {{ $delivery->appointment->caseOrder->patient->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-row md:flex-col gap-2 w-full md:w-auto">
                                @if($delivery->delivery_status === 'ready to deliver')
                                <button onclick="updateDeliveryStatus({{ $delivery->delivery_id }}, 'in transit')"
                                    class="flex-1 md:flex-none px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs md:text-sm font-semibold transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Start Delivery</span>
                                    <span class="sm:hidden">Start</span>
                                </button>
                                @elseif($delivery->delivery_status === 'in transit')
                                <button onclick="updateDeliveryStatus({{ $delivery->delivery_id }}, 'delivered')"
                                    class="flex-1 md:flex-none px-3 md:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs md:text-sm font-semibold transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Mark Delivered</span>
                                    <span class="sm:hidden">Deliver</span>
                                </button>
                                @endif
                                <a href="{{ route('rider.deliveries.show', $delivery->delivery_id) }}"
                                    class="flex-1 md:flex-none px-3 md:px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-xs md:text-sm font-semibold text-center transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- All Deliveries Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 md:p-6 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h2 class="text-lg md:text-xl font-bold text-gray-800">All Deliveries History</h2>
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterDeliveries('all')"
                        class="flex-1 sm:flex-none px-3 md:px-4 py-2 text-xs md:text-sm bg-orange-500 text-white rounded-lg hover:bg-orange-600 filter-btn active transition">
                        All
                    </button>
                    <button onclick="filterDeliveries('ready to deliver')"
                        class="flex-1 sm:flex-none px-3 md:px-4 py-2 text-xs md:text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 filter-btn transition">
                        Ready
                    </button>
                    <button onclick="filterDeliveries('in transit')"
                        class="flex-1 sm:flex-none px-3 md:px-4 py-2 text-xs md:text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 filter-btn transition">
                        In Transit
                    </button>
                    <button onclick="filterDeliveries('delivered')"
                        class="flex-1 sm:flex-none px-3 md:px-4 py-2 text-xs md:text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 filter-btn transition">
                        Delivered
                    </button>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="block md:hidden">
                @forelse($deliveries as $delivery)
                <div class="border-b delivery-row p-4" data-status="{{ $delivery->delivery_status }}">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <p class="font-semibold text-orange-600 text-sm">DEL-{{ str_pad($delivery->delivery_id, 5,
                                '0', STR_PAD_LEFT) }}</p>
                            <p class="text-sm text-gray-800 font-medium">{{
                                $delivery->appointment->caseOrder->clinic->clinic_name }}</p>
                        </div>
                        <span
                            class="px-2 py-1 text-xs rounded-full font-medium
                            {{ $delivery->delivery_status === 'ready to deliver' ? 'bg-yellow-100 text-yellow-800' : 
                               ($delivery->delivery_status === 'in transit' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ $delivery->delivery_status === 'ready to deliver' ? 'Ready' :
                            ($delivery->delivery_status === 'in transit' ? 'Transit' : 'Delivered') }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-600 mb-1">📍 {{
                        Str::limit($delivery->appointment->caseOrder->clinic->address ?? 'N/A', 50) }}</p>
                    <p class="text-xs text-gray-500 mb-3">📅 {{ $delivery->delivery_date ?
                        $delivery->delivery_date->format('M d, Y') : 'N/A' }}</p>

                    <div class="flex gap-2">
                        <a href="{{ route('rider.deliveries.show', $delivery->delivery_id) }}"
                            class="flex-1 text-center px-3 py-2 bg-gray-600 text-white rounded text-xs hover:bg-gray-700">
                            View Details
                        </a>
                        @if($delivery->delivery_status !== 'delivered')
                        <button
                            onclick="updateDeliveryStatus({{ $delivery->delivery_id }}, '{{ $delivery->delivery_status === 'ready to deliver' ? 'in transit' : 'delivered' }}')"
                            class="flex-1 text-center px-3 py-2 {{ $delivery->delivery_status === 'ready to deliver' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded text-xs">
                            {{ $delivery->delivery_status === 'ready to deliver' ? 'Start' : 'Complete' }}
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
                    <p class="text-sm">No deliveries assigned yet.</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery No.
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clinic</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($deliveries as $delivery)
                        <tr class="delivery-row hover:bg-gray-50" data-status="{{ $delivery->delivery_status }}">
                            <td class="px-6 py-4 text-sm font-semibold text-orange-600">
                                DEL-{{ str_pad($delivery->delivery_id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{
                                $delivery->appointment->caseOrder->clinic->clinic_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{
                                Str::limit($delivery->appointment->caseOrder->clinic->address ?? 'N/A', 35) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $delivery->delivery_date ?
                                $delivery->delivery_date->format('M d, Y') : 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs rounded-full font-medium
                                    {{ $delivery->delivery_status === 'ready to deliver' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($delivery->delivery_status === 'in transit' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $delivery->delivery_status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('rider.deliveries.show', $delivery->delivery_id) }}"
                                        class="text-blue-600 hover:underline text-sm font-medium">
                                        View
                                    </a>
                                    @if($delivery->delivery_status !== 'delivered')
                                    <button
                                        onclick="updateDeliveryStatus({{ $delivery->delivery_id }}, '{{ $delivery->delivery_status === 'ready to deliver' ? 'in transit' : 'delivered' }}')"
                                        class="text-green-600 hover:underline text-sm font-medium">
                                        {{ $delivery->delivery_status === 'ready to deliver' ? 'Start' : 'Complete' }}
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
                                <p>No deliveries assigned yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($deliveries->hasPages())
            <div class="p-4 md:p-6 border-t">
                {{ $deliveries->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Hidden form for status updates -->
<form id="statusUpdateForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="delivery_status" id="statusInput">
</form>

<script>
    function updateDeliveryStatus(deliveryId, status) {
        const messages = {
            'in transit': 'Confirm that you have started this delivery?',
            'delivered': 'Confirm that this delivery has been completed?'
        };
        
        if (confirm(messages[status] || 'Confirm status update?')) {
            const form = document.getElementById('statusUpdateForm');
            form.action = `/rider/deliveries/${deliveryId}/update-status`;
            document.getElementById('statusInput').value = status;
            form.submit();
        }
    }

    function filterDeliveries(status) {
        const rows = document.querySelectorAll('.delivery-row');
        const buttons = document.querySelectorAll('.filter-btn');
        
        // Update button states
        buttons.forEach(btn => {
            btn.classList.remove('bg-orange-500', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        });
        event.target.classList.remove('bg-gray-200', 'text-gray-700');
        event.target.classList.add('bg-orange-500', 'text-white');
        
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