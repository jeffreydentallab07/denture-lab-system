@extends('layouts.clinic')

@section('title', 'Clinic Dashboard')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen text-[13px] space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
        <div
            class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Total Case Orders</h6>
            <h3 class="text-2xl font-bold text-[#189ab4] mt-2">{{ $totalCaseOrders }}</h3>
        </div>
        <div
            class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Completed</h6>
            <h3 class="text-2xl font-bold text-green-600 mt-2">{{ $completedCases }}</h3>
        </div>
        <div
            class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Pending</h6>
            <h3 class="text-2xl font-bold text-yellow-600 mt-2">{{ $pendingCases }}</h3>
        </div>
        <div
            class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Patients</h6>
            <h3 class="text-2xl font-bold text-blue-600 mt-2">{{ $totalPatients }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8 flex flex-col gap-6">
            <section
                class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition transform duration-300">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h6 class="font-semibold text-gray-700">Recent Appointments</h6>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-[#189ab4] text-white sticky top-0 shadow">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Patient</th>
                                <th class="px-4 py-2 text-left font-semibold">Technician</th>
                                <th class="px-4 py-2 text-left font-semibold">Date & Time</th>
                                <th class="px-4 py-2 text-center font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-800">
                            @forelse($recentAppointments as $appointment)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2">{{ $appointment->caseOrder->patient->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $appointment->technician->name ?? 'Not assigned' }}</td>
                                <td class="px-4 py-2 text-[11px] text-gray-500">
                                    {{ $appointment->schedule_datetime->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <span class="bg-{{ $appointment->work_status == 'completed' ? 'green' : 'yellow' }}-200 
                                                 text-{{ $appointment->work_status == 'completed' ? 'green' : 'yellow' }}-900 
                                                 font-medium px-2 py-0.5 rounded-full text-xs">
                                        {{ ucfirst($appointment->work_status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">No appointments yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="col-span-12 lg:col-span-4">
            <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5">
                <h6 class="font-semibold text-gray-700 mb-4">Quick Stats</h6>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Dentists</span>
                        <span class="font-bold text-[#189ab4]">{{ $totalDentists }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Patients</span>
                        <span class="font-bold text-[#189ab4]">{{ $totalPatients }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection