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
                                <p class="text-blue-100 mt-2">{{ $appointment->schedule_datetime->format('M d, Y h:i A')
                                    }}</p>
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
                                <p class="text-sm text-gray-500">Scheduled Date & Time</p>
                                <p class="text-lg font-semibold text-gray-800">{{
                                    $appointment->schedule_datetime->format('M d, Y h:i A') }}</p>
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
                                    <td class="px-4 py-3 text-sm font-semibold text-green-600">₱{{
                                        number_format($usage->total_cost, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $usage->notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm font-bold text-gray-800 text-right">Total
                                        Material Cost:</td>
                                    <td class="px-4 py-3 text-sm font-bold text-green-600">₱{{
                                        number_format($appointment->total_material_cost, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Materials Used</h2>
                    <p class="text-gray-500 text-center py-4">No materials have been used yet.</p>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <!-- Reschedule Appointment -->
                        <button onclick="openRescheduleModal()"
                            class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition">
                            Reschedule Appointment
                        </button>

                        <!-- View Case Order -->
                        <a href="{{ route('admin.case-orders.show', $appointment->case_order_id) }}"
                            class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            View Case Order
                        </a>

                        <!-- Cancel Appointment -->
                        @if($appointment->work_status !== 'completed' && $appointment->work_status !== 'cancelled')
                        <button onclick="confirmCancel()"
                            class="block w-full bg-orange-600 text-white text-center py-2 rounded-lg hover:bg-orange-700 transition">
                            Cancel Appointment
                        </button>
                        @endif

                        <!-- Delete Appointment -->
                        <button onclick="confirmDelete()"
                            class="block w-full bg-red-600 text-white text-center py-2 rounded-lg hover:bg-red-700 transition">
                            Delete Appointment
                        </button>

                        <!-- Create Billing (if completed and no billing exists) -->
                        @if($appointment->work_status === 'completed' && !$appointment->billing)
                        <a href="{{ route('admin.billing.create', ['appointment' => $appointment->appointment_id]) }}"
                            class="block w-full bg-green-600 text-white text-center py-2 rounded-lg hover:bg-green-700 transition">
                            Create Billing
                        </a>
                        @endif

                        <!-- View Billing (if exists) -->
                        @if($appointment->billing)
                        <a href="{{ route('admin.billing.show', $appointment->billing->id) }}"
                            class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            View Billing
                        </a>
                        @endif
                    </div>

                    <!-- Info Note -->
                    <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-500 text-xs">
                        <p class="text-blue-700">
                            <strong>Note:</strong> Only the assigned technician can update the work status and add
                            materials used.
                        </p>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Timeline</h3>

                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Appointment Created</p>
                                <p class="text-xs text-gray-500">{{ $appointment->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>

                        @if($appointment->work_status === 'in-progress')
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Work In Progress</p>
                                <p class="text-xs text-gray-500">{{ $appointment->updated_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($appointment->work_status === 'completed')
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Work Completed</p>
                                <p class="text-xs text-gray-500">{{ $appointment->updated_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Statistics</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Materials Used</span>
                            <span class="font-semibold text-gray-800">{{ $appointment->materialUsages->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Cost</span>
                            <span class="font-semibold text-green-600">₱{{
                                number_format($appointment->total_material_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Days Until Appointment</span>
                            <span class="font-semibold text-gray-800">
                                @if($appointment->schedule_datetime->isFuture())
                                {{ $appointment->schedule_datetime->diffInDays(now()) }} days
                                @else
                                <span class="text-red-500">Overdue</span>
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
                <label class="block text-sm font-medium text-gray-700 mb-2">New Date & Time</label>
                <input type="datetime-local" name="schedule_datetime" min="{{ date('Y-m-d\TH:i') }}"
                    value="{{ $appointment->schedule_datetime->format('Y-m-d\TH:i') }}" required
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