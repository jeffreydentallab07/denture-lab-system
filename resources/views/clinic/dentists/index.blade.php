@extends('layouts.clinic')

@section('page-title', 'Dentist List')

@section('content')
<div class="py-12">
  <div class="max-w-full mx-auto sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-3xl font-bold text-gray-800">🦷 Dentists</h1>
      <button id="openAddDentistModal"
        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
        + Add Dentist
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
        <form method="GET" action="{{ route('clinic.dentists.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">

          <!-- Search -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
              placeholder="Search by name, email, or contact..."
              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3">
          </div>

          <!-- Placeholder for additional filter -->
          <div></div>

          <!-- Action Buttons -->
          <div class="flex items-end gap-2">
            <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
              🔍 Filter
            </button>
            <a href="{{ route('clinic.dentists.index') }}"
              class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
              Reset
            </a>
          </div>

        </form>
      </div>
    </div>

    <!-- Dentists Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Photo</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Full Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Contact</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Address</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($dentists as $dentist)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                @if($dentist->photo)
                <img src="{{ asset('storage/' . $dentist->photo) }}" alt="Dentist Photo"
                  class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                @else
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                  <span class="text-blue-600 font-semibold text-lg">{{ substr($dentist->name, 0, 1) }}</span>
                </div>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">Dr. {{ $dentist->name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $dentist->email ?: '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $dentist->contact_number ?: '-' }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ $dentist->address ?: '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                <button
                  onclick="openEditDentistModal({{ $dentist->dentist_id }}, '{{ addslashes($dentist->name) }}', '{{ addslashes($dentist->email) }}', '{{ addslashes($dentist->contact_number) }}', '{{ addslashes($dentist->address) }}', '{{ $dentist->photo }}')"
                  class="text-blue-600 hover:text-blue-900 mr-3">
                  Edit
                </button>
                <button
                  onclick="openDeleteDentistModal({{ $dentist->dentist_id }}, '{{ addslashes($dentist->name) }}', '{{ $dentist->photo }}')"
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
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <p class="text-lg">No dentists found</p>
                <p class="text-sm mt-2">Add your first dentist to get started</p>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($dentists->hasPages())
      <div class="px-6 py-4 border-t">
        {{ $dentists->links() }}
      </div>
      @endif
    </div>

  </div>
</div>

<!-- Add Dentist Modal -->
<div id="addDentistModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

    <!-- Header -->
    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="relative">
            <img id="previewAddPhoto" class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-md"
              src="https://via.placeholder.com/150" alt="Dentist Profile">
            <label class="absolute bottom-0 right-0 bg-white rounded-full p-1 cursor-pointer hover:bg-gray-100">
              <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <input type="file" name="photo" form="addDentistForm" accept="image/*" class="hidden"
                onchange="previewAddImage(event)">
            </label>
          </div>
          <div>
            <h2 class="text-xl font-bold text-white">Add New Dentist</h2>
            <p class="text-sm text-blue-100">Fill out dentist details</p>
          </div>
        </div>
        <button id="closeAddDentistModal" class="text-white hover:text-gray-200">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Form -->
    <form id="addDentistForm" action="{{ route('clinic.dentists.store') }}" method="POST" enctype="multipart/form-data"
      class="p-8 space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span
              class="text-red-500">*</span></label>
          <input type="text" name="name" required placeholder="John Doe"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
          <input type="email" name="email" placeholder="dentist@example.com"
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
          <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
          <input type="text" name="address" placeholder="Clinic address"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
      </div>
    </form>

    <!-- Footer -->
    <div class="px-8 py-5 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
      <button id="cancelAddDentist"
        class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition">
        Cancel
      </button>
      <button type="submit" form="addDentistForm"
        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition">
        Save Dentist
      </button>
    </div>
  </div>
</div>

<!-- Edit Dentist Modal -->
<div id="editDentistModal"
  class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

    <!-- Header -->
    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="relative">
            <img id="previewEditPhoto" class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-md"
              src="https://via.placeholder.com/150" alt="Dentist Profile">
            <label class="absolute bottom-0 right-0 bg-white rounded-full p-1 cursor-pointer hover:bg-gray-100">
              <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <input type="file" name="photo" form="editDentistForm" accept="image/*" class="hidden"
                onchange="previewEditImage(event)">
            </label>
          </div>
          <div>
            <h2 class="text-xl font-bold text-white">Edit Dentist</h2>
            <p class="text-sm text-blue-100">Update dentist details</p>
          </div>
        </div>
        <button onclick="closeEditDentistModal()" class="text-white hover:text-gray-200">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Form -->
    <form id="editDentistForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span
              class="text-red-500">*</span></label>
          <input type="text" id="editName" name="name" required placeholder="Dr. John Doe"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
          <input type="email" id="editEmail" name="email" placeholder="dentist@example.com"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
          <input type="text" id="editContact" name="contact_number" placeholder="09XX XXX XXXX"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
          <input type="text" id="editAddress" name="address" placeholder="Clinic address"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
      </div>
    </form>

    <!-- Footer -->
    <div class="px-8 py-5 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
      <button onclick="closeEditDentistModal()"
        class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 font-medium transition">
        Cancel
      </button>
      <button type="submit" form="editDentistForm"
        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition">
        Update Dentist
      </button>
    </div>
  </div>
</div>

<!-- Delete Dentist Modal -->
<div id="deleteDentistModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
      <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
    </div>
    <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">Delete Dentist</h2>
    <div class="flex items-center justify-center gap-3 mb-4">
      <img id="deleteDentistPhoto" src="https://via.placeholder.com/50" alt="Dentist Photo"
        class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
      <p class="text-gray-600">
        Are you sure you want to delete <span id="deleteDentistName" class="font-semibold text-gray-900"></span>?
      </p>
    </div>
    <p class="text-sm text-gray-500 text-center mb-6">This action cannot be undone.</p>
    <form id="deleteDentistForm" method="POST" class="flex justify-center gap-3">
      @csrf
      @method('DELETE')
      <button type="button" onclick="closeDeleteDentistModal()"
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
    const addModal = document.getElementById('addDentistModal');
    document.getElementById('openAddDentistModal').addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddDentistModal').addEventListener('click', () => addModal.classList.add('hidden'));
    document.getElementById('cancelAddDentist').addEventListener('click', () => addModal.classList.add('hidden'));
});

function previewAddImage(event) {
    if (event.target.files && event.target.files[0]) {
        document.getElementById('previewAddPhoto').src = URL.createObjectURL(event.target.files[0]);
    }
}

function previewEditImage(event) {
    if (event.target.files && event.target.files[0]) {
        document.getElementById('previewEditPhoto').src = URL.createObjectURL(event.target.files[0]);
    }
}

function openEditDentistModal(id, name, email, contact, address, photo) {
    const form = document.getElementById('editDentistForm');
    form.action = `/clinic/dentists/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editContact').value = contact;
    document.getElementById('editAddress').value = address;
    document.getElementById('previewEditPhoto').src = photo ? '/storage/' + photo : 'https://via.placeholder.com/150';
    document.getElementById('editDentistModal').classList.remove('hidden');
}

function closeEditDentistModal() {
    document.getElementById('editDentistModal').classList.add('hidden');
}

function openDeleteDentistModal(id, name, photo = null) {
    const form = document.getElementById('deleteDentistForm');
    form.action = `/clinic/dentists/${id}`;
    document.getElementById('deleteDentistName').innerText = name;
    document.getElementById('deleteDentistPhoto').src = photo ? '/storage/' + photo : 'https://via.placeholder.com/50';
    document.getElementById('deleteDentistModal').classList.remove('hidden');
}

function closeDeleteDentistModal() {
    document.getElementById('deleteDentistModal').classList.add('hidden');
}
</script>
@endsection