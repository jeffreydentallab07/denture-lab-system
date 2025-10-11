@extends('layouts.app')

@section('page-title', 'Edit Billing')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-6">

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 border border-red-300 p-4 mb-6 rounded-lg">
        <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('billing.update', $billing->id) }}" method="POST" class="space-y-6" id="editBillingForm">
        @csrf
        @method('PUT')


        <div>
            <label for="appointment_id" class="block mb-2 font-semibold text-gray-700">Appointment ID</label>
            <input type="number" id="appointment_id" name="appointment_id"
                value="{{ old('appointment_id', $billing->appointment_id) }}"
                class="w-full px-3 py-2 border-b-2 border-gray-300 focus:outline-none focus:border-blue-500" required>
            <p class="hidden text-red-500 text-sm mt-1" id="appointmentError">
                Appointment ID must be a valid positive number.
            </p>
        </div>

        <div>
            <label for="total_amount" class="block mb-2 font-semibold text-gray-700">Total Amount (₱)</label>
            <input type="number" step="0.01" id="total_amount" name="total_amount"
                value="{{ old('total_amount', $billing->total_amount) }}"
                class="w-full px-3 py-2 border-b-2 border-gray-300 focus:outline-none focus:border-blue-500" required>
            <p class="hidden text-red-500 text-sm mt-1" id="amountError">
                Total Amount must be greater than 0.
            </p>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('admin.billing.index') }}"
                class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const appointmentField = document.getElementById("appointment_id");
        const amountField = document.getElementById("total_amount");

        const appointmentError = document.getElementById("appointmentError");
        const amountError = document.getElementById("amountError");

        function validateAppointment() {
            const value = parseInt(appointmentField.value, 10);
            if (isNaN(value) || value <= 0) {
                appointmentField.classList.add("border-red-500", "animate-shake");
                appointmentError.classList.remove("hidden");
                appointmentField.classList.remove("border-green-500");
            } else {
                appointmentField.classList.remove("border-red-500", "animate-shake");
                appointmentError.classList.add("hidden");
                appointmentField.classList.add("border-green-500");
            }
        }

        function validateAmount() {
            const value = parseFloat(amountField.value);
            if (isNaN(value) || value <= 0) {
                amountField.classList.add("border-red-500", "animate-shake");
                amountError.classList.remove("hidden");
                amountField.classList.remove("border-green-500");
            } else {
                amountField.classList.remove("border-red-500", "animate-shake");
                amountError.classList.add("hidden");
                amountField.classList.add("border-green-500");
            }
        }

        appointmentField.addEventListener("input", validateAppointment);
        amountField.addEventListener("input", validateAmount);
    });
</script>

<style>
    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        20%,
        60% {
            transform: translateX(-6px);
        }

        40%,
        80% {
            transform: translateX(6px);
        }
    }

    .animate-shake {
        animation: shake 0.3s;
    }
</style>
@endsection