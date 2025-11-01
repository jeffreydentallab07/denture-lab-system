@extends('layouts.app')

@section('page-title', 'Create Delivery')

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
                Create Delivery for CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
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
                        <strong>Work Completed!</strong> Now assign a rider to deliver the case order back to the
                        clinic.
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
                        <p class="text-gray-500">Delivery Address</p>
                        <p class="font-medium">📍 {{ $caseOrder->clinic->address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8">

                <form action="{{ route('admin.case-orders.store-delivery', $caseOrder->co_id) }}" method="POST">
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

                    <!-- Delivery Date -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Delivery Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="delivery_date" required min="{{ date('Y-m-d') }}"
                            value="{{ old('delivery_date', date('Y-m-d', strtotime('+1 day'))) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('delivery_date')
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
                            Create Delivery
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection