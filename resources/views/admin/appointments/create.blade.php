@extends('layouts.app')

@section('page-title', 'Create Appointment')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <a href="{{ route('admin.appointments.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Appointments
        </a>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Appointment</h1>

            @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.appointments.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Case Order Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Case Order <span class="text-red-500">*</span>
                    </label>

                    @if($caseOrder)
                    <!-- Pre-selected case order -->
                    <div class="border-2 border-blue-500 rounded-lg p-4 bg-blue-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">CASE-{{ str_pad($caseOrder->co_id, 5, '0',
                                    STR_PAD_LEFT) }}</p>
                                <p class="text-sm text-gray-600">Clinic: {{ $caseOrder->clinic->clinic_name }}</p>
                                <p class="text-sm text-gray-600">Patient: {{ $caseOrder->patient->name }}</p>
                                <p class="text-sm text-gray-600">Type: {{ $caseOrder->case_type }}</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-xs">Selected</span>
                        </div>
                    </div>
                    <input type="hidden" name="case_order_id" value="{{ $caseOrder->co_id }}">
                    @else
                    <!-- Dropdown selection -->
                    <select name="case_order_id" id="caseOrderSelect" required
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                        <option value="">-- Select Case Order --</option>
                        @foreach($caseOrders as $order)
                        <option value="{{ $order->co_id }}" data-clinic="{{ $order->clinic->clinic_name }}"
                            data-patient="{{ $order->patient->name }}" data-type="{{ $order->case_type }}">
                            CASE-{{ str_pad($order->co_id, 5, '0', STR_PAD_LEFT) }} - {{ $order->clinic->clinic_name }}
                            - {{ $order->patient->name }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Case Order Details Preview -->
                    <div id="caseOrderPreview" class="hidden mt-3 p-4 bg-gray-50 rounded-lg border">
                        <p class="text-sm text-gray-600"><strong>Clinic:</strong> <span id="previewClinic"></span></p>
                        <p class="text-sm text-gray-600"><strong>Patient:</strong> <span id="previewPatient"></span></p>
                        <p class="text-sm text-gray-600"><strong>Case Type:</strong> <span id="previewType"></span></p>
                    </div>
                    @endif
                </div>

                <!-- Technician Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Assign Technician <span class="text-red-500">*</span>
                    </label>
                    <select name="technician_id" required
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                        <option value="">-- Select Technician --</option>
                        @foreach($technicians as $technician)
                        <option value="{{ $technician->id }}">
                            {{ $technician->name }} - {{ $technician->email }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">The technician will be notified immediately upon appointment
                        creation.</p>
                </div>

                <!-- Schedule Date & Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Schedule Date & Time <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="schedule_datetime" min="{{ date('Y-m-d\TH:i') }}" required
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">Select the date and time for this appointment</p>
                </div>


                <!-- Purpose/Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Purpose / Work Description
                    </label>
                    <textarea name="purpose" rows="4"
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"
                        placeholder="Describe the work to be done, any special instructions, etc."></textarea>
                </div>

                <!-- Info Box -->
                {{-- <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 mb-1">What happens after creation?</h4>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• Case order status will change to "In Progress"</li>
                                <li>• Technician will receive an instant notification</li>
                                <li>• Technician can view work details and select materials needed</li>
                                <li>• You can track progress from the appointments dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div> --}}

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.appointments.index') }}"
                        class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Create Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Case order preview (if not pre-selected)
const caseOrderSelect = document.getElementById('caseOrderSelect');
if (caseOrderSelect) {
    caseOrderSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('previewClinic').textContent = selected.dataset.clinic;
            document.getElementById('previewPatient').textContent = selected.dataset.patient;
            document.getElementById('previewType').textContent = selected.dataset.type;
            document.getElementById('caseOrderPreview').classList.remove('hidden');
        } else {
            document.getElementById('caseOrderPreview').classList.add('hidden');
        }
    });
}

// Set default datetime to 1 hour from now
const now = new Date();
now.setHours(now.getHours() + 1);
const year = now.getFullYear();
const month = String(now.getMonth() + 1).padStart(2, '0');
const day = String(now.getDate()).padStart(2, '0');
const hours = String(now.getHours()).padStart(2, '0');
const minutes = String(now.getMinutes()).padStart(2, '0');
const datetimeInput = document.querySelector('input[name="schedule_datetime"]');
if (datetimeInput) {
    datetimeInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
}
</script>
@endsection