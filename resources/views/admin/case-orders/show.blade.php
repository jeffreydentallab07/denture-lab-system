@extends('layouts.app')

@section('page-title', 'Case Order Details')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('admin.case-orders.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    ← Back
                </a>
                <h1 class="text-3xl font-bold text-gray-800">
                    Case Order: CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
                </h1>
            </div>

            <!-- Status Badge -->
            <div>
                @php
                $statusColors = [
                'pending' => 'bg-gray-100 text-gray-800', 'for appointment' => 'bg-blue-50 text-blue-700',
                'in progress' => 'bg-blue-100 text-blue-800',
                'under review' => 'bg-purple-100 text-purple-800',
                'adjustment requested' => 'bg-orange-100 text-orange-800',
                'revision in progress' => 'bg-yellow-100 text-yellow-800',
                'completed' => 'bg-green-100 text-green-800',
                ];
                @endphp
                <span
                    class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$caseOrder->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($caseOrder->status) }}
                </span>
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

        <!-- Action Alert for Pending Orders -->
        @if($caseOrder->status === 'pending')
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Action Required:</strong> Create a pickup and assign a rider to pick up this case order
                        from the clinic.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Alert for Pickup Completed -->
        @if($caseOrder->latestPickup && $caseOrder->latestPickup->status === 'picked up' &&
        (!$caseOrder->latestAppointment || $caseOrder->latestAppointment->work_status === 'completed'))
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
                        <strong>Next Step:</strong> Pickup completed! Create an appointment and assign a technician to
                        work on this case.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Alert for Work Completed -->
        @if($caseOrder->latestAppointment && $caseOrder->latestAppointment->work_status === 'completed' &&
        !$caseOrder->latestAppointment->delivery)
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
                        <strong>Next Step:</strong> Work completed! Create a delivery and assign a rider to deliver to
                        the clinic.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Alert for Adjustment Requested -->
        @if($caseOrder->status === 'adjustment requested')
        <div class="mb-6 bg-orange-50 border-l-4 border-orange-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-orange-700">
                        <strong>Adjustment Requested:</strong> The clinic has requested adjustments. Create a new pickup
                        to start the revision process.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
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
                                <p class="text-sm text-gray-500">Case Type</p>
                                <p class="font-medium">{{ ucfirst($caseOrder->case_type) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Created Date</p>
                                <p class="font-medium">{{ $caseOrder->created_at->format('F j, Y g:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Last Updated</p>
                                <p class="font-medium">{{ $caseOrder->updated_at->format('F j, Y g:i A') }}</p>
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

                <!-- Clinic, Patient & Dentist Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Clinic, Patient & Dentist Information
                        </h3>

                        <!-- Clinic -->
                        <div class="border-l-4 border-blue-500 pl-4 mb-4">
                            <p class="text-sm font-semibold text-gray-500 uppercase">Clinic</p>
                            <p class="font-bold text-lg">{{ $caseOrder->clinic->clinic_name }}</p>
                            <p class="text-sm text-gray-600">{{ $caseOrder->clinic->email }}</p>
                            <p class="text-sm text-gray-600">{{ $caseOrder->clinic->contact_number }}</p>
                            @if($caseOrder->clinic->address)
                            <p class="text-sm text-gray-600 mt-1">📍 {{ $caseOrder->clinic->address }}</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Patient -->
                            <div class="border-l-4 border-green-500 pl-4">
                                <p class="text-sm font-semibold text-gray-500 uppercase">Patient</p>
                                <p class="font-bold">{{ $caseOrder->patient->name }}</p>
                                <p class="text-sm text-gray-600">{{ $caseOrder->patient->email }}</p>
                                <p class="text-sm text-gray-600">{{ $caseOrder->patient->contact_number }}</p>
                            </div>

                            <!-- Dentist -->
                            <div class="border-l-4 border-purple-500 pl-4">
                                <p class="text-sm font-semibold text-gray-500 uppercase">Dentist</p>
                                <p class="font-bold">{{ $caseOrder->dentist->name }}</p>
                                <p class="text-sm text-gray-600">{{ $caseOrder->dentist->email }}</p>
                                <p class="text-sm text-gray-600">{{ $caseOrder->dentist->contact_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointments Timeline -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Appointments & Work History
                        </h3>

                        @if($caseOrder->appointments->count() > 0)
                        <div class="space-y-4">
                            @foreach($caseOrder->appointments->sortByDesc('created_at') as $index => $appointment)
                            <div
                                class="border-l-4 {{ $appointment->work_status === 'delivered' ? 'border-green-500' : ($appointment->work_status === 'completed' ? 'border-blue-500' : 'border-yellow-500') }} pl-4 pb-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold">Appointment #{{ $caseOrder->appointments->count() - $index
                                            }}</p>
                                        <p class="text-sm text-gray-600">{{ $appointment->purpose ?? 'General Work' }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                            {{ $appointment->work_status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $appointment->work_status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $appointment->work_status === 'in progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $appointment->work_status === 'scheduled' ? 'bg-gray-100 text-gray-800' : '' }}
                                        ">
                                        {{ ucfirst($appointment->work_status) }}
                                    </span>
                                </div>

                                <div class="mt-2 text-sm text-gray-600 space-y-1">
                                    <p><strong>Technician:</strong> {{ $appointment->technician->name }}</p>
                                    <p><strong>Scheduled:</strong> {{ $appointment->schedule_datetime->format('F j, Y
                                        g:i A') }}</p>

                                    @if($appointment->delivery)
                                    <p><strong>Delivery Status:</strong>
                                        <span
                                            class="font-semibold {{ $appointment->delivery->delivery_status === 'delivered' ? 'text-green-600' : 'text-orange-600' }}">
                                            {{ ucfirst($appointment->delivery->delivery_status) }}
                                        </span>
                                    </p>
                                    @if($appointment->delivery->delivered_at)
                                    <p><strong>Delivered:</strong> {{ $appointment->delivery->delivered_at->format('M j,
                                        Y g:i A') }}</p>
                                    @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-center text-gray-500 py-8">No appointments scheduled yet</p>
                        @endif
                    </div>
                </div>

                <!-- Pickup History -->
                @if($caseOrder->pickups && $caseOrder->pickups->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Pickup History
                        </h3>
                        <div class="space-y-4">
                            @foreach($caseOrder->pickups->sortByDesc('created_at') as $pickup)
                            <div class="border-l-4 border-orange-500 pl-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold">Pickup #{{ $loop->iteration }}</p>
                                        <p class="text-sm text-gray-600">{{ $pickup->pickup_date->format('F j, Y') }}
                                        </p>
                                    </div>
                                    <span
                                        class="px-2 py-1 rounded text-xs font-semibold
                                        {{ $pickup->status === 'picked up' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($pickup->status) }}
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    <p><strong>Rider:</strong> {{ $pickup->rider->name }}</p>
                                    @if($pickup->picked_up_at)
                                    <p><strong>Picked Up:</strong> {{ $pickup->picked_up_at->format('M j, Y g:i A') }}
                                    </p>
                                    @endif
                                    @if($pickup->notes)
                                    <p><strong>Notes:</strong> {{ $pickup->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="space-y-2">

                            @if(in_array($caseOrder->status, ['pending', 'adjustment requested']))
                            <!-- Create Pickup -->
                            <a href="{{ route('admin.case-orders.create-pickup', $caseOrder->co_id) }}"
                                class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center font-bold py-2 px-4 rounded-lg transition">
                                🚚 Create Pickup
                            </a>
                            @endif

                            @if($caseOrder->status === 'for appointment')
                            <!-- Create Appointment -->
                            <a href="{{ route('admin.case-orders.create-appointment', $caseOrder->co_id) }}"
                                class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded-lg transition">
                                📅 Create Appointment
                            </a>
                            @endif

                            @if($caseOrder->latestAppointment && $caseOrder->latestAppointment->work_status ===
                            'completed' && !$caseOrder->latestAppointment->delivery)
                            <!-- Create Delivery -->
                            <a href="{{ route('admin.case-orders.create-delivery', $caseOrder->co_id) }}"
                                class="block w-full bg-green-600 hover:bg-green-700 text-white text-center font-bold py-2 px-4 rounded-lg transition">
                                🚛 Create Delivery
                            </a>
                            @endif

                            <a href="{{ route('admin.case-orders.index') }}"
                                class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center font-bold py-2 px-4 rounded-lg transition">
                                ← Back to List
                            </a>

                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Status Timeline</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full {{ $caseOrder->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                                    @if($caseOrder->status === 'completed')
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">Completed</p>
                                    <p class="text-xs text-gray-500">Final stage</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full {{ in_array($caseOrder->status, ['under review', 'adjustment requested', 'revision in progress', 'completed']) ? 'bg-purple-500' : 'bg-gray-300' }} flex items-center justify-center">
                                    @if(in_array($caseOrder->status, ['under review', 'adjustment requested', 'revision
                                    in progress', 'completed']))
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">Under Review</p>
                                    <p class="text-xs text-gray-500">Awaiting approval</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full {{ in_array($caseOrder->status, ['in progress', 'under review', 'adjustment requested', 'revision in progress', 'completed']) ? 'bg-blue-500' : 'bg-gray-300' }} flex items-center justify-center">
                                    @if(in_array($caseOrder->status, ['in progress', 'under review', 'adjustment
                                    requested', 'revision in progress', 'completed']))
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">In Progress</p>
                                    <p class="text-xs text-gray-500">Work started</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full bg-green-500 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">Created</p>
                                    <p class="text-xs text-gray-500">{{ $caseOrder->created_at->format('M j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection