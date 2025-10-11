@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">

        <a href="{{ route('admin.case-orders.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Case Orders
        </a>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Case Order Info -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-900 to-blue-700 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold">CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT)
                                    }}</h1>
                                <p class="text-blue-100 mt-2">{{ $caseOrder->case_type }}</p>
                            </div>
                            <span
                                class="px-4 py-2 text-sm rounded-full font-semibold
                                {{ $caseOrder->status === 'initial' ? 'bg-yellow-500 text-white' : 
                                   ($caseOrder->status === 'for pickup' ? 'bg-blue-500 text-white' : 
                                   ($caseOrder->status === 'for appointment' ? 'bg-purple-500 text-white' : 
                                   ($caseOrder->status === 'in-progress' ? 'bg-indigo-500 text-white' : 
                                   ($caseOrder->status === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white')))) }}">
                                {{ ucfirst(str_replace('-', ' ', $caseOrder->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Case Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Case Number</p>
                                <p class="text-lg font-semibold text-gray-800">CASE-{{ str_pad($caseOrder->co_id, 5,
                                    '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Case Type</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $caseOrder->case_type }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <p class="text-lg font-semibold text-gray-800">{{ ucfirst(str_replace('-', ' ',
                                    $caseOrder->status)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Created Date</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $caseOrder->created_at->format('M d,
                                    Y') }}</p>
                            </div>
                        </div>

                        @if($caseOrder->notes)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Notes / Instructions</p>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded whitespace-pre-line">{{ $caseOrder->notes }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Clinic Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Clinic Information</h2>

                    <div class="flex items-center gap-4 mb-4">
                        <img src="{{ $caseOrder->clinic->profile_photo ? asset('storage/' . $caseOrder->clinic->profile_photo) : asset('images/default-clinic.png') }}"
                            alt="{{ $caseOrder->clinic->clinic_name }}"
                            class="w-16 h-16 rounded-full object-cover border-2">
                        <div>
                            <p class="text-lg font-semibold text-gray-800">{{ $caseOrder->clinic->clinic_name }}</p>
                            <p class="text-sm text-gray-600">{{ $caseOrder->clinic->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Contact</p>
                            <p class="text-gray-800">{{ $caseOrder->clinic->contact_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Address</p>
                            <p class="text-gray-800">{{ $caseOrder->clinic->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Patient & Dentist -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Patient & Dentist</h2>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Patient</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $caseOrder->patient->name ?? 'N/A' }}</p>
                            @if($caseOrder->patient)
                            <p class="text-sm text-gray-600 mt-1">{{ $caseOrder->patient->contact_number ?? '' }}</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-2">Dentist</p>
                            <p class="text-lg font-semibold text-gray-800">Dr. {{ $caseOrder->dentist->name ?? 'N/A' }}
                            </p>
                            @if($caseOrder->dentist)
                            <p class="text-sm text-gray-600 mt-1">{{ $caseOrder->dentist->contact_number ?? '' }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Pickup Information (if assigned) -->
                @if($caseOrder->status === 'for pickup' || $caseOrder->status === 'for appointment' ||
                $caseOrder->pickup)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Pickup Information</h2>

                    @if($caseOrder->pickup)
                    <div class="flex items-center gap-4 mb-4">
                        <img src="{{ $caseOrder->pickup->rider->photo ? asset('storage/' . $caseOrder->pickup->rider->photo) : asset('images/default-avatar.png') }}"
                            alt="{{ $caseOrder->pickup->rider->name }}"
                            class="w-12 h-12 rounded-full object-cover border-2">
                        <div>
                            <p class="text-sm text-gray-500">Assigned Rider</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $caseOrder->pickup->rider->name }}</p>
                            <p class="text-sm text-gray-600">{{ $caseOrder->pickup->rider->contact_number }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Pickup Status</p>
                            <span
                                class="inline-block mt-1 px-2 py-1 text-xs rounded-full font-medium
            {{ $caseOrder->pickup->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $caseOrder->pickup->status === 'pending' ? 'Pending Pickup' : 'Picked Up & Delivered'
                                }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-500">Pickup Date</p>
                            <p class="text-gray-800">{{ $caseOrder->pickup->pickup_date ?
                                \Carbon\Carbon::parse($caseOrder->pickup->pickup_date)->format('M d, Y') : 'Not set' }}
                            </p>
                        </div>
                        @if($caseOrder->pickup->picked_up_at)
                        <div>
                            <p class="text-gray-500">Picked Up At</p>
                            <p class="text-gray-800">{{ $caseOrder->pickup->picked_up_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <p class="text-gray-500">No pickup assigned yet.</p>
                    @endif
                </div>
                @endif

                <!-- Appointments -->
                @if($caseOrder->appointments->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Appointments</h2>

                    <div class="space-y-3">
                        @foreach($caseOrder->appointments as $appointment)
                        <div class="border-l-4 border-blue-500 pl-4 py-2 bg-gray-50 rounded">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $appointment->schedule_datetime->format('M
                                        d, Y h:i A') }}</p>
                                    <p class="text-sm text-gray-600">Technician: {{ $appointment->technician->name ??
                                        'Not assigned' }}</p>
                                    <p class="text-sm text-gray-600">Purpose: {{ $appointment->purpose ?? 'N/A' }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                                    {{ $appointment->work_status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($appointment->work_status === 'in-progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($appointment->work_status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <!-- Approve & Assign Rider (if initial) -->
                        @if($caseOrder->status === 'initial')
                        <button onclick="openAssignRiderModal()"
                            class="block w-full bg-green-600 text-white text-center py-2 rounded-lg hover:bg-green-700 transition">
                            Approve & Assign Rider
                        </button>
                        @endif

                        <!-- Create Appointment (if for appointment) -->
                        @if($caseOrder->status === 'for appointment')
                        <a href="{{ route('admin.appointments.create', ['case_order' => $caseOrder->co_id]) }}"
                            class="block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition">
                            Create Appointment
                        </a>
                        @endif

                        <!-- Edit -->
                        <a href="{{ route('admin.case-orders.edit', $caseOrder) }}"
                            class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition">
                            Edit Case Order
                        </a>

                        <!-- Delete -->
                        <button onclick="confirmDelete()"
                            class="block w-full bg-red-600 text-white text-center py-2 rounded-lg hover:bg-red-700 transition">
                            Delete Case Order
                        </button>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Timeline</h3>

                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Created</p>
                                <p class="text-xs text-gray-500">{{ $caseOrder->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>

                        @if($caseOrder->status !== 'initial')
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Status: {{ ucfirst(str_replace('-', ' ',
                                    $caseOrder->status)) }}</p>
                                <p class="text-xs text-gray-500">{{ $caseOrder->updated_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Assign Rider Modal -->
<div id="assignRiderModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Approve & Assign Rider</h3>

        <form action="{{ route('admin.case-orders.approve-and-assign', $caseOrder->co_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Rider</label>
                <select name="rider_id" required
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none">
                    <option value="">-- Select Rider --</option>
                    @foreach($riders as $rider)
                    <option value="{{ $rider->id }}">{{ $rider->name }} - {{ $rider->contact_number }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Date (Optional)</label>
                <input type="date" name="pickup_date" min="{{ date('Y-m-d') }}"
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea name="notes" rows="3"
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none"
                    placeholder="Any special instructions for pickup..."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeAssignRiderModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Approve & Assign
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Confirm Delete</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this case order? This action cannot be undone.</p>

        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Cancel
            </button>
            <form action="{{ route('admin.case-orders.destroy', $caseOrder) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openAssignRiderModal() {
    document.getElementById('assignRiderModal').classList.remove('hidden');
}

function closeAssignRiderModal() {
    document.getElementById('assignRiderModal').classList.add('hidden');
}

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAssignRiderModal();
        closeDeleteModal();
    }
});
</script>
@endsection