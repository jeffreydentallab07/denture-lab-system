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

            <form action="{{ route('admin.billing.update', $billing->id) }}" method="POST" id="editBillingForm">
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
                                <p class="text-gray-600">Payment Status</p>
                                <span
                                    class="px-2 py-1 text-xs rounded-full font-medium {{ $billing->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($billing->payment_status === 'partial' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($billing->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment ID (Read-only) -->
                    <div class="space-y-2">
                        <label class="block font-semibold text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Appointment ID
                        </label>
                        <div class="relative">
                            <input type="text" value="APT-{{ str_pad($billing->appointment_id, 5, '0', STR_PAD_LEFT) }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-600 font-semibold cursor-not-allowed"
                                readonly>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Appointment ID cannot be changed
                        </p>
                    </div>

                    <!-- Hidden field to preserve appointment_id -->
                    <input type="hidden" name="appointment_id" value="{{ $billing->appointment_id }}">

                    <!-- Total Amount Field -->
                    <div class="space-y-2">
                        <label for="total_amount" class="block font-semibold text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Total Amount
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-600 font-semibold text-lg">₱</span>
                            <input type="number" step="0.01" id="total_amount" name="total_amount"
                                value="{{ old('total_amount', $billing->total_amount) }}"
                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200 text-lg font-semibold"
                                required>
                        </div>
                        <p class="hidden text-red-600 text-sm mt-1 flex items-center" id="amountError">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Total Amount must be greater than 0.
                        </p>
                        <p class="text-xs text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Previous amount: ₱{{ number_format($billing->total_amount, 2) }}
                        </p>
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
    document.addEventListener("DOMContentLoaded", () => {
        const amountField = document.getElementById("total_amount");
        const amountError = document.getElementById("amountError");

        function validateAmount() {
            const value = parseFloat(amountField.value);
            if (isNaN(value) || value <= 0) {
                amountField.classList.add("border-red-500", "ring-2", "ring-red-200", "animate-shake");
                amountField.classList.remove("border-gray-300", "border-green-500");
                amountError.classList.remove("hidden");
            } else {
                amountField.classList.remove("border-red-500", "ring-red-200", "animate-shake");
                amountField.classList.add("border-green-500", "ring-2", "ring-green-200");
                amountError.classList.add("hidden");
            }
        }

        // Real-time validation
        amountField.addEventListener("input", validateAmount);

        // Format amount on blur
        amountField.addEventListener("blur", function() {
            if (this.value && !isNaN(parseFloat(this.value))) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });
</script>

<style>
    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        10%,
        30%,
        50%,
        70%,
        90% {
            transform: translateX(-6px);
        }

        20%,
        40%,
        60%,
        80% {
            transform: translateX(6px);
        }
    }

    .animate-shake {
        animation: shake 0.4s cubic-bezier(.36, .07, .19, .97) both;
    }

    /* Smooth transitions */
    input {
        transition: all 0.2s ease-in-out;
    }

    /* Number input styling */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        opacity: 1;
    }
</style>
@endsection