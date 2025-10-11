@extends('layouts.clinic')

@section('content')
<main class="flex-1 p-6 overflow-y-auto">

    <div class="max-w-2xl mx-auto bg-white p-8 border-2 border-gray-300 rounded-md shadow-lg">

        <div class="flex items-center justify-center mb-6 space-x-4">
            <img id="clinic-logo" src="logoclinic.png" alt="Clinic Logo"
                class="w-24 h-24 object-contain rounded-full" />
            <div class="text-center">
                <h3 class="text-xl font-semibold text-gray-700 uppercase tracking-wide">
                    {{ Auth::guard('clinic')->user()->clinic_name }}
                </h3>
                <p class="text-sm text-gray-500">Job Order Form</p>
            </div>
        </div>
        <hr class="my-4 border-t-2 border-gray-300">

        <form action="{{ route('clinic.new-case-orders.store') }}" method="POST" class="space-y-6 text-gray-700"
            id="caseOrderForm">
            @csrf

            <div class="text-center">
                <p class="text-sm text-gray-500">Please fill out all the necessary details.</p>
            </div>

            <h2 class="text-lg font-semibold text-gray-700">Patient and Dentist Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="mb-4">
                    <label for="patient_name" class="block font-medium">Patient</label>
                    <input list="patients_list" id="patient_name" name="patient_name"
                        class="validate-input mt-1 w-full border-0 border-b-2 border-gray-400 focus:border-blue-600 focus:ring-0 px-0 py-2"
                        placeholder="Type patient name..." required pattern="^[A-Za-z\s\.\-]+$"
                        title="Only letters, spaces, dot, and dash allowed">

                    <datalist id="patients_list">
                        @foreach($patients as $patient)
                        <option value="{{ $patient->name}}"></option>
                        @endforeach
                    </datalist>
                </div>


                <div>
                    <label for="dentist_id" class="block font-medium mb-1">Assign Dentist</label>
                    <select id="dentist_id" name="dentist_id" required
                        class="validate-input w-full border-gray-300 rounded-md shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option disabled selected>-- Select Dentist --</option>
                        @foreach($dentists as $dentist)
                        <option value="{{ $dentist->dentist_id }}">{{ $dentist->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h2 class="text-lg font-semibold text-gray-700">Case Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label for="case_type" class="block font-medium mb-1">Case Type</label>
                    <select id="case_type" name="case_type" required
                        class="validate-input w-full border-gray-300 rounded-md shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="" disabled selected>-- Select Case Type --</option>
                        @foreach ($values as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="case_date" class="block font-medium mb-1">Case Date</label>
                    <input type="date" id="case_date" name="case_date"
                        class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                </div>


                <div>
                    <label for="case_time" class="block font-medium mb-1">Case Time</label>
                    <input type="time" id="case_time" name="case_time"
                        class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
                </div>
            </div>


            <div>
                <label for="notes" class="block font-medium mb-1">Notes / Special Instructions</label>
                <textarea id="notes" name="notes" rows="4"
                    class="validate-input w-full border-gray-300 rounded-md shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    placeholder="Optional notes..." pattern="^[A-Za-z0-9\s\.\,\-\(\)\/]*$"
                    title="Only letters, numbers, spaces, dot, comma, dash, parenthesis, and slash allowed"></textarea>
            </div>

            <script>
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                document.getElementById('case_date').value = `${year}-${month}-${day}`;

                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                document.getElementById('case_time').value = `${hours}:${minutes}`;
            </script>

            <div class="text-center">
                <button type="submit"
                    class="w-full md:w-auto bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:bg-blue-700 transition">
                    Submit Case Order
                </button>
            </div>
        </form>
    </div>
</main>


<style>
    .shake {
        animation: shake 0.3s;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        20%,
        60% {
            transform: translateX(-8px);
        }

        40%,
        80% {
            transform: translateX(8px);
        }
    }

    .invalid-input {
        border-color: red !important;
    }
</style>


<script>
    document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("caseOrderForm");

    form.addEventListener("submit", function(event) {
        let isValid = form.checkValidity();
        if (!isValid) {
            event.preventDefault();

            form.querySelectorAll(":invalid").forEach((field) => {
                field.classList.add("shake", "invalid-input");

                if (navigator.vibrate) {
                    navigator.vibrate(200);
                }

                setTimeout(() => {
                    field.classList.remove("shake");
                }, 400);
            });
        }
    });
});
</script>
@endsection