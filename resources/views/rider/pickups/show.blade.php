@extends('layouts.rider')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <a href="{{ route('rider.pickups.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to My Pickups
        </a>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <!-- Pickup Details Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">Pickup Details</h1>
                        <p class="text-blue-100 mt-2">CASE-{{ str_pad($pickup->case_order_id, 5, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    <span class="px-4 py-2 text-sm rounded-full font-semibold
                        {{ $pickup->status === 'pending' ? 'bg-yellow-500 text-white' : 'bg-green-500 text-white' }}">
                        {{ $pickup->status === 'pending' ? 'Pending' : 'Picked Up' }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Clinic Information -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Pickup Location</h2>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <img src="{{ $pickup->caseOrder->clinic->profile_photo ? asset('storage/' . $pickup->caseOrder->clinic->profile_photo) : asset('images/default-clinic.png') }}"
                                alt="{{ $pickup->caseOrder->clinic->clinic_name }}"
                                class="w-16 h-16 rounded-full object-cover border-2">
                            <div class="flex-1">
                                <p class="text-lg font-bold text-gray-800">{{ $pickup->caseOrder->clinic->clinic_name }}
                                </p>
                                <div class="space-y-2 mt-3 text-sm text-gray-700">
                                    <p class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <strong>Address:</strong> {{ $pickup->pickup_address }}
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                        <strong>Contact:</strong> {{ $pickup->caseOrder->clinic->contact_number ?? 'N/A'
                                        }}
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <strong>Email:</strong> {{ $pickup->caseOrder->clinic->email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pickup Schedule -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Schedule</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Pickup Date</p>
                            <p class="text-lg font-bold text-gray-800">{{ $pickup->pickup_date->format('l, F d, Y') }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Pickup Time</p>
                            <p class="text-lg font-bold text-gray-800">{{ $pickup->pickup_time ?? 'Flexible' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Case Information -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Case Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Case Number</p>
                            <p class="font-semibold text-gray-800">CASE-{{ str_pad($pickup->case_order_id, 5, '0',
                                STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Patient Name</p>
                            <p class="font-semibold text-gray-800">{{ $pickup->caseOrder->patient->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Case Type</p>
                            <p class="font-semibold text-gray-800">{{ $pickup->caseOrder->case_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Priority</p>
                            <span
                                class="px-2 py-1 text-xs rounded-full font-medium
                                {{ $pickup->caseOrder->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                   ($pickup->caseOrder->priority === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($pickup->caseOrder->priority) }}
                            </span>
                        </div>
                    </div>

                    @if($pickup->notes)
                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                        <p class="text-sm font-semibold text-gray-700 mb-1">Special Instructions:</p>
                        <p class="text-sm text-gray-700">{{ $pickup->notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                @if($pickup->status === 'pending')
                <div class="pt-4 border-t">
                    <button onclick="updateStatus({{ $pickup->pickup_id }}, 'picked-up')"
                        class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Mark as Picked Up
                    </button>
                </div>
                @else
                <div class="pt-4 border-t">
                    <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 mx-auto text-green-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-lg font-bold text-green-700">Pickup Completed</p>
                        <p class="text-sm text-green-600 mt-1">This case has been successfully picked up</p>
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
</script>
@endsection