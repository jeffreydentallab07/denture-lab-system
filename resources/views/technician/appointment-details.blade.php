@extends('layouts.technician')

@section('title', 'Appointment Details')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <a href="{{ route('technician.appointments.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Appointments
        </a>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 border border-red-300">
            {{ session('error') }}
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
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Work Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Case Order</p>
                                <p class="text-lg font-semibold text-gray-800">CASE-{{
                                    str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Case Type</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $appointment->caseOrder->case_type }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Scheduled Date</p>
                                <p class="text-lg font-semibold text-gray-800">{{
                                    $appointment->schedule_datetime->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Scheduled Time</p>
                                <p class="text-lg font-semibold text-gray-800">{{
                                    $appointment->schedule_datetime->format('h:i A') }}</p>
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

                <!-- Clinic & Patient Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Clinic & Patient Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 font-medium mb-2">Clinic</p>
                            <div class="flex items-center gap-3">
                                <img src="{{ $appointment->caseOrder->clinic->profile_photo ? asset('storage/' . $appointment->caseOrder->clinic->profile_photo) : asset('images/default-clinic.png') }}"
                                    alt="{{ $appointment->caseOrder->clinic->clinic_name }}"
                                    class="w-12 h-12 rounded-full object-cover border-2">
                                <div>
                                    <p class="font-semibold text-gray-800">{{
                                        $appointment->caseOrder->clinic->clinic_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $appointment->caseOrder->clinic->contact_number
                                        ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 font-medium mb-2">Patient</p>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $appointment->caseOrder->patient->name ??
                                    'N/A' }}</p>
                                <p class="text-sm text-gray-600">Contact: {{
                                    $appointment->caseOrder->patient->contact_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Materials Used -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-bold text-gray-800">Materials Used</h2>
                        @if($appointment->work_status !== 'completed' && $appointment->work_status !== 'cancelled')
                        <button onclick="openAddMaterialModal()"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                            + Add Material
                        </button>
                        @endif
                    </div>

                    @if($appointment->materialUsages->count() > 0)
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
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Notes
                                    </th>
                                    @if($appointment->work_status !== 'completed' && $appointment->work_status !==
                                    'cancelled')
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action
                                    </th>
                                    @endif
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
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $usage->notes ?? '-' }}</td>
                                    @if($appointment->work_status !== 'completed' && $appointment->work_status !==
                                    'cancelled')
                                    <td class="px-4 py-3">
                                        <form
                                            action="{{ route('technician.appointments.removeMaterial', [$appointment->appointment_id, $usage->usage_id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to remove this material?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm font-bold text-gray-800 text-right">Total
                                        Material Cost:</td>
                                    <td class="px-4 py-3 text-sm font-bold text-green-600">₱{{
                                        number_format($appointment->total_material_cost, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">No materials added yet.</p>
                    @endif
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Work Status Update -->
                @if($appointment->work_status !== 'completed' && $appointment->work_status !== 'cancelled')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Update Status</h3>

                    <form action="{{ route('technician.appointment.update', $appointment->appointment_id) }}"
                        method="POST" onsubmit="return confirm('Are you sure you want to update the status?');">
                        @csrf
                        <div class="space-y-3">
                            <select name="work_status" required
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                                <option value="pending" {{ $appointment->work_status === 'pending' ? 'selected' : ''
                                    }}>Pending</option>
                                <option value="in-progress" {{ $appointment->work_status === 'in-progress' ? 'selected'
                                    : '' }}>In Progress</option>
                                <option value="completed" {{ $appointment->work_status === 'completed' ? 'selected' : ''
                                    }}>Completed</option>
                            </select>
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                                Update Status
                            </button>
                        </div>
                    </form>

                    @if($appointment->work_status === 'in-progress')
                    <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-500 text-xs">
                        <p class="text-blue-700">
                            <strong>Note:</strong> Make sure to add all materials used before marking as completed.
                        </p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Timeline</h3>

                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Assigned to You</p>
                                <p class="text-xs text-gray-500">{{ $appointment->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>

                        @if($appointment->work_status === 'in-progress')
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
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Statistics</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Materials Used</span>
                            <span class="font-semibold text-gray-800">{{ $appointment->materialUsages->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Cost</span>
                            <span class="font-semibold text-green-600">₱{{
                                number_format($appointment->total_material_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Time Until Scheduled</span>
                            <span class="font-semibold text-gray-800">
                                @if($appointment->schedule_datetime->isFuture())
                                {{ $appointment->schedule_datetime->diffForHumans() }}
                                @elseif($appointment->schedule_datetime->isToday())
                                <span class="text-blue-600">Today</span>
                                @else
                                <span class="text-orange-600">{{ $appointment->schedule_datetime->diffForHumans()
                                    }}</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Add Material Modal -->
<div id="addMaterialModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Add Material</h3>

        <form action="{{ route('technician.appointments.addMaterial', $appointment->appointment_id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Material *</label>
                <select name="material_id" id="materialSelect" required onchange="updateMaterialInfo()"
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none">
                    <option value="">-- Select Material --</option>
                    @foreach($materials as $material)
                    <option value="{{ $material->material_id }}" data-unit="{{ $material->unit }}"
                        data-price="{{ $material->price }}" data-available="{{ $material->quantity }}">
                        {{ $material->material_name }} (Available: {{ $material->quantity }} {{ $material->unit }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity Used *</label>
                <input type="number" name="quantity_used" id="quantityInput" min="1" required
                    onchange="calculateTotal()"
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none">
                <p id="availableText" class="text-xs text-gray-500 mt-1"></p>
            </div>

            <div class="mb-4" id="totalCostDiv" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Cost</label>
                <p id="totalCost" class="text-lg font-bold text-green-600"></p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea name="notes" rows="3"
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none"
                    placeholder="Any additional notes..."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeAddMaterialModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Add Material
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddMaterialModal() {
    document.getElementById('addMaterialModal').classList.remove('hidden');
}

function closeAddMaterialModal() {
    document.getElementById('addMaterialModal').classList.add('hidden');
    document.getElementById('materialSelect').value = '';
    document.getElementById('quantityInput').value = '';
    document.getElementById('totalCostDiv').style.display = 'none';
}

function updateMaterialInfo() {
    const select = document.getElementById('materialSelect');
    const option = select.options[select.selectedIndex];
    const available = option.dataset.available;
    const unit = option.dataset.unit;
    
    if (select.value) {
        document.getElementById('availableText').textContent = `Available: ${available} ${unit}`;
        document.getElementById('quantityInput').max = available;
    } else {
        document.getElementById('availableText').textContent = '';
        document.getElementById('quantityInput').max = '';
    }
    
    calculateTotal();
}

function calculateTotal() {
    const select = document.getElementById('materialSelect');
    const option = select.options[select.selectedIndex];
    const price = parseFloat(option.dataset.price) || 0;
    const quantity = parseFloat(document.getElementById('quantityInput').value) || 0;
    
    if (price > 0 && quantity > 0) {
        const total = price * quantity;
        document.getElementById('totalCost').textContent = '₱' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('totalCostDiv').style.display = 'block';
    } else {
        document.getElementById('totalCostDiv').style.display = 'none';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddMaterialModal();
    }
});
</script>
@endsection