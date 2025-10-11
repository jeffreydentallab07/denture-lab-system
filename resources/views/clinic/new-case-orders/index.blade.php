@extends('layouts.clinic')

@section('page-title', 'Case Order List')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

    {{-- Toast Notification --}}
    @if(session('success'))
    <div id="toast"
        class="fixed top-4 right-4 z-50 bg-green-100 text-green-800 px-4 py-2 rounded shadow-md transition-all duration-500">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
        const toast = document.getElementById('toast');
        toast.classList.add('opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
    </script>
    @endif

    <div class="flex justify-between items-center mb-6">
        <button onclick="document.getElementById('addCaseOrderModal').classList.remove('hidden')"
            class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition shadow-md">
            + New Case Order
        </button>
    </div>

    <div class="overflow-x-auto rounded-xl shadow-lg mt-4">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Case ID</th>
                    <th class="px-6 py-3 text-left">Patient Name</th>
                    <th class="px-6 py-3 text-left">Dentist Name</th>
                    <th class="px-6 py-3 text-left">Created At</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($caseOrders as $order)
                <tr class="hover:bg-blue-50 transition">
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ 'CASE-' . str_pad($order->co_id, 5, '0',
                        STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $order->patient->name?? 'N/A' }}</td>
                    <td class="px-6 py-3 text-gray-700">
                        {{ $order->patient->dentist ? 'Dr. ' . $order->patient->dentist->name : 'N/A' }}
                    </td>
                    <td class="px-6 py-3 text-gray-700">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i
                        A') }}</td>
                    <td class="px-6 py-3 flex gap-2">
                        <button onclick="openViewModal(
        {{ $order->co_id }},
        '{{ addslashes($order->patient->name?? 'N/A') }}',
        '{{ addslashes($order->patient->dentist->name ?? 'N/A') }}',
        '{{ addslashes($order->case_type ?? '') }}',
        '{{ $order->status ?? 'N/A' }}',
        '{{ addslashes($order->notes ?? '') }}',
        '{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}'
    )" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition text-sm">
                            View
                        </button>
                        <button onclick="openEditModal(
                            {{ $order->co_id }},
                            {{ $order->patient_id }},
                            '{{ addslashes($order->patient->dentist->name ?? 'N/A') }}',
                            '{{ addslashes($order->case_type) }}',
                            '{{ $order->status }}',
                            '{{ addslashes($order->notes) }}'
                        )" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            Edit
                        </button>


                        <button onclick="openDeleteModal({{ $order->co_id }})"
                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-gray-500">No case orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="viewCaseOrderModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-2">
        <div class="bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-xl shadow-2xl relative font-sans"
            role="dialog" aria-labelledby="viewModalTitle" aria-modal="true">
            <button onclick="closeModal('viewCaseOrderModal')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-xl z-10"
                aria-label="Close modal">&times;</button>

            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 id="viewModalTitle" class="text-xl font-semibold text-gray-800">Case Order Details</h2>
            </div>

            <div class="p-4 space-y-4 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Case ID</label>
                        <input type="text" id="view_co_id"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Patient Name</label>
                        <input type="text" id="view_patient_name"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Dentist Name</label>
                        <input type="text" id="view_dentist_name"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Case Type</label>
                        <input type="text" id="view_case_type"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Case Status</label>
                        <input type="text" id="view_status"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Created At</label>
                        <input type="text" id="view_created_at"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 text-xs">Notes / Special Instructions</label>
                    <textarea id="view_notes" rows="3"
                        class="mt-1 block w-full border rounded px-2 py-1.5 text-sm bg-gray-100 cursor-not-allowed"
                        readonly></textarea>
                </div>

                <div class="flex justify-end mt-3">
                    <button type="button" onclick="closeModal('viewCaseOrderModal')"
                        class="px-3 py-1.5 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openViewModal(id, patientName, dentistName, caseType, caseStatus, notes, createdAt) {
        document.getElementById('view_co_id').value = 'CASE-' + String(id).padStart(5, '0');
        document.getElementById('view_patient_name').value = patientName;
        document.getElementById('view_dentist_name').value = dentistName;
        document.getElementById('view_case_type').value = caseType;
        document.getElementById('view_status').value = caseStatus;
        document.getElementById('view_notes').value = notes;
        document.getElementById('view_created_at').value = createdAt;

        document.getElementById('viewCaseOrderModal').classList.remove('hidden');
    }
    </script>


    <div id="addCaseOrderModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-2">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative font-sans" role="dialog"
            aria-labelledby="addModalTitle" aria-modal="true">
            {{-- Header with Clinic Info --}}
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-md" src="{{ Auth::guard('clinic')->user()->profile_photo 
                            ? asset('storage/' . Auth::guard('clinic')->user()->profile_photo) 
                            : 'https://via.placeholder.com/150' }}" alt="{{ $clinic->clinic_name }}">
                    </div>
                    <div>
                        <h2 id="addModalTitle" class="text-xl font-semibold text-gray-800">New Case Order</h2>
                        <p class="text-xs text-gray-500">Clinic: {{ $clinic->clinic_name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">Address: {{ $clinic->address ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">Contact No: {{ $clinic->contact_number ?? 'N/A' }}</p>
                    </div>
                </div>
                <button onclick="closeModal('addCaseOrderModal')" class="text-gray-500 hover:text-gray-800 text-xl"
                    aria-label="Close modal">&times;</button>
            </div>


            <div class="flex border-b border-gray-200 px-4 py-1 bg-blue-900 text-white">
                <span class="px-3 py-1 border-b-2 border-white font-medium text-xs">Case Order Details</span>
            </div>


            <div class="p-4 space-y-4">
                <form id="addCaseOrderForm" action="{{ route('clinic.new-case-orders.store') }}" method="POST"
                    class="space-y-3 text-sm">
                    @csrf


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label for="patient_id" class="block font-medium text-gray-700 text-xs">Patient</label>
                            <select id="patient_id" name="patient_id"
                                class="mt-1 block w-full border rounded px-2 py-1.5 text-sm" required>
                                <option value="">-- Select Patient --</option>
                                @foreach($patients as $patient)
                                <option value="{{ $patient->patient_id }}" data-dentist="{{ $patient->dentist->name }}">
                                    {{ $patient->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="dentist_name" class="block font-medium text-gray-700 text-xs">Dentist</label>
                            <input type="text" id="dentist_name" name="dentist_name"
                                class="mt-1 block w-full border rounded px-2 py-1.5 text-sm" readonly>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label for="case_type" class="block font-medium text-gray-700 text-xs">Case Type</label>
                            <select id="case_type" name="case_type"
                                class="mt-1 block w-full border rounded px-2 py-1.5 text-sm" required>
                                @foreach ($caseTypes as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block font-medium text-gray-700 text-xs">Case Status</label>
                            <input type="text" id="status" name="status"
                                class="mt-1 block w-full border rounded px-2 py-1.5 text-sm" value="initial" readonly>
                        </div>
                    </div>


                    <div>
                        <label for="notes" class="block font-medium text-gray-700 text-xs">Notes / Special
                            Instructions</label>
                        <textarea id="notes" name="notes" rows="2"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm"
                            placeholder="Optional notes..."></textarea>
                    </div>


                    <div class="flex justify-end space-x-3 mt-3">
                        <button type="button" onclick="closeModal('addCaseOrderModal')"
                            class="px-3 py-1.5 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">Cancel</button>
                        <button type="submit"
                            class="px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 text-sm">Save Case
                            Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editCaseOrderModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-2">
        <div class="bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-xl shadow-2xl relative font-sans"
            role="dialog" aria-labelledby="editModalTitle" aria-modal="true">
            <button onclick="closeModal('editCaseOrderModal')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-xl z-10"
                aria-label="Close modal">&times;</button>

            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-md" src="{{ Auth::guard('clinic')->user()->profile_photo 
                            ? asset('storage/' . Auth::guard('clinic')->user()->profile_photo) 
                            : 'https://via.placeholder.com/150' }}" alt="{{ $clinic->clinic_name }}">
                    </div>
                    <div>
                        <h2 id="editModalTitle" class="text-xl font-semibold text-gray-800">Edit Case Order</h2>
                        <p class="text-xs text-gray-500">Update details for this case order.</p>
                    </div>
                </div>
            </div>

            <form id="editCaseOrderForm" method="POST" class="p-4 space-y-4 text-sm">
                @csrf
                @method('PUT')
                <input type="hidden" name="co_id" id="edit_co_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Patient</label>
                        <input type="text" id="edit_patient_name"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm bg-gray-100 cursor-not-allowed"
                            readonly aria-describedby="editPatientDesc">
                        <input type="hidden" name="patient_id" id="edit_patient_id">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Dentist</label>
                        <input type="text" id="edit_dentist_name"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm bg-gray-100 cursor-not-allowed"
                            readonly aria-describedby="editDentistDesc">
                        <input type="hidden" name="dentist_id" id="edit_dentist_id">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label for="case_type_edit" class="block font-medium text-gray-700 text-xs">Case Type</label>
                        <select id="case_type_edit" name="case_type"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm" required>
                            @foreach ($caseTypes as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status_edit" class="block font-medium text-gray-700 text-xs">Case Status</label>
                        <select name="status" id="status_edit"
                            class="mt-1 block w-full border rounded px-2 py-1.5 text-sm" required>
                            @foreach($caseStatuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="notes_edit" class="block font-medium text-gray-700 text-xs">Notes / Special
                        Instructions</label>
                    <textarea id="notes_edit" name="notes" rows="2"
                        class="mt-1 block w-full border rounded px-2 py-1.5 text-sm"
                        placeholder="Optional notes..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 mt-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('editCaseOrderModal')"
                        class="px-3 py-1.5 bg-gray-500 text-white rounded hover:bg-gray-600 transition text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
                        Update Case Order
                    </button>
                </div>
            </form>
        </div>
    </div>


    <div id="deleteModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-2">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden relative font-sans"
            role="alertdialog" aria-labelledby="deleteModalTitle" aria-describedby="deleteModalDesc" aria-modal="true">

            <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-red-50">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h2 id="deleteModalTitle" class="text-lg font-semibold text-gray-800">Confirm Delete</h2>
                        <p class="text-xs text-gray-500">Clinic: {{ $clinic->clinic_name ?? 'N/A' }}</p>
                    </div>
                </div>
                <button onclick="closeDeleteModal()" class="text-gray-500 hover:text-gray-800 text-xl"
                    aria-label="Close delete confirmation">&times;</button>
            </div>

            <div class="p-4" id="deleteModalDesc">
                <p class="text-gray-700 mb-4">Are you sure you want to delete this case order? This action cannot be
                    undone.</p>
                <p class="text-red-600 font-medium mb-4" id="deleteCaseId">CASE-XXXXX</p>
            </div>

            <form id="deleteForm" method="POST"
                class="p-4 border-t border-gray-200 flex justify-end space-x-3 bg-gray-50">
                @csrf
                @method('DELETE')
                <input type="hidden" name="co_id" id="delete_co_id">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
                    aria-describedby="deleteWarning">Delete</button>
                <p id="deleteWarning" class="sr-only">This will permanently delete the case order.</p>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
  
    window.closeModal = function(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    
        if (modalId.includes('edit')) {
            document.getElementById('editCaseOrderForm').reset();
        }
    };

  
    window.openEditModal = function(id, patientId, dentistName, caseType, caseStatus, notes) {
        const modal = document.getElementById('editCaseOrderModal');
        const form = document.getElementById('editCaseOrderForm');
        modal.classList.remove('hidden');
        form.action = '/clinic/new-case-orders/' + id;

      
        document.getElementById('edit_co_id').value = id;
        document.getElementById('edit_patient_id').value = patientId;
        document.getElementById('edit_dentist_id').value = patientId;


        const patientOption = document.querySelector(`#patient_id option[value="${patientId}"]`);
        document.getElementById('edit_patient_name').value = patientOption ? patientOption.textContent.trim() : 'N/A';
        document.getElementById('edit_dentist_name').value = dentistName || 'N/A';

        document.getElementById('case_type_edit').value = caseType || '';
        document.getElementById('status_edit').value = caseStatus || 'initial';
        document.getElementById('notes_edit').value = notes || '';

    
        setTimeout(() => document.getElementById('case_type_edit').focus(), 100);
    };

  
    window.openDeleteModal = function(id) {
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const deleteCaseId = document.getElementById('deleteCaseId');
        const deleteCoId = document.getElementById('delete_co_id');

        deleteForm.action = '/clinic/new-case-orders/' + id;
        deleteCoId.value = id;
        deleteCaseId.textContent = 'CASE-' + String(id).padStart(5, '0');
        deleteModal.classList.remove('hidden');

      
        setTimeout(() => {
            const cancelBtn = deleteForm.querySelector('button[type="button"]');
            if (cancelBtn) cancelBtn.focus();
        }, 100);
    };

 
    window.closeDeleteModal = function() {
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.classList.add('hidden');
    };

   
    const addPatientSelect = document.getElementById('patient_id');
    const dentistInput = document.getElementById('dentist_name');
    if (addPatientSelect && dentistInput) {
        addPatientSelect.addEventListener('change', function() {
            const selectedOption = addPatientSelect.options[addPatientSelect.selectedIndex];
            dentistInput.value = selectedOption.getAttribute('data-dentist') || '';
        });
    }

 
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('addCaseOrderModal');
            closeModal('editCaseOrderModal');
            closeDeleteModal();
        }
    });

 
    ['addCaseOrderModal', 'editCaseOrderModal', 'deleteModal'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (modalId === 'deleteModal') {
                        closeDeleteModal();
                    } else {
                        closeModal(modalId);
                    }
                }
            });
        }
    });

   
    function trapFocus(modal) {
        const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];

        modal.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstFocusable) {
                        lastFocusable.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastFocusable) {
                        firstFocusable.focus();
                        e.preventDefault();
                    }
                }
            }
        });
    }

 
    document.querySelectorAll('[id^="addCaseOrderModal"], [id^="editCaseOrderModal"], [id^="deleteModal"]').forEach(modal => {
    
    });
});
    </script>

    @endsection