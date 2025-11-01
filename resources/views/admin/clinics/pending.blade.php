@extends('layouts.app')

@section('page-title', 'Pending Clinic Approvals')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pending Clinic Registrations</h1>
            <p class="text-gray-600 mt-1">Review and approve new clinic registrations</p>
        </div>
        <a href="{{ route('admin.clinics.index') }}"
            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            All Clinics
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Pending Approvals</h3>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $totalPending }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">New Registrations (Last 7 Days)</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $recentRegistrations }}</p>
        </div>
    </div>

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

    <!-- Pending Clinics Table -->
    <div class="overflow-x-auto rounded-xl shadow-lg mt-4">
        <table class="min-w-full border-separate border-spacing-0 bg-white">
            <thead>
                <tr class="bg-orange-600 text-white">
                    <th class="px-6 py-3 text-left">Photo</th>
                    <th class="px-6 py-3 text-left">Clinic Name</th>
                    <th class="px-6 py-3 text-left">Owner</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Contact</th>
                    <th class="px-6 py-3 text-left">Address</th>
                    <th class="px-6 py-3 text-left">Registered</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingClinics as $clinic)
                <tr class="hover:bg-gray-50 border-b">
                    <td class="px-6 py-3">
                        <img src="{{ $clinic->profile_photo ? asset('storage/' . $clinic->profile_photo) : asset('images/default-clinic.png') }}"
                            alt="{{ $clinic->clinic_name }}"
                            class="w-12 h-12 object-cover rounded-full border-2 border-orange-500">
                    </td>
                    <td class="px-6 py-3">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $clinic->clinic_name }}</p>
                            <p class="text-xs text-gray-500">@{{ $clinic->username }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-gray-700">{{ $clinic->owner_name }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $clinic->email }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $clinic->contact_number ?? 'N/A' }}</td>
                    <td class="px-6 py-3 text-gray-700 text-sm">{{ Str::limit($clinic->address, 30) ?? 'N/A' }}</td>
                    <td class="px-6 py-3">
                        <div>
                            <p class="text-sm text-gray-800">{{ $clinic->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $clinic->created_at->diffForHumans() }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex justify-center items-center gap-2">
                            <!-- Approve Button -->
                            <form action="{{ route('admin.clinics.approve', $clinic->clinic_id) }}" method="POST"
                                class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition flex items-center gap-1"
                                    title="Approve Clinic">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Approve
                                </button>
                            </form>

                            <!-- Reject Button -->
                            <button onclick="openRejectModal('{{ $clinic->clinic_id }}', '{{ $clinic->clinic_name }}')"
                                class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition flex items-center gap-1"
                                title="Reject Clinic">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reject
                            </button>

                            <!-- View Button -->
                            <a href="{{ route('admin.clinics.show', $clinic->clinic_id) }}"
                                class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition"
                                title="View Details">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                    class="w-4 h-4">
                                    <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                    <path fill-rule="evenodd"
                                        d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-500 text-lg font-semibold">No Pending Registrations</p>
                            <p class="text-gray-400 text-sm mt-1">All clinic registrations have been processed.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $pendingClinics->links() }}
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="bg-red-600 px-6 py-4 rounded-t-lg">
            <h3 class="text-xl font-bold text-white">Reject Clinic Registration</h3>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-4">You are about to reject: <strong id="rejectClinicName"
                    class="text-gray-800"></strong></p>

            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea name="rejection_reason" required rows="4"
                        class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200 transition"
                        placeholder="Please provide a clear reason for rejection..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-semibold">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                        Reject Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRejectModal(clinicId, clinicName) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectClinicName').textContent = clinicName;
    document.getElementById('rejectForm').action = `/admin/clinics/${clinicId}/reject`;
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
    }
});

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection