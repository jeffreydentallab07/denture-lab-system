@extends('layouts.clinic')

@section('page-title', 'Case Order Details')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('clinic.case-orders.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    ← Back
                </a>
                <h1 class="text-3xl font-bold text-gray-800">
                    Case Order: CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
                </h1>
            </div>
            <div class="flex gap-2">
                @if($caseOrder->status === 'under review')
                <a href="{{ route('clinic.case-orders.review', $caseOrder->co_id) }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    📋 Review Submission
                </a>
                @endif

                @if(in_array($caseOrder->status, ['pending', 'adjustment requested']))
                <a href="{{ route('clinic.case-orders.edit', $caseOrder->co_id) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    ✏️ Edit
                </a>
                @endif
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
            {{ session('error') }}
        </div>
        @endif

        <!-- Status Alert -->
        @if($caseOrder->status === 'under review')
        <div class="mb-6 bg-purple-50 border-l-4 border-purple-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-purple-700">
                        <strong>Work has been delivered!</strong> Please review and either approve or request
                        adjustments.
                        <a href="{{ route('clinic.case-orders.review', $caseOrder->co_id) }}"
                            class="underline font-bold">Click here to review</a>
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Case Details Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Case Details
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Case ID</p>
                                <p class="font-bold text-blue-600">CASE-{{ str_pad($caseOrder->co_id, 5, '0',
                                    STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                @php
                                $statusColors = [
                                'pending' => 'bg-gray-100 text-gray-800', 'for appointment' => 'bg-blue-50
                                text-blue-700',
                                'in progress' => 'bg-blue-100 text-blue-800',
                                'under review' => 'bg-purple-100 text-purple-800',
                                'adjustment requested' => 'bg-orange-100 text-orange-800',
                                'revision in progress' => 'bg-yellow-100 text-yellow-800',
                                'completed' => 'bg-green-100 text-green-800',
                                ];
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$caseOrder->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($caseOrder->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Case Type</p>
                                <p class="font-medium">{{ ucfirst($caseOrder->case_type) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Created Date</p>
                                <p class="font-medium">{{ $caseOrder->created_at->format('F j, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Submission Count</p>
                                <p class="font-medium">{{ $caseOrder->submission_count }}</p>
                            </div>
                            @if($caseOrder->latest_delivery && $caseOrder->latest_delivery->delivered_at)
                            <div>
                                <p class="text-sm text-gray-500">Last Delivered</p>
                                <p class="font-medium">{{ $caseOrder->latest_delivery->delivered_at->format('M j, Y g:i
                                    A') }}</p>
                            </div>
                            @endif
                        </div>

                        @if($caseOrder->notes)
                        <div class="mt-6">
                            <p class="text-sm text-gray-500 mb-2">Notes / History</p>
                            <div class="p-4 bg-gray-50 rounded-lg text-sm whitespace-pre-line">{{ $caseOrder->notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Patient & Dentist Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Patient & Dentist Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Patient -->
                            <div class="border-l-4 border-blue-500 pl-4">
                                <p class="text-sm font-semibold text-gray-500 uppercase">Patient</p>
                                <p class="font-bold text-lg">{{ $caseOrder->patient->name }}</p>
                                <p class="text-sm text-gray-600">{{ $caseOrder->patient->email }}</p>
                                <p class="text-sm text-gray-600">{{ $caseOrder->patient->contact_number }}</p>
                                @if($caseOrder->patient->address)
                                <p class="text-sm text-gray-600 mt-1">{{ $caseOrder->patient->address }}</p>
                                @endif
                            </div>
                            <!-- Dentist -->
                            <div class="border-l-4 border-green-500 pl-4">
                                <p class="text-sm font-semibold text-gray-500 uppercase">Dentist</p>
                                <p class="font-bold text-lg">{{ $caseOrder->dentist->name }}</p>
                                <p class="text-sm text-gray-600">{{ $caseOrder->dentist->email }}</p>
                                <p class="text-sm text-gray-600">{{ $caseOrder->dentist->contact_number }}</p>
                                @if($caseOrder->dentist->address)
                                <p class="text-sm text-gray-600 mt-1">{{ $caseOrder->dentist->address }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rest of show.blade.php content... (appointments timeline, etc.) -->
                <!-- I'll continue in the next message due to length -->

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            @if($caseOrder->status === 'under review')
                            <a href="{{ route('clinic.case-orders.review', $caseOrder->co_id) }}"
                                class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center font-bold py-2 px-4 rounded-lg transition">
                                📋 Review Submission
                            </a>
                            @endif

                            @if(in_array($caseOrder->status, ['pending', 'adjustment requested']))
                            <a href="{{ route('clinic.case-orders.edit', $caseOrder->co_id) }}"
                                class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded-lg transition">
                                ✏️ Edit Case Order
                            </a>
                            @endif

                            @if($caseOrder->status === 'pending')
                            <form action="{{ route('clinic.case-orders.destroy', $caseOrder->co_id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this case order?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="block w-full bg-red-600 hover:bg-red-700 text-white text-center font-bold py-2 px-4 rounded-lg transition">
                                    🗑️ Delete Case Order
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection