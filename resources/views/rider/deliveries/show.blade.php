@extends('layouts.rider')

@section('title', 'Delivery Details')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <a href="{{ route('rider.deliveries.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to My Deliveries
        </a>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <!-- Delivery Details Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-orange-600 to-orange-500 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">Delivery Details</h1>
                        <p class="text-orange-100 mt-2">DEL-{{ str_pad($delivery->delivery_id, 5, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    <span
                        class="px-4 py-2 text-sm rounded-full font-semibold
                        {{ $delivery->delivery_status === 'ready to deliver' ? 'bg-yellow-500 text-white' : 
                           ($delivery->delivery_status === 'in transit' ? 'bg-blue-500 text-white' : 'bg-green-500 text-white') }}">
                        {{ ucfirst(str_replace('_', ' ', $delivery->delivery_status)) }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Delivery Information -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Delivery Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Delivery ID</p>
                            <p class="text-lg font-semibold text-gray-800">DEL-{{ str_pad($delivery->delivery_id, 5,
                                '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Case Number</p>
                            <p class="text-lg font-semibold text-gray-800">CASE-{{
                                str_pad($delivery->appointment->case_order_id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Delivery Date</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $delivery->delivery_date ?
                                $delivery->delivery_date->format('M d, Y') : 'Not set' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-lg font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ',
                                $delivery->delivery_status)) }}</p>
                        </div>
                        @if($delivery->delivered_at)
                        <div>
                            <p class="text-sm text-gray-500">Delivered At</p>
                            <p class="text-lg font-semibold text-green-600">{{ $delivery->delivered_at->format('M d, Y
                                h:i A') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($delivery->notes)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-500 mb-2">Delivery Notes</p>
                        <p class="text-gray-700 bg-gray-50 p-3 rounded whitespace-pre-line">{{ $delivery->notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- Delivery Destination -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Delivery Destination</h2>
                    <div class="bg-orange-50 rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <img src="{{ $delivery->appointment->caseOrder->clinic->profile_photo ? asset('storage/' . $delivery->appointment->caseOrder->clinic->profile_photo) : asset('images/default-clinic.png') }}"
                                alt="{{ $delivery->appointment->caseOrder->clinic->clinic_name }}"
                                class="w-16 h-16 rounded-full object-cover border-2">
                            <div class="flex-1">
                                <p class="text-lg font-bold text-gray-800">{{
                                    $delivery->appointment->caseOrder->clinic->clinic_name }}</p>
                                <div class="space-y-2 mt-3 text-sm text-gray-700">
                                    <p class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <strong>Address:</strong> {{ $delivery->appointment->caseOrder->clinic->address
                                        ?? 'N/A' }}
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                        <strong>Contact:</strong> {{
                                        $delivery->appointment->caseOrder->clinic->contact_number ?? 'N/A' }}
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <strong>Email:</strong> {{ $delivery->appointment->caseOrder->clinic->email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Case Information -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Case Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Case Number</p>
                            <p class="font-semibold text-gray-800">CASE-{{
                                str_pad($delivery->appointment->case_order_id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Patient Name</p>
                            <p class="font-semibold text-gray-800">{{ $delivery->appointment->caseOrder->patient->name
                                ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Case Type</p>
                            <p class="font-semibold text-gray-800">{{ $delivery->appointment->caseOrder->case_type }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Work Completed</p>
                            <p class="font-semibold text-gray-800">{{ $delivery->appointment->updated_at->format('M d,
                                Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($delivery->delivery_status !== 'delivered')
                <div class="pt-4 border-t">
                    @if($delivery->delivery_status === 'ready to deliver')
                    <button onclick="updateDeliveryStatus({{ $delivery->delivery_id }}, 'in transit')"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                        Start Delivery
                    </button>
                    @elseif($delivery->delivery_status === 'in transit')
                    <button onclick="updateDeliveryStatus({{ $delivery->delivery_id }}, 'delivered')"
                        class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Mark as Delivered
                    </button>
                    @endif
                </div>
                @else
                <div class="pt-4 border-t">
                    <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 mx-auto text-green-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-lg font-bold text-green-700">Delivery Completed</p>
                        <p class="text-sm text-green-600 mt-1">This delivery has been successfully completed</p>
                    </div>
                </div>
                @endif
            </div>
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
</script>
@endsection