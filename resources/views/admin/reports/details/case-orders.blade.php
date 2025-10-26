@extends('layouts.app')

@section('page-title', 'Case Orders Report - Detailed Breakdown')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('admin.reports.index', ['type' => 'case-orders', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
                class="text-blue-600 hover:underline mb-2 inline-block">
                ← Back to Reports
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Case Orders - Detailed Breakdown</h1>
            <p class="text-gray-600">Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{
                \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
        </div>
        <button onclick="exportDetailPDF()"
            class="bg-red-600 text-white px-5 py-2 rounded font-semibold hover:bg-red-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Export Detailed PDF
        </button>
    </div>

    <!-- Executive Summary -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Executive Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <h3 class="text-sm text-gray-600">Total Cases</h3>
                <p class="text-4xl font-bold text-blue-600 mt-2">{{ $data['total_cases'] }}</p>
            </div>
            @foreach($data['status_breakdown'] as $status => $count)
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm text-gray-600">{{ ucfirst(str_replace('-', ' ', $status)) }}</h3>
                <p class="text-4xl font-bold text-gray-800 mt-2">{{ $count }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $data['total_cases'] > 0 ? number_format(($count /
                    $data['total_cases']) * 100, 1) : 0 }}%</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Status Distribution Chart -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Status Distribution</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Bar Chart -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Cases by Status</h3>
                @foreach($data['status_breakdown'] as $status => $count)
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-700">{{ ucfirst(str_replace('-', ' ', $status)) }}</span>
                        <span class="text-sm font-bold text-gray-800">{{ $count }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-blue-600 h-4 rounded-full transition-all"
                            style="width: {{ $data['total_cases'] > 0 ? ($count / $data['total_cases']) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Case Type Distribution -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Cases by Type</h3>
                @foreach($data['case_type_breakdown'] as $type)
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-700">{{ $type->case_type }}</span>
                        <span class="text-sm font-bold text-gray-800">{{ $type->count }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-green-600 h-4 rounded-full transition-all"
                            style="width: {{ $data['total_cases'] > 0 ? ($type->count / $data['total_cases']) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Case Type Breakdown Table -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Case Type Analysis</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-blue-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Case Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Total Count</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Percentage</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Visual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['case_type_breakdown'] as $index => $type)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @if($index === 0)
                            <span class="text-2xl">🥇</span>
                            @elseif($index === 1)
                            <span class="text-2xl">🥈</span>
                            @elseif($index === 2)
                            <span class="text-2xl">🥉</span>
                            @else
                            <span class="font-semibold text-gray-600">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $type->case_type }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600">{{ $type->count }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-700">
                            {{ $data['total_cases'] > 0 ? number_format(($type->count / $data['total_cases']) * 100, 1)
                            : 0 }}%
                        </td>
                        <td class="px-4 py-3">
                            <div class="w-32 bg-gray-200 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full"
                                    style="width: {{ $data['total_cases'] > 0 ? ($type->count / $data['total_cases']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Case Orders List -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">All Case Orders ({{ $data['total_cases'] }})</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Case No.</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clinic</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Case Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['case_orders'] as $case)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-semibold text-blue-600">
                            <a href="{{ route('admin.case-orders.show', $case->co_id) }}" class="hover:underline">
                                CASE-{{ str_pad($case->co_id, 5, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $case->clinic->clinic_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $case->patient->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $case->case_type }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-xs rounded-full font-medium
                                {{ $case->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($case->status === 'in-progress' ? 'bg-blue-100 text-blue-800' : 
                                   ($case->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                {{ ucfirst(str_replace('-', ' ', $case->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-xs rounded-full font-medium
                                {{ $case->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                   ($case->priority === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($case->priority) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $case->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.case-orders.show', $case->co_id) }}"
                                class="text-blue-600 hover:underline text-xs">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Insights -->
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📊 Key Insights</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
            $completionRate = $data['total_cases'] > 0 ? (($data['status_breakdown']['completed'] ?? 0) /
            $data['total_cases']) * 100 : 0;
            $topCaseType = $data['case_type_breakdown']->first();
            @endphp
            <div class="bg-white p-4 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-600 mb-2">Completion Rate</h3>
                <p class="text-2xl font-bold text-green-600">{{ number_format($completionRate, 1) }}%</p>
                <p class="text-xs text-gray-500 mt-1">{{ $data['status_breakdown']['completed'] ?? 0 }} out of {{
                    $data['total_cases'] }} cases completed</p>
            </div>
            @if($topCaseType)
            <div class="bg-white p-4 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-600 mb-2">Most Popular Case Type</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $topCaseType->case_type }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $topCaseType->count }} cases ({{
                    number_format(($topCaseType->count / $data['total_cases']) * 100, 1) }}%)</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function exportDetailPDF() {
    const params = new URLSearchParams({
        date_from: '{{ $dateFrom }}',
        date_to: '{{ $dateTo }}'
    });
    
    // Open print preview page in new tab
    const printUrl = '{{ route("admin.reports.printCaseOrdersDetail") }}?' + params.toString();
    window.open(printUrl, '_blank');
}
</script>
@endsection