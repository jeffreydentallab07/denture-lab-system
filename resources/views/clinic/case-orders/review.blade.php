@extends('layouts.clinic')

@section('page-title', 'Review Case Order')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex items-center">
            <a href="{{ route('clinic.case-orders.show', $caseOrder->co_id) }}"
                class="mr-4 text-gray-600 hover:text-gray-900">
                ← Back
            </a>
            <h1 class="text-3xl font-bold text-gray-800">
                Review Case Order: CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
            </h1>
        </div>

        <!-- Status Alert -->
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
                        @if($caseOrder->submission_count == 1)
                        <strong>Initial Submission Delivered!</strong> Please review the work and either approve it or
                        request adjustments.
                        @else
                        <strong>Revision #{{ $caseOrder->submission_count }} Delivered!</strong> Please review the
                        changes and approve or request further adjustments.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Case Order Details -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Case Order Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Patient</p>
                        <p class="font-medium">{{ $caseOrder->patient->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dentist</p>
                        <p class="font-medium">{{ $caseOrder->dentist->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Case Type</p>
                        <p class="font-medium">{{ ucfirst($caseOrder->case_type) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Submission Count</p>
                        <p class="font-medium">{{ $caseOrder->submission_count }}</p>
                    </div>
                </div>

                @if($caseOrder->latest_delivery && $caseOrder->latest_delivery->delivered_at)
                <div class="mt-4">
                    <p class="text-sm text-gray-500">Delivered At</p>
                    <p class="font-medium">{{ $caseOrder->latest_delivery->delivered_at->format('F j, Y g:i A') }}</p>
                </div>
                @endif

                @if($caseOrder->notes)
                <div class="mt-4">
                    <p class="text-sm text-gray-500">Notes History</p>
                    <div class="mt-2 p-3 bg-gray-50 rounded text-sm whitespace-pre-line">{{ $caseOrder->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Submission History -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Submission History</h3>
                <div class="space-y-4">
                    @foreach($caseOrder->appointments()->whereIn('work_status', ['completed',
                    'delivered'])->orderBy('created_at', 'desc')->get() as $index => $appointment)
                    <div class="border-l-4 border-gray-300 pl-4">
                        <p class="font-medium">Submission #{{ $caseOrder->appointments()->whereIn('work_status',
                            ['completed', 'delivered'])->count() - $index }}</p>
                        <p class="text-sm text-gray-600">Technician: {{ $appointment->technician->name }}</p>
                        <p class="text-sm text-gray-600">Completed: {{ $appointment->updated_at->format('M j, Y g:i A')
                            }}</p>
                        @if($appointment->delivery)
                        <p class="text-sm text-gray-600">Delivered: {{ $appointment->delivery->delivered_at ?
                            $appointment->delivery->delivered_at->format('M j, Y g:i A') : 'Pending' }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Review Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Approve -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-semibold">Approve Work</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Mark this case order as completed. This action is final and the case will be closed.
                    </p>

                    <form action="{{ route('clinic.case-orders.approve', $caseOrder->co_id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Completion Notes
                                (Optional)</label>
                            <textarea name="completion_notes" rows="3"
                                class="w-full p-3 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500"
                                placeholder="Add any final comments..."></textarea>
                        </div>
                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
                            ✓ Approve & Complete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Request Adjustment -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="text-lg font-semibold">Request Adjustment</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Request changes or adjustments. This will create a new appointment for revision work.
                    </p>

                    <form action="{{ route('clinic.case-orders.request-adjustment', $caseOrder->co_id) }}"
                        method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Adjustment Details <span class="text-red-500">*</span>
                            </label>
                            <textarea name="adjustment_notes" rows="3" required
                                class="w-full p-3 border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="Describe what needs to be adjusted..."></textarea>
                            @error('adjustment_notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-lg transition">
                            ⚠ Request Adjustment
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection