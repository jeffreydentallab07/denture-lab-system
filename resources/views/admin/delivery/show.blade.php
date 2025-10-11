@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">

        <a href="{{ route('admin.delivery.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Deliveries
        </a>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Delivery Info Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-600 to-orange-500 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold">DEL-{{ str_pad($delivery->delivery_id, 5, '0',
                                    STR_PAD_LEFT) }}</h1>
                                <p class="text-orange-100 mt-2">{{ $delivery->delivery_date ?
                                    $delivery->delivery_date->format('M d, Y') : 'Date TBD' }}</p>
                            </div>
                            <span
                                class="px-4 py-2 text-sm rounded-full font-semibold
                                {{ $delivery->delivery_status === 'delivered' ? 'bg-green-500 text-white' : 
                                   ($delivery->delivery_status === 'in transit' ? 'bg-blue-500 text-white' : 'bg-yellow-500 text-white') }}">
                                {{ ucfirst(str_replace('_', ' ', $delivery->delivery_status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Delivery Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Delivery ID</p>
                                <p class="text-lg font-semibold text-gray-800">DEL-{{ str_pad($delivery->delivery_id, 5,
                                    '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Appointment</p>
                                <a href="{{ route('admin.appointments.show', $delivery->appointment_id) }}"
                                    class="text-lg font-semibold text-blue-600 hover:underline">
                                    APT-{{ str_pad($delivery->appointment_id, 5, '0', STR_PAD_LEFT) }}
                                </a>
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
                                <p class="text-lg font-semibold text-green-600">{{ $delivery->delivered_at->format('M d,
                                    Y h:i A') }}</p>
                            </div>
                            @endif
                        </div>

                        @if($delivery->notes)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Delivery Notes</p>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded whitespace-pre-line">{{ $delivery->notes }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Clinic Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Delivery Destination</h2>

                    <div class="flex items-start gap-4 mb-4">
                        <img src="{{ $delivery->appointment->caseOrder->clinic->profile_photo ? asset('storage/' . $delivery->appointment->caseOrder->clinic->profile_photo) : asset('images/default-clinic.png') }}"
                            alt="{{ $delivery->appointment->caseOrder->clinic->clinic_name }}"
                            class="w-16 h-16 rounded-full object-cover border-2">
                        <div class="flex-1">
                            <p class="text-lg font-semibold text-gray-800">{{
                                $delivery->appointment->caseOrder->clinic->clinic_name }}</p>
                            <p class="text-sm text-gray-600">{{ $delivery->appointment->caseOrder->clinic->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Delivery Address</p>
                                <p class="text-gray-800">{{ $delivery->appointment->caseOrder->clinic->address ?? 'N/A'
                                    }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Contact Number</p>
                                <p class="text-gray-800">{{ $delivery->appointment->caseOrder->clinic->contact_number ??
                                    'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rider Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Assigned Rider</h2>

                    <div class="flex items-center gap-4">
                        <img src="{{ $delivery->rider->photo ? asset('storage/' . $delivery->rider->photo) : asset('images/default-avatar.png') }}"
                            alt="{{ $delivery->rider->name }}" class="w-16 h-16 rounded-full object-cover border-2">
                        <div>
                            <p class="text-lg font-semibold text-gray-800">{{ $delivery->rider->name }}</p>
                            <p class="text-sm text-gray-600">{{ $delivery->rider->email }}</p>
                            <p class="text-sm text-gray-600">{{ $delivery->rider->contact_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Case Order & Billing Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Case Order & Billing</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Case Number</p>
                            <a href="{{ route('admin.case-orders.show', $delivery->appointment->case_order_id) }}"
                                class="font-semibold text-blue-600 hover:underline">
                                CASE-{{ str_pad($delivery->appointment->case_order_id, 5, '0', STR_PAD_LEFT) }}
                            </a>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Patient</p>
                            <p class="font-semibold text-gray-800">{{ $delivery->appointment->caseOrder->patient->name
                                ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Case Type</p>
                            <p class="font-semibold text-gray-800">{{ $delivery->appointment->caseOrder->case_type }}
                            </p>
                        </div>
                        @if($delivery->appointment->billing)
                        <div>
                            <p class="text-sm text-gray-500">Total Amount</p>
                            <p class="font-semibold text-green-600">₱{{
                                number_format($delivery->appointment->billing->total_amount, 2) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <!-- Edit Delivery -->
                        @if($delivery->delivery_status !== 'delivered')
                        <a href="{{ route('admin.delivery.edit', $delivery->delivery_id) }}"
                            class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition">
                            Edit Delivery
                        </a>
                        @endif

                        <!-- View Appointment -->
                        <a href="{{ route('admin.appointments.show', $delivery->appointment_id) }}"
                            class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            View Appointment
                        </a>

                        <!-- View Billing -->
                        @if($delivery->appointment->billing)
                        <a href="{{ route('admin.billing.show', $delivery->appointment->billing->id) }}"
                            class="block w-full bg-green-600 text-white text-center py-2 rounded-lg hover:bg-green-700 transition">
                            View Billing
                        </a>
                        @endif

                        <!-- Delete -->
                        <button onclick="confirmDelete()"
                            class="block w-full bg-red-600 text-white text-center py-2 rounded-lg hover:bg-red-700 transition">
                            Delete Delivery
                        </button>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Delivery Timeline</h3>

                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Delivery Created</p>
                                <p class="text-xs text-gray-500">{{ $delivery->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        @if($delivery->delivery_status === 'in transit' || $delivery->delivery_status === 'delivered')
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">In Transit</p>
                                <p class="text-xs text-gray-500">{{ $delivery->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($delivery->delivery_status === 'delivered')
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Delivered</p>
                                <p class="text-xs text-gray-500">{{ $delivery->delivered_at ?
                                    $delivery->delivered_at->format('M d, Y h:i A') : $delivery->updated_at->format('M
                                    d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Status Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Status</h3>

                    <div class="text-center">
                        <div
                            class="inline-block px-6 py-3 rounded-full text-sm font-semibold mb-2
                            {{ $delivery->delivery_status === 'delivered' ? 'bg-green-100 text-green-800' : 
                               ($delivery->delivery_status === 'in transit' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $delivery->delivery_status)) }}
                        </div>

                        @if($delivery->delivery_status === 'delivered')
                        <p class="text-sm text-gray-600 mt-2">✓ Successfully delivered</p>
                        @elseif($delivery->delivery_status === 'in transit')
                        <p class="text-sm text-gray-600 mt-2">Rider is on the way</p>
                        @else
                        <p class="text-sm text-gray-600 mt-2">Awaiting pickup by rider</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Confirm Delete</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this delivery record? This action cannot be
            undone.</p>

        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Cancel
            </button>
            <form action="{{ route('admin.delivery.destroy', $delivery->delivery_id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection