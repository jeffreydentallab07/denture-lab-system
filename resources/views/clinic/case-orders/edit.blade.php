@extends('layouts.clinic')

@section('page-title', 'Edit Case Order')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex items-center">
            <a href="{{ route('clinic.case-orders.show', $caseOrder->co_id) }}"
                class="mr-4 text-gray-600 hover:text-gray-900">
                ← Back
            </a>
            <h1 class="text-3xl font-bold text-gray-800">
                Edit Case Order: CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
            </h1>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8">

                <form action="{{ route('clinic.case-orders.update', $caseOrder->co_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Patient Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Patient <span class="text-red-500">*</span>
                        </label>
                        <select name="patient_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient->patient_id }}" {{ old('patient_id', $caseOrder->patient_id) ==
                                $patient->patient_id ? 'selected' : '' }}>
                                {{ $patient->name }} - {{ $patient->email }}
                            </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                            <option value="{{ $dentist->dentist_id }}" {{ old('dentist_id', $caseOrder->dentist_id) ==
                                $dentist->dentist_id ? 'selected' : '' }}>
                                {{ $dentist->name }} - {{ $dentist->email }}
                            </option>
                            @endforeach
                        </select>
                        @error('dentist_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Case Type -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Case Type <span class="text-red-500">*</span>
                        </label>
                        <select name="case_type" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Case Type</option>
                            <option value="denture" {{ old('case_type', $caseOrder->case_type) == 'denture' ? 'selected'
                                : '' }}>Denture</option>
                            <option value="crown" {{ old('case_type', $caseOrder->case_type) == 'crown' ? 'selected' :
                                '' }}>Crown</option>
                            <option value="bridge" {{ old('case_type', $caseOrder->case_type) == 'bridge' ? 'selected' :
                                '' }}>Bridge</option>
                            <option value="implant" {{ old('case_type', $caseOrder->case_type) == 'implant' ? 'selected'
                                : '' }}>Implant</option>
                            <option value="orthodontics" {{ old('case_type', $caseOrder->case_type) == 'orthodontics' ?
                                'selected' : '' }}>Orthodontics</option>
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
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $caseOrder->notes) }}</textarea>
                        @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Maximum 1000 characters</p>
                    </div>

                    <!-- Warning Box -->
                    @if($caseOrder->status === 'adjustment requested')
                    <div class="mb-6 bg-orange-50 border-l-4 border-orange-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-orange-700">
                                    You can update case details while waiting for the admin to schedule a new
                                    appointment for adjustments.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('clinic.case-orders.show', $caseOrder->co_id) }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                            Update Case Order
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection