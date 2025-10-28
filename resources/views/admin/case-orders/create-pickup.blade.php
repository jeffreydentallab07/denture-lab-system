@extends('layouts.app')

@section('page-title', 'Create Pickup')

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
                Create Pickup for CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
            </h1>
        </div>

        <!-- Info Alert -->
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
                        Assign a rider to pick up the case order from the clinic.
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
                        <p class="text-gray-500">Clinic</p>
                        <p class="font-medium">{{ $caseOrder->clinic->clinic_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Contact</p>
                        <p class="font-medium">{{ $caseOrder->clinic->contact_number }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500">Pickup Address</p>
                        <p class="font-medium">📍 {{ $caseOrder->clinic->address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8">

                <form action="{{ route('admin.case-orders.store-pickup', $caseOrder->co_id) }}" method="POST">
                    @csrf

                    <!-- Rider Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Assign Rider <span class="text-red-500">*</span>
                        </label>
                        <select name="rider_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Rider</option>
                            @foreach($riders as $rider)
                            <option value="{{ $rider->id }}" {{ old('rider_id')==$rider->id ? 'selected' : '' }}>
                                {{ $rider->name }} - {{ $rider->contact_number }}
                            </option>
                            @endforeach
                        </select>
                        @error('rider_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pickup Date -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pickup Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="pickup_date" required min="{{ date('Y-m-d') }}"
                            value="{{ old('pickup_date', date('Y-m-d', strtotime('+1 day'))) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('pickup_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notes / Instructions
                        </label>
                        <textarea name="notes" rows="3" placeholder="Any special instructions for the rider..."
                            class="w-full p-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        @error('notes')
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
                            Create Pickup
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection