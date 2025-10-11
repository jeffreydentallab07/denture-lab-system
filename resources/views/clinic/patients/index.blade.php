@extends('layouts.clinic')

@section('page-title', 'Patient List')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">
  <button id="openAddPatientModal"
    class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition">
    + Add Patient
  </button>

  <div class="fixed top-4 right-4 z-50 space-y-2">

    @if(session('success'))
    <div id="successToast"
      class="max-w-sm w-full bg-green-500 text-white px-4 py-3 rounded shadow-lg transform translate-x-20 opacity-0 transition-all duration-500">
      {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div id="errorToast"
      class="max-w-sm w-full bg-red-500 text-white px-4 py-3 rounded shadow-lg transform translate-x-20 opacity-0 transition-all duration-500">
      {{ session('error') }}
    </div>
    @endif

  </div>
  <div class="overflow-x-auto rounded-xl shadow-lg mt-4">
    <table class="min-w-full border-separate border-spacing-0">
      <thead>
        <tr class="bg-blue-900 text-white">
          <th class="px-6 py-3 text-left">Patient Name</th>
          <th class="px-6 py-3 text-left">Email Address</th>
          <th class="px-6 py-3 text-left">Contact No.</th>
          <th class="px-6 py-3 text-left">Address</th>
          <th class="px-6 py-3 text-left">Assigned Doctor</th>
          <th class="px-6 py-3 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($patients as $patient)
        <tr class="bg-white hover:bg-gray-50">
          <td class="px-6 py-3 font-semibold text-gray-800">{{ $patient->name }}</td>
          <td class="px-6 py-3 text-gray-700">{{ $patient->email }}</td>
          <td class="px-6 py-3 text-gray-700">{{ $patient->contact_number }}</td>
          <td class="px-6 py-3 text-gray-700">{{ $patient->address }}</td>
          <td class="px-6 py-3 text-gray-700">
            {{ $patient->dentist->name ?? 'N/A' }}
          </td>
          <td class="px-6 py-3 flex gap-2">
            <button onclick="openEditPatientModal(
                    {{ $patient->patient_id }},
                    '{{ addslashes($patient->name) }}',
                    '{{ addslashes($patient->email) }}',
                    '{{ addslashes($patient->contact_number) }}',
                    '{{ addslashes($patient->address) }}',
                    {{ $patient->dentist_id ?? 'null' }}
                )" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
              Edit
            </button>
            <button onclick="openDeletePatientModal({{ $patient->patient_id }}, '{{ addslashes($patient->name) }}')"
              class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
              Delete
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="p-4 text-gray-500 text-center bg-white">No patients found.</td>
        </tr>
        @endforelse
      </tbody>

    </table>
  </div>


  <div id="addPatientModal"
    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">


      <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-800">Add Patient</h2>
        <button id="closeAddPatientModal" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
      </div>


      <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
        <span class="px-4 py-2 border-b-2 border-white font-medium">Patient Details</span>
      </div>


      <div class="p-6 space-y-6">
        <form id="addPatientForm" action="{{ route('clinic.patients.store') }}" method="POST" class="space-y-6">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Full Name</label>
              <input type="text" name="name" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                  focus:outline-none text-lg py-2" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                          focus:outline-none text-lg py-2">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Contact Number</label>
              <input type="text" name="contact_number" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                          focus:outline-none text-lg py-2">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Address</label>
              <input type="text" name="address" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                          focus:outline-none text-lg py-2">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Assigned Doctor</label>
            <select name="dentist_id" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                         focus:outline-none text-lg py-2" required>
              <option value=""> </option>
              @foreach($dentists as $dentist)
              <option value="{{ $dentist->dentist_id }}">{{ $dentist->name }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>


      <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
        <button id="cancelAddPatient"
          class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
        <button type="submit" form="addPatientForm"
          class="py-2 px-4 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-md">Save</button>
      </div>

    </div>
  </div>


  <div id="editPatientModal"
    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">

      <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-800">Edit Patient</h2>
        <button onclick="closeEditPatientModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
      </div>

      <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
        <span class="px-4 py-2 border-b-2 border-white font-medium">Patient Details</span>
      </div>

      <div class="p-6 space-y-6">
        <form id="editPatientForm" method="POST" class="space-y-6">
          @csrf
          @method('PUT')
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Full Name</label>
              <input type="text" id="editPatientName" name="name" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                  focus:outline-none text-lg py-2" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" id="editPatientEmail" name="email" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                          focus:outline-none text-lg py-2">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Contact Number</label>
              <input type="text" id="editPatientContact" name="contact_number" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                          focus:outline-none text-lg py-2">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Address</label>
              <input type="text" id="editPatientAddress" name="address" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                          focus:outline-none text-lg py-2">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Assigned Doctor</label>
            <select id="editPatientDoctor" name="dentist_id" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                         focus:outline-none text-lg py-2" required>
              <option value=""> </option>
              @foreach($dentists as $dentist)
              <option value="{{ $dentist->dentist_id }}">{{ $dentist->name }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>

      <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
        <button onclick="closeEditPatientModal()"
          class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
        <button type="submit" form="editPatientForm"
          class="py-2 px-4 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md">Update</button>
      </div>
    </div>
  </div>

  <!-- Delete Patient Modal -->
  <div id="deletePatientModal"
    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-96 p-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Delete Patient</h2>
      <p class="text-gray-600 mb-4">
        Are you sure you want to delete <span id="deletePatientName" class="font-semibold"></span>?
      </p>
      <form id="deletePatientForm" method="POST" class="flex justify-end gap-2">
        @csrf
        @method('DELETE')
        <button type="button" onclick="closeDeletePatientModal()"
          class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
          Cancel
        </button>
        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
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

    // Toast animations
    const successToast = document.getElementById('successToast');
    const errorToast = document.getElementById('errorToast');  // ADD THIS LINE
 
    if(successToast) {
        setTimeout(() => {
            successToast.classList.remove('translate-x-20', 'opacity-0');
            successToast.classList.add('translate-x-0', 'opacity-100');
        }, 100);

        setTimeout(() => {
            successToast.classList.add('translate-x-20', 'opacity-0');
            setTimeout(() => successToast.remove(), 500);
        }, 4100);
    }

    if(errorToast) {  // NOW THIS WILL WORK
        setTimeout(() => {
            errorToast.classList.remove('translate-x-20', 'opacity-0');
            errorToast.classList.add('translate-x-0', 'opacity-100');
        }, 100);

        setTimeout(() => {
            errorToast.classList.add('translate-x-20', 'opacity-0');
            setTimeout(() => errorToast.remove(), 500);
        }, 4100);
    }
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