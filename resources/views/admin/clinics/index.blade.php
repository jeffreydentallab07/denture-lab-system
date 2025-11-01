@extends('layouts.app')

@section('page-title', 'Clinics Management')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Clinic Management</h1>
            <p class="text-gray-600 mt-1">Manage all registered clinics</p>
        </div>
        <a href="{{ route('admin.clinics.pending') }}"
            class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition flex items-center gap-2 shadow-lg relative">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Pending Approvals
            @php
            $pendingCount = \App\Models\Clinic::where('approval_status', 'pending')->count();
            @endphp
            @if($pendingCount > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                {{ $pendingCount }}
            </span>
            @endif
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Registered Clinics</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalClinics }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Active Clinics (Last 30 Days)</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $activeClinics }}</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
        {{ session('success') }}
    </div>
    @endif

    <!-- Clinics Table -->
    <div class="overflow-x-auto rounded-xl shadow-lg mt-4">
        <table class="min-w-full border-separate border-spacing-0 bg-white">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Photo</th>
                    <th class="px-6 py-3 text-left">Clinic Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Contact</th>
                    <th class="px-6 py-3 text-left">Address</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Case Orders</th>
                    <th class="px-6 py-3 text-left">Patients</th>
                    <th class="px-6 py-3 text-left">Dentists</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clinics as $clinic)
                <tr class="hover:bg-gray-50 border-b">
                    <td class="px-6 py-3">
                        <img src="{{ $clinic->profile_photo ? asset('storage/' . $clinic->profile_photo) : asset('images/default-clinic.png') }}"
                            alt="{{ $clinic->clinic_name }}" class="w-12 h-12 object-cover rounded-full border">
                    </td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $clinic->clinic_name }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $clinic->email }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $clinic->contact_number ?? 'N/A' }}</td>
                    <td class="px-6 py-3 text-gray-700 text-sm">{{ Str::limit($clinic->address, 30) ?? 'N/A' }}</td>
                    <td class="px-6 py-3">
                        @if($clinic->approval_status === 'approved')
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                            Approved
                        </span>
                        @elseif($clinic->approval_status === 'pending')
                        <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">
                            Pending
                        </span>
                        @else
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                            Rejected
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            {{ $clinic->case_orders_count }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                            {{ $clinic->patients_count }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                            {{ $clinic->dentists_count }}
                        </span>
                    </td>
                    <td class="p-0">
                        <div class="flex justify-center items-baseline">
                            <!-- View Button -->
                            <a href="{{ route('admin.clinics.show', $clinic->clinic_id) }}"
                                class="px-2 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition"
                                aria-label="View Clinic">
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
                    <td colspan="10" class="text-center py-6 text-gray-500">No clinics found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $clinics->links() }}
    </div>
</div>
@endsection