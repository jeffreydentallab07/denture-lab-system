@extends('layouts.app')

@section('page-title', 'Edit Billing')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.billing.show', $billing->id) }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium mb-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Billing Details
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Edit Billing Information</h1>
            <p class="text-gray-600 mt-1">Billing ID: <span class="font-semibold">BILL-{{ str_pad($billing->id, 5, '0',
                    STR_PAD_LEFT) }}</span></p>
        </div>

        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md">
            <div class="flex items-start">
                <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="font-semibold mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc pl-5 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white">Billing Details</h2>
            </div>

            <form action="{{ route('admin.billing.update', $billing->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">

                    <!-- Current Information Display -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Current Information
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Appointment</p>
                                <p class="font-semibold text-gray-800">APT-{{ str_pad($billing->appointment_id, 5, '0',
                                    STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Patient</p>
                                <p class="font-semibold text-gray-800">{{
                                    $billing->appointment->caseOrder->patient->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Clinic</p>
                                <p class="font-semibold text-gray-800">{{
                                    $billing->appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Material Cost</p>
                                <p class="font-semibold text-green-600">₱{{
                                    number_format($billing->appointment->total_material_cost, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" name="appointment_id" value="{{ $billing->appointment_id }}">
                    <input type="hidden" id="materialCost" value="{{ $billing->appointment->total_material_cost }}">

                    <!-- Additional Details -->
                    <div class="space-y-2">
                        <label for="additional_details" class="block font-semibold text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Additional Fee Details
                        </label>
                        <textarea name="additional_details" id="additional_details" rows="2"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                            placeholder="e.g., Labor, transportation, rush fee...">{{ old('additional_details', $billing->additional_details) }}</textarea>
                        <p class="text-xs text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Describe what the additional charges are for
                        </p>
                    </div>

                    <!-- Additional Amount -->
                    <div class="space-y-2">
                        <label for="additional_amount" class="block font-semibold text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2 fill-green-600" viewBox="0 0 36 36" version="1.1"
                                preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <title>peso-solid</title>
                                    <path d="M14.18,13.8V16h9.45a5.26,5.26,0,0,0,.08-.89,4.72,4.72,0,0,0-.2-1.31Z"
                                        class="clr-i-solid clr-i-solid-path-1"></path>
                                    <path d="M14.18,19.7h5.19a4.28,4.28,0,0,0,3.5-1.9H14.18Z"
                                        class="clr-i-solid clr-i-solid-path-2"></path>
                                    <path d="M19.37,10.51H14.18V12h8.37A4.21,4.21,0,0,0,19.37,10.51Z"
                                        class="clr-i-solid clr-i-solid-path-3"></path>
                                    <path
                                        d="M17.67,2a16,16,0,1,0,16,16A16,16,0,0,0,17.67,2Zm10.5,15.8H25.7a6.87,6.87,0,0,1-6.33,4.4H14.18v6.54a1.25,1.25,0,1,1-2.5,0V17.8H8.76a.9.9,0,1,1,0-1.8h2.92V13.8H8.76a.9.9,0,1,1,0-1.8h2.92V9.26A1.25,1.25,0,0,1,12.93,8h6.44a6.84,6.84,0,0,1,6.15,4h2.65a.9.9,0,0,1,0,1.8H26.09a6.91,6.91,0,0,1,.12,1.3,6.8,6.8,0,0,1-.06.9h2a.9.9,0,0,1,0,1.8Z"
                                        class="clr-i-solid clr-i-solid-path-4"></path>
                                    <rect x="0" y="0" width="36" height="36" fill-opacity="0"></rect>
                                </g>
                            </svg>
                            Additional Amount
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-600 font-semibold text-lg">₱</span>
                            <input type="number" step="0.01" id="additional_amount" name="additional_amount"
                                value="{{ old('additional_amount', $billing->additional_amount) }}"
                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200 text-lg font-semibold"
                                required oninput="calculateTotal()">
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Previous: ₱{{ number_format($billing->additional_amount, 2) }}
                        </p>
                    </div>

                    <!-- Total Amount Display -->
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Material Cost:</span>
                            <span class="font-semibold text-gray-800">₱{{
                                number_format($billing->appointment->total_material_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Additional Amount:</span>
                            <span class="font-semibold text-gray-800" id="displayAdditionalAmount">₱{{
                                number_format($billing->additional_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t-2 border-blue-300">
                            <span class="text-lg font-bold text-gray-800">Total Amount:</span>
                            <span class="text-xl font-bold text-blue-600" id="displayTotalAmount">₱{{
                                number_format($billing->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="space-y-2">
                        <label for="payment_status" class="block font-semibold text-gray-700">
                            Payment Status <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_status" id="payment_status" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200">
                            <option value="unpaid" {{ old('payment_status', $billing->payment_status) == 'unpaid' ?
                                'selected' : '' }}>Unpaid</option>
                            <option value="partial" {{ old('payment_status', $billing->payment_status) == 'partial' ?
                                'selected' : '' }}>Partial Payment</option>
                            <option value="paid" {{ old('payment_status', $billing->payment_status) == 'paid' ?
                                'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div class="space-y-2">
                        <label for="payment_method" class="block font-semibold text-gray-700">
                            Payment Method
                        </label>
                        <select name="payment_method" id="payment_method"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200">
                            <option value="">-- Select Method --</option>
                            <option value="Cash" {{ old('payment_method', $billing->payment_method) == 'Cash' ?
                                'selected' : '' }}>Cash</option>
                            <option value="Bank Transfer" {{ old('payment_method', $billing->payment_method) == 'Bank
                                Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Credit Card" {{ old('payment_method', $billing->payment_method) == 'Credit
                                Card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="Debit Card" {{ old('payment_method', $billing->payment_method) == 'Debit
                                Card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="GCash" {{ old('payment_method', $billing->payment_method) == 'GCash' ?
                                'selected' : '' }}>GCash</option>
                            <option value="PayMaya" {{ old('payment_method', $billing->payment_method) == 'PayMaya' ?
                                'selected' : '' }}>PayMaya</option>
                            <option value="Check" {{ old('payment_method', $billing->payment_method) == 'Check' ?
                                'selected' : '' }}>Check</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-2">
                        <label for="notes" class="block font-semibold text-gray-700">
                            Notes
                        </label>
                        <textarea name="notes" id="notes" rows="4"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                            placeholder="Additional notes or payment terms...">{{ old('notes', $billing->notes) }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.billing.show', $billing->id) }}"
                            class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200 flex items-center shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-200 flex items-center shadow-md hover:shadow-lg font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Billing
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function calculateTotal() {
    const materialCost = parseFloat(document.getElementById('materialCost').value) || 0;
    const additionalAmount = parseFloat(document.getElementById('additional_amount').value) || 0;
    const totalAmount = materialCost + additionalAmount;
    
    document.getElementById('displayAdditionalAmount').textContent = '₱' + additionalAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('displayTotalAmount').textContent = '₱' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
@endsection