@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">

        <a href="{{ route('admin.appointments.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Appointments
        </a>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Appointment Info Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-900 to-blue-700 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold">APT-{{ str_pad($appointment->appointment_id, 5, '0',
                                    STR_PAD_LEFT) }}</h1>
                                <p class="text-blue-100 mt-2">Est. Completion: {{
                                    $appointment->estimated_date->format('M d, Y') }}</p>
                            </div>
                            <span class="px-4 py-2 text-sm rounded-full font-semibold
    {{ $appointment->work_status === 'pending' ? 'bg-yellow-500 text-white' : 
       ($appointment->work_status === 'in-progress' ? 'bg-blue-500 text-white' : 
       ($appointment->work_status === 'completed' ? 'bg-green-500 text-white' : 
       ($appointment->work_status === 'cancelled' ? 'bg-red-500 text-white' : 'bg-gray-500 text-white'))) }}">
                                {{ ucfirst(str_replace('-', ' ', $appointment->work_status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Appointment Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Appointment ID</p>
                                <p class="text-lg font-semibold text-gray-800">APT-{{
                                    str_pad($appointment->appointment_id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Case Order</p>
                                <a href="{{ route('admin.case-orders.show', $appointment->case_order_id) }}"
                                    class="text-lg font-semibold text-blue-600 hover:underline">
                                    CASE-{{ str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) }}
                                </a>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Estimated Completion Date</p>
                                <p class="text-lg font-semibold text-gray-800">{{
                                    $appointment->estimated_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Work Status</p>
                                <p class="text-lg font-semibold text-gray-800">{{ ucfirst(str_replace('-', ' ',
                                    $appointment->work_status)) }}</p>
                            </div>
                        </div>

                        @if($appointment->purpose)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Purpose / Work Description</p>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded whitespace-pre-line">{{ $appointment->purpose
                                }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Clinic & Patient Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Clinic & Patient Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 font-medium mb-2">Clinic</p>
                            <div class="flex items-center gap-3">
                                <img src="{{ $appointment->caseOrder->clinic->profile_photo ? asset('storage/' . $appointment->caseOrder->clinic->profile_photo) : asset('images/default-clinic.png') }}"
                                    alt="{{ $appointment->caseOrder->clinic->clinic_name }}"
                                    class="w-12 h-12 rounded-full object-cover border-2">
                                <div>
                                    <p class="font-semibold text-gray-800">{{
                                        $appointment->caseOrder->clinic->clinic_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $appointment->caseOrder->clinic->contact_number
                                        ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 font-medium mb-2">Patient</p>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $appointment->caseOrder->patient->name ??
                                    'N/A' }}</p>
                                <p class="text-sm text-gray-600">{{ $appointment->caseOrder->patient->contact_number ??
                                    'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technician Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Assigned Technician</h2>

                    <div class="flex items-center gap-4">
                        <img src="{{ $appointment->technician->photo ? asset('storage/' . $appointment->technician->photo) : asset('images/default-avatar.png') }}"
                            alt="{{ $appointment->technician->name }}"
                            class="w-16 h-16 rounded-full object-cover border-2">
                        <div>
                            <p class="text-lg font-semibold text-gray-800">{{ $appointment->technician->name }}</p>
                            <p class="text-sm text-gray-600">{{ $appointment->technician->email }}</p>
                            <p class="text-sm text-gray-600">{{ $appointment->technician->contact_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Materials Used -->
                @if($appointment->materialUsages->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Materials Used</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Material
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit
                                        Price</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total
                                        Cost</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Notes
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($appointment->materialUsages as $usage)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{
                                        $usage->material->material_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $usage->quantity_used }} {{
                                        $usage->material->unit }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">₱{{
                                        number_format($usage->material->price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-800">₱{{
                                        number_format($usage->total_cost, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $usage->notes ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm font-bold text-gray-800 text-right">Total
                                        Material Cost:</td>
                                    <td class="px-4 py-3 text-sm font-bold text-blue-600">₱{{
                                        number_format($appointment->total_material_cost, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Delivery Information -->
                @if($appointment->delivery)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Delivery Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Delivery Status</p>
                            <span
                                class="inline-block mt-1 px-3 py-1 text-xs rounded-full font-medium
                                {{ $appointment->delivery->delivery_status === 'ready to deliver' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($appointment->delivery->delivery_status === 'in transit' ? 'bg-blue-100 text-blue-800' : 
                                   ($appointment->delivery->delivery_status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($appointment->delivery->delivery_status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Assigned Rider</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $appointment->delivery->rider->name ??
                                'N/A'
                                }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Delivery Date</p>
                            <p class="text-lg font-semibold text-gray-800">{{
                                $appointment->delivery->delivery_date->format('M d, Y') }}</p>
                        </div>
                        @if($appointment->delivery->delivered_at)
                        <div>
                            <p class="text-sm text-gray-500">Delivered At</p>
                            <p class="text-lg font-semibold text-gray-800">{{
                                $appointment->delivery->delivered_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.case-orders.show', $appointment->case_order_id) }}"
                            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            View Full Case Order Details
                        </a>
                    </div>
                </div>
                @endif

                <!-- Billing Information -->
                @if($appointment->billing)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Billing Information</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Material Cost:</span>
                            <span class="font-semibold">₱{{ number_format($appointment->billing->material_cost, 2)
                                }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Labor Cost:</span>
                            <span class="font-semibold">₱{{ number_format($appointment->billing->labor_cost, 2)
                                }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivery Fee:</span>
                            <span class="font-semibold">₱{{ number_format($appointment->billing->delivery_fee, 2)
                                }}</span>
                        </div>
                        @if($appointment->billing->additional_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Additional ({{ $appointment->billing->additional_details
                                }}):</span>
                            <span class="font-semibold">₱{{ number_format($appointment->billing->additional_amount, 2)
                                }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-lg font-bold text-gray-800">Total Amount:</span>
                            <span class="text-lg font-bold text-blue-600">₱{{
                                number_format($appointment->billing->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Status:</span>
                            <span
                                class="px-3 py-1 text-xs rounded-full font-medium
                                {{ $appointment->billing->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($appointment->billing->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Actions Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Actions</h2>

                    <div class="space-y-3">
                        @if($appointment->work_status !== 'cancelled')
                        <button onclick="openRescheduleModal()"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                            📅 Reschedule
                        </button>

                        <button onclick="confirmCancel()"
                            class="w-full px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 text-sm font-medium">
                            ⚠️ Cancel Appointment
                        </button>
                        @endif

                        <a href="{{ route('admin.appointments.edit', $appointment->appointment_id) }}"
                            class="block w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium text-center">
                            ✏️ Edit Details
                        </a>

                        <button onclick="confirmDelete()"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium">
                            🗑️ Delete
                        </button>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Quick Info</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Created</span>
                            <span class="font-semibold text-gray-800">{{ $appointment->created_at->format('M d, Y')
                                }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Last Updated</span>
                            <span class="font-semibold text-gray-800">{{ $appointment->updated_at->diffForHumans()
                                }}</span>
                        </div>
                        @php
                        use Carbon\Carbon;

                        $estimated = Carbon::parse($appointment->estimated_date);
                        $days = now()->diffInDays($estimated, false);
                        @endphp

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Days Until Completion</span>
                            <span class="font-semibold text-gray-800">
                                @if($estimated->isFuture())
                                {{ abs((int) round($days)) }} {{ abs((int) round($days)) === 1 ? 'day' : 'days' }}
                                @elseif($estimated->isToday())
                                <span class="text-blue-600">Today</span>
                                @else
                                @php $overdue = abs((int) round($days)); @endphp
                                <span class="text-red-500">
                                    Overdue by {{ $overdue }} {{ $overdue === 1 ? 'day' : 'days' }}
                                </span>
                                @endif
                            </span>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- Reschedule Appointment Modal -->
<div id="rescheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Reschedule Appointment</h3>

        <form action="{{ route('admin.appointments.reschedule', $appointment->appointment_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">New Estimated Completion Date</label>
                <input type="date" name="estimated_date" min="{{ date('Y-m-d') }}"
                    value="{{ $appointment->estimated_date->format('Y-m-d') }}" required
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rescheduling (Optional)</label>
                <textarea name="reschedule_reason" rows="3"
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none"
                    placeholder="Enter reason for rescheduling..."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRescheduleModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Reschedule
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Cancel Appointment</h3>
        <p class="text-gray-600 mb-4">
            Are you sure you want to cancel this appointment?
            <br><br>
            <strong>This will:</strong>
        </p>
        <ul class="text-sm text-gray-600 mb-4 space-y-1 list-disc list-inside">
            <li>Set appointment status to "Cancelled"</li>
            <li>Set case order status back to "For Appointment"</li>
            <li>Notify the assigned technician</li>
            <li>Allow creation of a new appointment for this case</li>
        </ul>

        <form action="{{ route('admin.appointments.cancel', $appointment->appointment_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Reason</label>
                <textarea name="cancellation_reason" rows="3" required
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none"
                    placeholder="Enter reason for cancellation..."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeCancelModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Go Back
                </button>
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                    Confirm Cancellation
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Confirm Delete</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this appointment? This action cannot be undone.
        </p>

        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Cancel
            </button>
            <form action="{{ route('admin.appointments.destroy', $appointment->appointment_id) }}" method="POST"
                class="inline">
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
    // Reschedule Modal
function openRescheduleModal() {
    document.getElementById('rescheduleModal').classList.remove('hidden');
}

function closeRescheduleModal() {
    document.getElementById('rescheduleModal').classList.add('hidden');
}

// Cancel Modal
function confirmCancel() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

// Delete Modal
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modals on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRescheduleModal();
        closeCancelModal();
        closeDeleteModal();
    }
});
</script>
@endsection