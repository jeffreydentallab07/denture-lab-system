@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">

        <a href="{{ route('admin.billing.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Billings
        </a>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Billing Info Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-500 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold">BILL-{{ str_pad($billing->id, 5, '0', STR_PAD_LEFT) }}
                                </h1>
                                <p class="text-green-100 mt-2">₱{{ number_format($billing->total_amount, 2) }}</p>
                            </div>
                            <span
                                class="px-4 py-2 text-sm rounded-full font-semibold
                                {{ $billing->payment_status === 'paid' ? 'bg-white text-green-600' : 
                                   ($billing->payment_status === 'partial' ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white') }}">
                                {{ ucfirst($billing->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Billing Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Billing ID</p>
                                <p class="text-lg font-semibold text-gray-800">BILL-{{ str_pad($billing->id, 5, '0',
                                    STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Appointment</p>
                                <a href="{{ route('admin.appointments.show', $billing->appointment_id) }}"
                                    class="text-lg font-semibold text-blue-600 hover:underline">
                                    APT-{{ str_pad($billing->appointment_id, 5, '0', STR_PAD_LEFT) }}
                                </a>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Amount</p>
                                <p class="text-lg font-semibold text-green-600">₱{{
                                    number_format($billing->total_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Payment Status</p>
                                <p class="text-lg font-semibold text-gray-800">{{ ucfirst($billing->payment_status) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Payment Method</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $billing->payment_method ?? 'Not
                                    specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date Created</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $billing->created_at->format('M d, Y')
                                    }}</p>
                            </div>
                        </div>

                        @if($billing->notes)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Notes</p>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded whitespace-pre-line">{{ $billing->notes }}
                            </p>
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
                                <img src="{{ $billing->appointment->caseOrder->clinic->profile_photo ? asset('storage/' . $billing->appointment->caseOrder->clinic->profile_photo) : asset('images/default-clinic.png') }}"
                                    alt="{{ $billing->appointment->caseOrder->clinic->clinic_name }}"
                                    class="w-12 h-12 rounded-full object-cover border-2">
                                <div>
                                    <p class="font-semibold text-gray-800">{{
                                        $billing->appointment->caseOrder->clinic->clinic_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $billing->appointment->caseOrder->clinic->email
                                        }}</p>
                                    <p class="text-sm text-gray-600">{{
                                        $billing->appointment->caseOrder->clinic->contact_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 font-medium mb-2">Patient</p>
                            <div>
                                <p class="font-semibold text-gray-800">{{
                                    $billing->appointment->caseOrder->patient->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">Contact: {{
                                    $billing->appointment->caseOrder->patient->contact_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Case Order Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Case Order Details</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Case Number</p>
                            <a href="{{ route('admin.case-orders.show', $billing->appointment->case_order_id) }}"
                                class="font-semibold text-blue-600 hover:underline">
                                CASE-{{ str_pad($billing->appointment->case_order_id, 5, '0', STR_PAD_LEFT) }}
                            </a>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Case Type</p>
                            <p class="font-semibold text-gray-800">{{ $billing->appointment->caseOrder->case_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Technician</p>
                            <p class="font-semibold text-gray-800">{{ $billing->appointment->technician->name ?? 'N/A'
                                }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Completion Date</p>
                            <p class="font-semibold text-gray-800">{{ $billing->appointment->updated_at->format('M d,
                                Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Materials Used -->
                @if($billing->appointment->materialUsages->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Materials Used</h2>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left p-3 text-sm font-semibold text-gray-700">Material</th>
                                    <th class="text-center p-3 text-sm font-semibold text-gray-700">Qty</th>
                                    <th class="text-right p-3 text-sm font-semibold text-gray-700">Unit Price</th>
                                    <th class="text-right p-3 text-sm font-semibold text-gray-700">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($billing->appointment->materialUsages as $usage)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 text-sm text-gray-800">{{ $usage->material->material_name }}</td>
                                    <td class="p-3 text-sm text-center text-gray-800">{{ $usage->quantity_used }}</td>
                                    <td class="p-3 text-sm text-right text-gray-800">₱{{
                                        number_format($usage->material->price, 2) }}</td>
                                    <td class="p-3 text-sm text-right font-medium text-gray-800">₱{{
                                        number_format($usage->quantity_used * $usage->material->price, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 border-t-2">
                                <tr>
                                    <td colspan="3" class="p-3 text-sm font-bold text-gray-800 text-right">Total
                                        Material Cost:</td>
                                    <td class="p-3 text-sm font-bold text-green-600 text-right">₱{{
                                        number_format($billing->appointment->total_material_cost, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <!-- Print Invoice -->
                        <a href="{{ route('admin.billing.invoice', $billing->id) }}" target="_blank"
                            class="flex items-center justify-center gap-2 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                            Print Invoice
                        </a>

                        <!-- Edit Billing -->
                        <a href="{{ route('admin.billing.edit', $billing->id) }}"
                            class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition">
                            Edit Billing
                        </a>

                        <!-- View Appointment -->
                        <a href="{{ route('admin.appointments.show', $billing->appointment_id) }}"
                            class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            View Appointment
                        </a>

                        <!-- View Case Order -->
                        <a href="{{ route('admin.case-orders.show', $billing->appointment->case_order_id) }}"
                            class="block w-full bg-indigo-600 text-white text-center py-2 rounded-lg hover:bg-indigo-700 transition">
                            View Case Order
                        </a>

                        <!-- Create Delivery (if completed and no delivery exists) -->
                        @if($billing->appointment->work_status === 'completed' && !$billing->appointment->delivery)
                        <a href="{{ route('admin.delivery.create', ['appointment' => $billing->appointment_id]) }}"
                            class="block w-full bg-orange-600 text-white text-center py-2 rounded-lg hover:bg-orange-700 transition">
                            Create Delivery
                        </a>
                        @endif

                        <!-- View Delivery (if exists) -->
                        @if($billing->appointment->delivery)
                        <a href="{{ route('admin.delivery.show', $billing->appointment->delivery->delivery_id) }}"
                            class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            View Delivery
                        </a>
                        @endif

                        <!-- Delete -->
                        <button onclick="confirmDelete()"
                            class="block w-full bg-red-600 text-white text-center py-2 rounded-lg hover:bg-red-700 transition">
                            Delete Billing
                        </button>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Payment Summary</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-2 border-b">
                            <span class="text-sm text-gray-600">Material Cost</span>
                            <span class="font-semibold text-gray-800">₱{{
                                number_format($billing->appointment->total_material_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b">
                            <span class="text-sm text-gray-600">Labor & Others</span>
                            <span class="font-semibold text-gray-800">₱{{ number_format($billing->total_amount -
                                $billing->appointment->total_material_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="font-bold text-gray-800">Total Amount</span>
                            <span class="font-bold text-green-600 text-lg">₱{{ number_format($billing->total_amount, 2)
                                }}</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Timeline</h3>

                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Billing Created</p>
                                <p class="text-xs text-gray-500">{{ $billing->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        @if($billing->updated_at != $billing->created_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Last Updated</p>
                                <p class="text-xs text-gray-500">{{ $billing->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
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
        <p class="text-gray-600 mb-6">Are you sure you want to delete this billing record? This action cannot be undone.
        </p>

        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Cancel
            </button>
            <form action="{{ route('admin.billing.destroy', $billing->id) }}" method="POST" class="inline">
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