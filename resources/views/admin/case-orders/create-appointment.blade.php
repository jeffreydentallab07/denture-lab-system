@extends('layouts.app')

@section('page-title', 'Create Appointment')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex items-center">
            <a href="{{ route('admin.case-orders.show', $caseOrder->co_id) }}"
                class="mr-4 text-gray-600 hover:text-gray-900">
                ← Back
            </a>
            <h1 class="text-3xl font-bold text-gray-800">
                Create Appointment for CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
            </h1>
        </div>

        <!-- Info Alert -->
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        <strong>Pickup Completed!</strong> Now assign a technician to work on this case order.
                    </p>
                </div>
            </div>
        </div>

        <!-- Case Details -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Case Order Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Patient</p>
                        <p class="font-medium">{{ $caseOrder->patient->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Case Type</p>
                        <p class="font-medium">{{ ucfirst($caseOrder->case_type) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Submission Count</p>
                        <p class="font-medium">{{ $caseOrder->submission_count > 0 ? 'Revision #' .
                            ($caseOrder->submission_count + 1) : 'Initial Work' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8">

                <form action="{{ route('admin.case-orders.store-appointment', $caseOrder->co_id) }}" method="POST">
                    @csrf

                    <!-- Technician Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Assign Technician <span class="text-red-500">*</span>
                        </label>
                        <select name="technician_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Technician</option>
                            @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}" {{ old('technician_id')==$technician->id ? 'selected'
                                : '' }}>
                                {{ $technician->name }} - {{ $technician->contact_number }}
                            </option>
                            @endforeach
                        </select>
                        @error('technician_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Schedule DateTime -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Schedule Date & Time <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="schedule_datetime" required
                            min="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}" value="{{ old('schedule_datetime') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('schedule_datetime')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Purpose -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Purpose / Work Description
                        </label>
                        <textarea name="purpose" rows="3" placeholder="Describe the work to be done..."
                            class="w-full p-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('purpose', $caseOrder->submission_count > 0 ? 'Revision/Adjustment Work' : 'Initial Work') }}</textarea>
                        @error('purpose')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('admin.case-orders.show', $caseOrder->co_id) }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                            Create Appointment
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection