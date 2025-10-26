<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Orders Detail Report</title>
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body class="bg-white">

    <!-- Print Controls (Hidden when printing) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-gray-800 text-white px-6 py-4 shadow-lg z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-lg font-bold">Case Orders Report Preview</h1>
                <p class="text-sm text-gray-300">{{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{
                    \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Print / Save as PDF
                </button>
                <button onclick="window.close()"
                    class="bg-gray-600 hover:bg-gray-700 px-6 py-2 rounded-lg font-semibold transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div class="max-w-7xl mx-auto p-8" style="margin-top: 80px;">

        <!-- Report Header -->
        <div class="text-center mb-8 pb-6 border-b-2 border-gray-300">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ config('app.name', 'Denture Lab') }}</h1>
            <h2 class="text-xl font-semibold text-gray-700 mb-1">Case Orders - Detailed Breakdown</h2>
            <p class="text-gray-600">Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{
                \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
            <p class="text-sm text-gray-500 mt-2">Generated on: {{ now()->format('M d, Y h:i A') }}</p>
        </div>

        <!-- Executive Summary -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 pb-2">Executive Summary</h2>
            <div class="grid grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-sm text-gray-600 font-medium">Total Cases</h3>
                    <p class="text-4xl font-bold text-blue-600 mt-2">{{ $data['total_cases'] }}</p>
                </div>
                @foreach($data['status_breakdown'] as $status => $count)
                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-sm text-gray-600 font-medium">{{ ucfirst(str_replace('-', ' ', $status)) }}</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $count }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $data['total_cases'] > 0 ? number_format(($count /
                        $data['total_cases']) * 100, 1) : 0 }}%</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Case Type Analysis -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 pb-2">Case Type Analysis</h2>
            <table class="min-w-full border border-gray-300">
                <thead class="bg-blue-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-blue-800">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-blue-800">Case Type
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-blue-800">Count
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['case_type_breakdown'] as $index => $type)
                    <tr class="border-b border-gray-200 {{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="px-4 py-3 border-r border-gray-200">
                            @if($index === 0)
                            <span class="text-xl">🥇</span>
                            @elseif($index === 1)
                            <span class="text-xl">🥈</span>
                            @elseif($index === 2)
                            <span class="text-xl">🥉</span>
                            @else
                            <span class="font-semibold text-gray-600">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-800 border-r border-gray-200">{{
                            $type->case_type }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600 border-r border-gray-200">{{ $type->count
                            }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-700">{{ $data['total_cases'] > 0 ?
                            number_format(($type->count / $data['total_cases']) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- All Case Orders -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 pb-2">All Case Orders ({{ $data['total_cases']
                }})</h2>
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            Case No.</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            Clinic</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            Patient</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            Case Type</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            Status</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['case_orders'] as $case)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-3 py-2 font-semibold text-blue-600 border-r border-gray-200">CASE-{{
                            str_pad($case->co_id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-3 py-2 text-gray-700 border-r border-gray-200">{{ $case->clinic->clinic_name }}
                        </td>
                        <td class="px-3 py-2 text-gray-700 border-r border-gray-200">{{ $case->patient->name ?? 'N/A' }}
                        </td>
                        <td class="px-3 py-2 font-medium text-gray-800 border-r border-gray-200">{{ $case->case_type }}
                        </td>
                        <td class="px-3 py-2 border-r border-gray-200">
                            <span
                                class="px-2 py-1 text-xs rounded-full font-medium {{ $case->status === 'completed' ? 'bg-green-100 text-green-800' : ($case->status === 'in-progress' ? 'bg-blue-100 text-blue-800' : ($case->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                {{ ucfirst(str_replace('-', ' ', $case->status)) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-gray-600">{{ $case->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Key Insights -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 pb-2">📊 Key Insights</h2>
            <div class="grid grid-cols-2 gap-4">
                @php
                $completionRate = $data['total_cases'] > 0 ? (($data['status_breakdown']['completed'] ?? 0) /
                $data['total_cases']) * 100 : 0;
                $topCaseType = $data['case_type_breakdown']->first();
                @endphp
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Completion Rate</h3>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($completionRate, 1) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $data['status_breakdown']['completed'] ?? 0 }} out of {{
                        $data['total_cases'] }} cases completed</p>
                </div>
                @if($topCaseType)
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Most Popular Case Type</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $topCaseType->case_type }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $topCaseType->count }} cases ({{
                        number_format(($topCaseType->count / $data['total_cases']) * 100, 1) }}%)</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 pt-6 border-t-2 border-gray-300 text-center text-sm text-gray-600">
            <p class="font-medium">This is an official report generated by {{ config('app.name', 'Denture Lab') }}</p>
            <p class="mt-1">For inquiries, please contact the administration office.</p>
        </div>
    </div>
</body>

</html>