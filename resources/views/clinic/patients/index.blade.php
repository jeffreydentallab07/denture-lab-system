@extends('layouts.clinic')

@section('page-title', 'Patient List')

@section('content')
<div class="py-12">
  <div class="max-w-full mx-auto sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-3xl font-bold text-gray-800">👥 Patients</h1>
      <button id="openAddPatientModal"
        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
        + Add Patient
      </button>
    </div>

    <!-- Success/Error Messages -->
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

    <!-- Search/Filter Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
      <div class="p-6">
        <form method="GET" action="{{ route('clinic.patients.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">

          <!-- Search -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
              placeholder="Search by name, email, or contact..."
              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3">
          </div>

          <!-- Doctor Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Doctor</label>
            <select name="dentist_id"
              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3">
              <option value="">All Doctors</option>
              @foreach($dentists as $dentist)
              <option value="{{ $dentist->dentist_id }}" {{ request('dentist_id')==$dentist->dentist_id ? 'selected' :
                '' }}>
                {{ $dentist->name }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-end gap-2">
            <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
              🔍 Filter
            </button>
            <a href="{{ route('clinic.patients.index') }}"
              class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
              Reset
            </a>
          </div>

        </form>
      </div>
    </div>

    <!-- Patients Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Patient Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Contact</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Address</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Assigned Doctor</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($patients as $patient)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ $patient->name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $patient->email ?: '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $patient->contact_number ?: '-' }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ $patient->address ?: '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  {{ $patient->dentist->name ?? 'N/A' }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                <button onclick="openEditPatientModal(
                                    {{ $patient->patient_id }},
                                    '{{ addslashes($patient->name) }}',
                                    '{{ addslashes($patient->email) }}',
                                    '{{ addslashes($patient->contact_number) }}',
                                    '{{ addslashes($patient->address) }}',
                                    {{ $patient->dentist_id ?? 'null' }}
                                )" class="text-blue-600 hover:text-blue-900 mr-3">
                  Edit
                </button>
                <button onclick="openDeletePatientModal({{ $patient->patient_id }}, '{{ addslashes($patient->name) }}')"
                  class="text-red-600 hover:text-red-900">
                  Delete
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-lg">No patients found</p>
                <p class="text-sm mt-2">Add your first patient to get started</p>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($patients->hasPages())
      <div class="px-6 py-4 border-t">
        {{ $patients->links() }}
      </div>
      @endif
    </div>

  </div>
</div>

<!-- Add Patient Modal -->
<div id="addPatientModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

    <!-- Header -->
    <div
      class="px-6 py-5 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-blue-600 to-blue-700">
      <h2 class="text-xl font-bold text-white">Add New Patient</h2>
      <button id="closeAddPatientModal" class="text-white hover:text-gray-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Form -->
    <form id="addPatientForm" action="{{ route('clinic.patients.store') }}" method="POST" class="p-8 space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span
              class="text-red-500">*</span></label>
          <input type="text" name="name" required placeholder="Enter patient name"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
          <input type="email" name="email" placeholder="patient@example.com"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
          <input type="text" name="contact_number" placeholder="09XX XXX XXXX"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Assigned Doctor <span
              class="text-red-500">*</span></label>
          <select name="dentist_id" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="">Select Doctor</option>
            @foreach($dentists as $dentist)
            <option value="{{ $dentist->dentist_id }}">{{ $dentist->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
        <textarea name="address" rows="3" placeholder="Enter complete address"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
      </div>
    </form>

    <!-- Footer -->
    <div class="px-8 py-5 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
      <button id="cancelAddPatient"
        class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition">
        Cancel
      </button>
      <button type="submit" form="addPatientForm"
        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition">
        Save Patient
      </button>
    </div>
  </div>
</div>

<!-- Edit Patient Modal -->
<div id="editPatientModal"
  class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

    <!-- Header -->
    <div
      class="px-6 py-5 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-blue-600 to-blue-700">
      <h2 class="text-xl font-bold text-white">Edit Patient</h2>
      <button onclick="closeEditPatientModal()" class="text-white hover:text-gray-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Form -->
    <form id="editPatientForm" method="POST" class="p-8 space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span
              class="text-red-500">*</span></label>
          <input type="text" id="editPatientName" name="name" required placeholder="Enter patient name"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
          <input type="email" id="editPatientEmail" name="email" placeholder="patient@example.com"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
          <input type="text" id="editPatientContact" name="contact_number" placeholder="09XX XXX XXXX"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Assigned Doctor <span
              class="text-red-500">*</span></label>
          <select id="editPatientDoctor" name="dentist_id" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="">Select Doctor</option>
            @foreach($dentists as $dentist)
            <option value="{{ $dentist->dentist_id }}">{{ $dentist->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
        <textarea id="editPatientAddress" name="address" rows="3" placeholder="Enter complete address"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
      </div>
    </form>

    <!-- Footer -->
    <div class="px-8 py-5 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
      <button onclick="closeEditPatientModal()"
        class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition">
        Cancel
      </button>
      <button type="submit" form="editPatientForm"
        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition">
        Update Patient
      </button>
    </div>
  </div>
</div>

<!-- Delete Patient Modal -->
<div id="deletePatientModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
      <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
    </div>
    <h2 class="text-xl font-bold text-gray-800 mb-2 text-center">Delete Patient</h2>
    <p class="text-gray-600 mb-6 text-center">
      Are you sure you want to delete <span id="deletePatientName" class="font-semibold text-gray-900"></span>? This
      action cannot be undone.
    </p>
    <form id="deletePatientForm" method="POST" class="flex justify-center gap-3">
      @csrf
      @method('DELETE')
      <button type="button" onclick="closeDeletePatientModal()"
        class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition">
        Cancel
      </button>
      <button type="submit"
        class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-sm transition">
        Delete
      </button>
    </form>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const addModal = document.getElementById('addPatientModal');
    document.getElementById('openAddPatientModal').addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddPatientModal').addEventListener('click', () => addModal.classList.add('hidden'));
    document.getElementById('cancelAddPatient').addEventListener('click', () => addModal.classList.add('hidden'));
});

function openEditPatientModal(id, name, email, contact, address, dentistId) {
    const modal = document.getElementById('editPatientModal');
    const form = document.getElementById('editPatientForm');
    form.action = `/clinic/patients/${id}`;
    document.getElementById('editPatientName').value = name;
    document.getElementById('editPatientEmail').value = email;
    document.getElementById('editPatientContact').value = contact;
    document.getElementById('editPatientAddress').value = address;
    document.getElementById('editPatientDoctor').value = dentistId || '';
    modal.classList.remove('hidden');
}

function closeEditPatientModal() {
    document.getElementById('editPatientModal').classList.add('hidden');
}

function openDeletePatientModal(id, name) {
    const form = document.getElementById('deletePatientForm');
    form.action = `/clinic/patients/${id}`;
    document.getElementById('deletePatientName').innerText = name;
    document.getElementById('deletePatientModal').classList.remove('hidden');
}

function closeDeletePatientModal() {
    document.getElementById('deletePatientModal').classList.add('hidden');
}
</script>
@endsection