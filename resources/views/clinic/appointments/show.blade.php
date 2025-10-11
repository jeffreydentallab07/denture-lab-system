@extends('layouts.clinic')

@section('title', 'Appointment Details')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">

        <a href="{{ route('clinic.appointments.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Appointments
        </a>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Appointment Info Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-900 to-blue-700 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold">APT-{{ str_pad($appointment->appointment_id, 5, '0',
                                    STR_PAD_LEFT) }}</h1>
                                <p class="text-blue-100 mt-2">{{ $appointment->schedule_datetime->format('M d, Y h:i A')
                                    }}</p>
                            </div>
                            <span
                                class="px-4 py-2 text-sm rounded-full font-semibold
                                {{ $appointment->work_status === 'pending' ? 'bg-yellow-500 text-white' : 
                                   ($appointment->work_status === 'in-progress' ? 'bg-blue-500 text-white' : 
                                   ($appointment->work_status === 'completed' ? 'bg-green-500 text-white' : 'bg-red-500 text-white')) }}">
                                {{ ucfirst(str_replace('-', ' ', $appointment->work_status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Appointment Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Case Number</p>
                                <p class="text-lg font-semibold text-gray-800">CASE-{{
                                    str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Patient</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $appointment->caseOrder->patient->name
                                    ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Case Type</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $appointment->caseOrder->case_type }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Scheduled Date</p>
                                <p class="text-lg font-semibold text-gray-800">{{
                                    $appointment->schedule_datetime->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        @if($appointment->purpose)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Work Description</p>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded whitespace-pre-line">{{ $appointment->purpose
                                }}</p>
                        </div>
                        @endif

                        @if($appointment->caseOrder->notes)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Case Notes</p>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded whitespace-pre-line">{{
                                $appointment->caseOrder->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Technician Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Assigned Technician</h2>

                    <div class="flex items-center gap-4">
                        <img src="{{ $appointment->technician->photo ? asset('storage/' . $appointment->technician->photo) : asset('images/default-avatar.png') }}"
                            alt="{{ $appointment->technician->name }}"
                            class="w-16 h-16 rounded-full object-cover border-2">
                        <div>
                            <p class="text-lg font-semibold text-gray-800">{{ $appointment->technician->name }}</p>
                            <p class="text-sm text-gray-600">{{ $appointment->technician->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Materials Used -->
                @if($appointment->materialUsages->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Materials Used</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Material
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit
                                        Price</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($appointment->materialUsages as $usage)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{
                                        $usage->material->material_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $usage->quantity_used }} {{
                                        $usage->material->unit }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">₱{{
                                        number_format($usage->material->price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-green-600">₱{{
                                        number_format($usage->total_cost, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm font-bold text-gray-800 text-right">Total
                                        Material Cost:</td>
                                    <td class="px-4 py-3 text-sm font-bold text-green-600">₱{{
                                        number_format($appointment->total_material_cost, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Materials Used</h2>
                    <p class="text-gray-500 text-center py-4">No materials have been used yet.</p>
                </div>
                @endif

                <!-- Billing Information -->
                @if($appointment->billing)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Billing Information</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Billing ID</p>
                            <p class="font-semibold text-gray-800">BILL-{{ str_pad($appointment->billing->id, 5, '0',
                                STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Amount</p>
                            <p class="text-lg font-bold text-green-600">₱{{
                                number_format($appointment->billing->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Payment Status</p>
                            <span
                                class="inline-block px-3 py-1 text-xs rounded-full font-medium
                                {{ $appointment->billing->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($appointment->billing->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($appointment->billing->payment_status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Payment Method</p>
                            <p class="font-semibold text-gray-800">{{ $appointment->billing->payment_method ?? 'Not
                                specified' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t">
                        <a href="{{ route('clinic.billing.show', $appointment->billing->id) }}"
                            class="text-blue-600 hover:underline text-sm font-medium">
                            View Full Billing Details →
                        </a>
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Status Timeline -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Status Timeline</h3>

                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Appointment Created</p>
                                <p class="text-xs text-gray-500">{{ $appointment->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>

                        @if($appointment->work_status === 'in-progress' || $appointment->work_status === 'completed')
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Work Started</p>
                                <p class="text-xs text-gray-500">{{ $appointment->updated_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($appointment->work_status === 'completed')
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Work Completed</p>
                                <p class="text-xs text-gray-500">{{ $appointment->updated_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Stats</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Materials Used</span>
                            <span class="font-semibold text-gray-800">{{ $appointment->materialUsages->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Material Cost</span>
                            <span class="font-semibold text-green-600">₱{{
                                number_format($appointment->total_material_cost, 2) }}</span>
                        </div>
                        @if($appointment->work_status !== 'completed')
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Time Until Scheduled</span>
                            <span class="font-semibold text-gray-800">
                                @if($appointment->schedule_datetime->isFuture())
                                {{ $appointment->schedule_datetime->diffForHumans() }}
                                @elseif($appointment->schedule_datetime->isToday())
                                <span class="text-blue-600">Today</span>
                                @else
                                <span class="text-orange-600">Overdue</span>
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($appointment->billing)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>
                    <a href="{{ route('clinic.billing.show', $appointment->billing->id) }}"
                        class="block w-full bg-green-600 text-white text-center py-2 rounded-lg hover:bg-green-700 transition">
                        View Billing
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection