@extends('layouts.clinic')

@section('title', 'Billing Details')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">

        <a href="{{ route('clinic.billing.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Billings
        </a>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Billing Info Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-900 to-blue-700 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm">Billing Invoice</p>
                                <h1 class="text-3xl font-bold">BILL-{{ str_pad($billing->id, 5, '0', STR_PAD_LEFT) }}
                                </h1>
                                <p class="text-2xl font-bold mt-2">₱{{ number_format($billing->total_amount, 2) }}</p>
                            </div>
                            <span
                                class="px-4 py-2 text-sm rounded-full font-semibold
                                {{ $billing->payment_status === 'paid' ? 'bg-green-500 text-white' : 
                                   ($billing->payment_status === 'partial' ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white') }}">
                                {{ ucfirst($billing->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Billing Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Billing ID</p>
                                <p class="text-lg font-semibold text-gray-800">BILL-{{ str_pad($billing->id, 5, '0',
                                    STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date Issued</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $billing->created_at->format('M d, Y')
                                    }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Payment Status</p>
                                <p class="text-lg font-semibold text-gray-800">{{ ucfirst($billing->payment_status) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Payment Method</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $billing->payment_method ?? 'Not
                                    specified' }}</p>
                            </div>
                        </div>

                        @if($billing->notes)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Notes</p>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded whitespace-pre-line">{{ $billing->notes }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Case Order Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Case Order Details</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Case Number</p>
                            <p class="font-semibold text-gray-800">CASE-{{ str_pad($billing->appointment->case_order_id,
                                5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Appointment</p>
                            <p class="font-semibold text-gray-800">APT-{{ str_pad($billing->appointment_id, 5, '0',
                                STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Patient Name</p>
                            <p class="font-semibold text-gray-800">{{ $billing->appointment->caseOrder->patient->name ??
                                'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Case Type</p>
                            <p class="font-semibold text-gray-800">{{ $billing->appointment->caseOrder->case_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Technician</p>
                            <p class="font-semibold text-gray-800">{{ $billing->appointment->technician->name ?? 'N/A'
                                }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Completion Date</p>
                            <p class="font-semibold text-gray-800">{{ $billing->appointment->updated_at->format('M d,
                                Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Materials Used -->
                @if($billing->appointment->materialUsages->count() > 0)
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
                                @foreach($billing->appointment->materialUsages as $usage)
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
                                    <td colspan="3" class="px-4 py-3 text-sm font-bold text-gray-800 text-right">
                                        Subtotal (Materials):</td>
                                    <td class="px-4 py-3 text-sm font-bold text-green-600">₱{{
                                        number_format($billing->appointment->total_material_cost, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Payment Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Payment Summary</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-2 border-b">
                            <span class="text-sm text-gray-600">Material Cost</span>
                            <span class="font-semibold text-gray-800">₱{{
                                number_format($billing->appointment->total_material_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b">
                            <span class="text-sm text-gray-600">Labor & Service</span>
                            <span class="font-semibold text-gray-800">₱{{ number_format($billing->total_amount -
                                $billing->appointment->total_material_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 bg-green-50 p-3 rounded">
                            <span class="font-bold text-gray-800">Total Due</span>
                            <span class="font-bold text-green-600 text-xl">₱{{ number_format($billing->total_amount, 2)
                                }}</span>
                        </div>
                    </div>

                    @if($billing->payment_status !== 'paid')
                    <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-500 text-xs">
                        <p class="text-yellow-700">
                            <strong>Payment Pending:</strong> Please settle your payment at your earliest convenience.
                        </p>
                    </div>
                    @else
                    <div class="mt-4 p-3 bg-green-50 border-l-4 border-green-500 text-xs">
                        <p class="text-green-700">
                            <strong>✓ Paid:</strong> Thank you for your payment!
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Need Help?</h3>

                    <div class="space-y-3 text-sm">
                        <p class="text-gray-600">If you have any questions about this billing, please contact us:</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-gray-700">support@denturelab.com</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <p class="text-gray-700">(123) 456-7890</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                {{-- <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button onclick="window.print()"
                            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                            Print Invoice
                        </button>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .lg\:col-span-2,
        .lg\:col-span-2 * {
            visibility: visible;
        }

        .lg\:col-span-2 {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
@endsection