@extends('layouts.clinic')

@section('page-title', 'Create Case Order')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex items-center">
            <a href="{{ route('clinic.case-orders.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                ← Back
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Create New Case Order</h1>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8">

                <form action="{{ route('clinic.case-orders.store') }}" method="POST">
                    @csrf

                    <!-- Patient Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Patient <span class="text-red-500">*</span>
                        </label>
                        <select name="patient_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient->patient_id }}" {{ old('patient_id')==$patient->patient_id ?
                                'selected' : '' }}>
                                {{ $patient->name }} - {{ $patient->email }}
                            </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">
                            Don't see the patient? <a href="{{ route('clinic.patients.create') }}"
                                class="text-blue-600 hover:underline">Add new patient</a>
                        </p>
                    </div>

                    <!-- Dentist Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Dentist <span class="text-red-500">*</span>
                        </label>
                        <select name="dentist_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Dentist</option>
                            @foreach($dentists as $dentist)
                            <option value="{{ $dentist->dentist_id }}" {{ old('dentist_id')==$dentist->dentist_id ?
                                'selected' : '' }}>
                                {{ $dentist->name }} - {{ $dentist->email }}
                            </option>
                            @endforeach
                        </select>
                        @error('dentist_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">
                            Don't see the dentist? <a href="{{ route('clinic.dentists.create') }}"
                                class="text-blue-600 hover:underline">Add new dentist</a>
                        </p>
                    </div>

                    <!-- Case Type -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Case Type <span class="text-red-500">*</span>
                        </label>
                        <select name="case_type" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Case Type</option>
                            <option value="denture" {{ old('case_type')=='denture' ? 'selected' : '' }}>Denture</option>
                            <option value="crown" {{ old('case_type')=='crown' ? 'selected' : '' }}>Crown</option>
                            <option value="bridge" {{ old('case_type')=='bridge' ? 'selected' : '' }}>Bridge</option>
                            <option value="implant" {{ old('case_type')=='implant' ? 'selected' : '' }}>Implant</option>
                            <option value="orthodontics" {{ old('case_type')=='orthodontics' ? 'selected' : '' }}>
                                Orthodontics</option>
                        </select>
                        @error('case_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notes / Special Instructions
                        </label>
                        <textarea name="notes" rows="5"
                            placeholder="Enter any special instructions or notes for this case..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Maximum 1000 characters</p>
                    </div>

                    <!-- Info Box -->
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    After creating the case order, the admin will schedule a pickup and assign a
                                    technician to work on it.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('clinic.case-orders.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                            Create Case Order
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection