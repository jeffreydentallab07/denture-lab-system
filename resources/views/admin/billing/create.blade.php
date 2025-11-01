@extends('layouts.app')

@section('page-title', 'Create Billing')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <a href="{{ route('admin.billing.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Billings
        </a>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Billing</h1>

            @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.billing.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Appointment Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Select Completed Appointment <span class="text-red-500">*</span>
                    </label>

                    @if($appointment)
                    <!-- Pre-selected appointment -->
                    <div class="border-2 border-blue-500 rounded-lg p-4 bg-blue-50">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="font-semibold text-gray-800">APT-{{ str_pad($appointment->appointment_id, 5,
                                    '0', STR_PAD_LEFT) }}</p>
                                <p class="text-sm text-gray-600">CASE-{{ str_pad($appointment->case_order_id, 5, '0',
                                    STR_PAD_LEFT) }}</p>
                            </div>
                            <span class="px-3 py-1 bg-green-600 text-white rounded-full text-xs">Completed</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-500">Clinic:</p>
                                <p class="font-medium text-gray-800">{{ $appointment->caseOrder->clinic->clinic_name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Patient:</p>
                                <p class="font-medium text-gray-800">{{ $appointment->caseOrder->patient->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Case Type:</p>
                                <p class="font-medium text-gray-800">{{ $appointment->caseOrder->case_type }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Material Cost:</p>
                                <p class="font-medium text-green-600">₱{{
                                    number_format($appointment->total_material_cost, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="appointment_id" value="{{ $appointment->appointment_id }}">
                    <input type="hidden" id="materialCost" value="{{ $appointment->total_material_cost }}">
                    @else
                    <!-- Dropdown selection -->
                    <select name="appointment_id" id="appointmentSelect" required
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"
                        onchange="updateAppointmentDetails()">
                        <option value="">-- Select Completed Appointment --</option>
                        @foreach($completedAppointments as $apt)
                        <option value="{{ $apt->appointment_id }}"
                            data-clinic="{{ $apt->caseOrder->clinic->clinic_name }}"
                            data-patient="{{ $apt->caseOrder->patient->name }}"
                            data-case="{{ $apt->caseOrder->case_type }}" data-cost="{{ $apt->total_material_cost }}">
                            APT-{{ str_pad($apt->appointment_id, 5, '0', STR_PAD_LEFT) }} - {{
                            $apt->caseOrder->clinic->clinic_name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Appointment Preview -->
                    <div id="appointmentPreview" class="hidden mt-3 p-4 bg-gray-50 rounded-lg border">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-500">Clinic:</p>
                                <p class="font-medium text-gray-800" id="previewClinic"></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Patient:</p>
                                <p class="font-medium text-gray-800" id="previewPatient"></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Case Type:</p>
                                <p class="font-medium text-gray-800" id="previewCase"></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Material Cost:</p>
                                <p class="font-medium text-green-600" id="previewCost"></p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="materialCost" value="0">
                    @endif
                </div>

                <!-- Additional Details -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Additional Fee Details (Optional)
                    </label>
                    <textarea name="additional_details" rows="2"
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"
                        placeholder="e.g., Labor, transportation, rush fee, consultation...">{{ old('additional_details') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        Describe what the additional charges are for
                    </p>
                </div>

                <!-- Additional Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Additional Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">₱</span>
                        <input type="number" name="additional_amount" id="additionalAmount" step="0.01" min="0" required
                            value="{{ old('additional_amount', 0) }}"
                            class="w-full border-2 border-gray-300 rounded-lg p-3 pl-8 focus:border-blue-500 focus:outline-none"
                            placeholder="0.00" oninput="calculateTotal()">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Labor, services, and other additional charges
                    </p>
                </div>

                <!-- Total Amount (Auto-calculated) -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Material Cost:</span>
                        <span class="font-semibold text-gray-800" id="displayMaterialCost">₱0.00</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Additional Amount:</span>
                        <span class="font-semibold text-gray-800" id="displayAdditionalAmount">₱0.00</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t-2 border-blue-300">
                        <span class="text-lg font-bold text-gray-800">Total Amount:</span>
                        <span class="text-xl font-bold text-blue-600" id="displayTotalAmount">₱0.00</span>
                    </div>
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Status <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_status" required
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                        <option value="unpaid">Unpaid</option>
                        <option value="partial">Partial Payment</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Method (Optional)
                    </label>
                    <select name="payment_method"
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                        <option value="">-- Select Method --</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="GCash">GCash</option>
                        <option value="PayMaya">PayMaya</option>
                        <option value="Check">Check</option>
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="notes" rows="4"
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"
                        placeholder="Additional notes or payment terms...">{{ old('notes') }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.billing.index') }}"
                        class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Create Billing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateAppointmentDetails() {
    const select = document.getElementById('appointmentSelect');
    const option = select.options[select.selectedIndex];
    
    if (select.value) {
        const materialCost = parseFloat(option.dataset.cost);
        
        document.getElementById('previewClinic').textContent = option.dataset.clinic;
        document.getElementById('previewPatient').textContent = option.dataset.patient;
        document.getElementById('previewCase').textContent = option.dataset.case;
        document.getElementById('previewCost').textContent = '₱' + materialCost.toLocaleString('en-US', {minimumFractionDigits: 2});
        document.getElementById('appointmentPreview').classList.remove('hidden');
        document.getElementById('materialCost').value = materialCost;
        
        calculateTotal();
    } else {
        document.getElementById('appointmentPreview').classList.add('hidden');
        document.getElementById('materialCost').value = 0;
        calculateTotal();
    }
}

function calculateTotal() {
    const materialCost = parseFloat(document.getElementById('materialCost').value) || 0;
    const additionalAmount = parseFloat(document.getElementById('additionalAmount').value) || 0;
    const totalAmount = materialCost + additionalAmount;
    
    document.getElementById('displayMaterialCost').textContent = '₱' + materialCost.toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('displayAdditionalAmount').textContent = '₱' + additionalAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('displayTotalAmount').textContent = '₱' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
@endsection