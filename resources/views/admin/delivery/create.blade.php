@extends('layouts.app')

@section('page-title', 'Create Delivery')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <a href="{{ route('admin.delivery.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Deliveries
        </a>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Delivery</h1>

            @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.delivery.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Appointment Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Select Completed Case <span class="text-red-500">*</span>
                    </label>

                    @if($appointment)
                    <!-- Pre-selected appointment -->
                    <div class="border-2 border-green-500 rounded-lg p-4 bg-green-50">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="font-semibold text-gray-800">APT-{{ str_pad($appointment->appointment_id, 5,
                                    '0', STR_PAD_LEFT) }}</p>
                                <p class="text-sm text-gray-600">CASE-{{ str_pad($appointment->case_order_id, 5, '0',
                                    STR_PAD_LEFT) }}</p>
                            </div>
                            <span class="px-3 py-1 bg-green-600 text-white rounded-full text-xs">Ready for
                                Delivery</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-500">Clinic:</p>
                                <p class="font-medium text-gray-800">{{ $appointment->caseOrder->clinic->clinic_name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Address:</p>
                                <p class="font-medium text-gray-800">{{ $appointment->caseOrder->clinic->address }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Contact:</p>
                                <p class="font-medium text-gray-800">{{ $appointment->caseOrder->clinic->contact_number
                                    }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Total Amount:</p>
                                <p class="font-medium text-green-600">₱{{
                                    number_format($appointment->billing->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="appointment_id" value="{{ $appointment->appointment_id }}">
                    @else
                    <!-- Dropdown selection -->
                    <select name="appointment_id" id="appointmentSelect" required
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"
                        onchange="updateAppointmentDetails()">
                        <option value="">-- Select Completed Case --</option>
                        @foreach($readyAppointments as $apt)
                        <option value="{{ $apt->appointment_id }}"
                            data-clinic="{{ $apt->caseOrder->clinic->clinic_name }}"
                            data-address="{{ $apt->caseOrder->clinic->address }}"
                            data-contact="{{ $apt->caseOrder->clinic->contact_number }}"
                            data-amount="{{ $apt->billing->total_amount }}">
                            APT-{{ str_pad($apt->appointment_id, 5, '0', STR_PAD_LEFT) }} - {{
                            $apt->caseOrder->clinic->clinic_name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Appointment Preview -->
                    <div id="appointmentPreview" class="hidden mt-3 p-4 bg-gray-50 rounded-lg border">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-500">Clinic:</p>
                                <p class="font-medium text-gray-800" id="previewClinic"></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Address:</p>
                                <p class="font-medium text-gray-800" id="previewAddress"></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Contact:</p>
                                <p class="font-medium text-gray-800" id="previewContact"></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Total Amount:</p>
                                <p class="font-medium text-green-600" id="previewAmount"></p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Rider Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Assign Rider <span class="text-red-500">*</span>
                    </label>
                    <select name="rider_id" required
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                        <option value="">-- Select Rider --</option>
                        @foreach($riders as $rider)
                        <option value="{{ $rider->id }}">
                            {{ $rider->name }} - {{ $rider->contact_number ?? $rider->email }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">The rider will be notified immediately upon delivery creation.
                    </p>
                </div>

                <!-- Delivery Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Scheduled Delivery Date (Optional)
                    </label>
                    <input type="date" name="delivery_date" min="{{ date('Y-m-d') }}"
                        value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">If not specified, delivery will be scheduled for tomorrow.</p>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Delivery Notes (Optional)
                    </label>
                    <textarea name="notes" rows="4"
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"
                        placeholder="Special instructions for delivery..."></textarea>
                </div>

                <!-- Info Box -->
                {{-- <div class="bg-orange-50 border-l-4 border-orange-500 p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-orange-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-orange-800 mb-1">What happens after creation?</h4>
                            <ul class="text-xs text-orange-700 space-y-1">
                                <li>• The rider will receive an instant notification with delivery details</li>
                                <li>• The clinic will be notified about the scheduled delivery</li>
                                <li>• The rider can update delivery status (Ready → In Transit → Delivered)</li>
                                <li>• You can track the delivery progress from the admin panel</li>
                            </ul>
                        </div>
                    </div>
                </div> --}}

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.delivery.index') }}"
                        class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
                        Create Delivery
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateAppointmentDetails() {
    const select = document.getElementById('appointmentSelect');
    const option = select.options[select.selectedIndex];
    
    if (select.value) {
        document.getElementById('previewClinic').textContent = option.dataset.clinic;
        document.getElementById('previewAddress').textContent = option.dataset.address;
        document.getElementById('previewContact').textContent = option.dataset.contact;
        document.getElementById('previewAmount').textContent = '₱' + parseFloat(option.dataset.amount).toLocaleString('en-US', {minimumFractionDigits: 2});
        document.getElementById('appointmentPreview').classList.remove('hidden');
    } else {
        document.getElementById('appointmentPreview').classList.add('hidden');
    }
}
</script>
@endsection