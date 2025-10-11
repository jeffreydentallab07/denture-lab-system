@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">

        <a href="{{ route('admin.clinics.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Back to Clinics
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Clinic Info Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-900 to-blue-700 p-6 text-white">
                        <div class="flex items-center gap-6">
                            <img src="{{ $clinic->profile_photo ? asset('storage/' . $clinic->profile_photo) : asset('images/default-clinic.png') }}"
                                alt="{{ $clinic->clinic_name }}"
                                class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                            <div>
                                <h1 class="text-3xl font-bold">{{ $clinic->clinic_name }}</h1>
                                <p class="text-blue-100 mt-1">{{ $clinic->email }}</p>
                                <p class="text-blue-100">{{ $clinic->contact_number ?? 'No contact' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Clinic Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Clinic Name</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $clinic->clinic_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $clinic->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Contact Number</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $clinic->contact_number ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Member Since</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $clinic->created_at->format('M d, Y')
                                    }}</p>
                            </div>
                        </div>

                        @if($clinic->address)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500">Address</p>
                            <p class="text-gray-700 mt-1">{{ $clinic->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Case Orders -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Case Orders</h2>

                    <div class="space-y-3">
                        @forelse($recentCaseOrders as $caseOrder)
                        <div class="border-l-4 border-blue-500 pl-4 py-3 bg-gray-50 rounded">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-800">CASE-{{ str_pad($caseOrder->co_id, 5, '0',
                                        STR_PAD_LEFT) }}</p>
                                    <p class="text-sm text-gray-600">Patient: {{ $caseOrder->patient->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-600">Dentist: Dr. {{ $caseOrder->dentist->name ?? 'N/A'
                                        }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $caseOrder->case_type }}</p>
                                    <p class="text-xs text-gray-400">{{ $caseOrder->created_at->format('M d, Y') }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                                    {{ $caseOrder->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($caseOrder->status === 'in-progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($caseOrder->status) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">No case orders yet.</p>
                        @endforelse
                    </div>

                    @if($clinic->case_orders_count > 10)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.case-orders.index', ['clinic_id' => $clinic->clinic_id]) }}"
                            class="text-blue-600 hover:underline text-sm">
                            View All {{ $clinic->case_orders_count }} Case Orders →
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Dentists List -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Dentists ({{ $clinic->dentists_count }})</h2>

                    <div class="space-y-2">
                        @forelse($clinic->dentists as $dentist)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded">
                            <img src="{{ $dentist->photo ? asset('storage/' . $dentist->photo) : asset('images/default-avatar.png') }}"
                                alt="{{ $dentist->name }}" class="w-10 h-10 rounded-full object-cover border">
                            <div>
                                <p class="font-semibold text-gray-800">Dr. {{ $dentist->name }}</p>
                                <p class="text-sm text-gray-600">{{ $dentist->email ?? 'No email' }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">No dentists registered.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Patients List -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Patients ({{ $clinic->patients_count }})</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @forelse($clinic->patients->take(10) as $patient)
                        <div class="p-3 bg-gray-50 rounded border-l-4 border-green-500">
                            <p class="font-semibold text-gray-800">{{ $patient->name }}</p>
                            <p class="text-sm text-gray-600">{{ $patient->contact_number ?? 'No contact' }}</p>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4 col-span-2">No patients registered.</p>
                        @endforelse
                    </div>

                    @if($clinic->patients_count > 10)
                    <div class="mt-4 text-center">
                        <p class="text-gray-500 text-sm">Showing 10 of {{ $clinic->patients_count }} patients</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar Stats -->
            <div class="space-y-6">

                <!-- Statistics Card -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Statistics</h3>

                    <div class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Total Case Orders</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $totalCaseOrders }}</p>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Completed</p>
                            <p class="text-3xl font-bold text-green-600">{{ $completedCaseOrders }}</p>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Pending/In Progress</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $pendingCaseOrders }}</p>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Total Dentists</p>
                            <p class="text-3xl font-bold text-purple-600">{{ $clinic->dentists_count }}</p>
                        </div>

                        <div class="bg-pink-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Total Patients</p>
                            <p class="text-3xl font-bold text-pink-600">{{ $clinic->patients_count }}</p>
                        </div>
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Activity</h3>

                    <div class="space-y-3">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Registered</p>
                                <p class="text-xs text-gray-500">{{ $clinic->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        @if($clinic->updated_at != $clinic->created_at)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Last Updated</p>
                                <p class="text-xs text-gray-500">{{ $clinic->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($recentCaseOrders->isNotEmpty())
                        <div class="flex gap-3">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Latest Case Order</p>
                                <p class="text-xs text-gray-500">{{ $recentCaseOrders->first()->created_at->format('M d,
                                    Y') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection