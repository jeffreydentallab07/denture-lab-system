<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materials Detail Report</title>
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

    <!-- Print Controls -->
    <div class="no-print fixed top-0 left-0 right-0 bg-gray-800 text-white px-6 py-4 shadow-lg z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-lg font-bold">Materials Report Preview</h1>
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
            <h2 class="text-xl font-semibold text-gray-700 mb-1">Materials - Detailed Breakdown</h2>
            <p class="text-gray-600">Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{
                \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
            <p class="text-sm text-gray-500 mt-2">Generated on: {{ now()->format('M d, Y h:i A') }}</p>
        </div>

        <!-- Inventory Overview -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 pb-2">Inventory Overview</h2>
            <div class="grid grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-sm text-gray-600 font-medium">Total Materials</h3>
                    <p class="text-4xl font-bold text-blue-600 mt-2">{{ $data['materials']->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">In inventory</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <h3 class="text-sm text-gray-600 font-medium">Total Material Cost</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">₱{{ number_format($data['total_material_cost'],
                        2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Usage cost in period</p>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                    <h3 class="text-sm text-gray-600 font-medium">Low/Out of Stock</h3>
                    <p class="text-4xl font-bold text-red-600 mt-2">{{ $data['low_stock_materials']->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Need attention</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <h3 class="text-sm text-gray-600 font-medium">Materials Used</h3>
                    <p class="text-4xl font-bold text-green-600 mt-2">{{ $data['material_usages']->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Different materials</p>
                </div>
            </div>
        </div>

        <!-- Top 10 Most Used Materials -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 pb-2">Top 10 Most Used Materials</h2>
            <table class="min-w-full border border-gray-300">
                <thead class="bg-purple-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-purple-800">Rank
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-purple-800">
                            Material Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-purple-800">Total
                            Used</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-purple-800">Unit
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-purple-800">Total
                            Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['material_usages']->take(10) as $index => $usage)
                    <tr
                        class="border-b border-gray-200 {{ $index < 3 ? 'bg-yellow-50' : ($index % 2 === 0 ? 'bg-gray-50' : 'bg-white') }}">
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
                            $usage->material_name }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600 border-r border-gray-200">{{
                            number_format($usage->total_used, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 border-r border-gray-200">{{ $usage->unit }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-purple-600 border-r border-gray-200">₱{{
                            number_format($usage->total_cost, 2) }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-700">{{ $data['total_material_cost'] > 0 ?
                            number_format(($usage->total_cost / $data['total_material_cost']) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($data['low_stock_materials']->count() > 0)
        <!-- Low Stock Alert -->
        <div class="mb-8 border-2 border-red-300 rounded-lg p-6">
            <h2 class="text-xl font-bold text-red-600 mb-4 border-b-2 border-red-300 pb-2">⚠️ Materials Needing
                Immediate Attention</h2>
            <table class="min-w-full border border-gray-300">
                <thead class="bg-red-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-red-800">Material
                            Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-red-800">Current
                            Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-red-800">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase border-r border-red-800">Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Unit Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['low_stock_materials'] as $material)
                    <tr
                        class="border-b border-gray-200 {{ $material->status === 'out of stock' ? 'bg-red-100' : 'bg-orange-50' }}">
                        <td class="px-4 py-3 text-sm font-semibold text-gray-800 border-r border-gray-200">{{
                            $material->material_name }}</td>
                        <td
                            class="px-4 py-3 text-sm font-bold {{ $material->quantity == 0 ? 'text-red-600' : 'text-orange-600' }} border-r border-gray-200">
                            {{ $material->quantity }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 border-r border-gray-200">{{ $material->unit }}</td>
                        <td class="px-4 py-3 border-r border-gray-200">
                            <span
                                class="px-2 py-1 text-xs rounded-full font-medium {{ $material->status === 'out of stock' ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ ucfirst($material->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ number_format($material->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Complete Inventory Status -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 pb-2">Complete Inventory Status</h2>
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            #</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            Material Name</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            Quantity</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            Unit</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            Unit Price</th>
                        <th
                            class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase border-r border-gray-300">
                            Total Value</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalInventoryValue = 0; @endphp
                    @foreach($data['materials'] as $index => $material)
                    @php $totalInventoryValue += ($material->quantity * $material->price); @endphp
                    <tr class="border-b border-gray-200 {{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="px-3 py-2 text-gray-600 border-r border-gray-200">{{ $index + 1 }}</td>
                        <td class="px-3 py-2 font-medium text-gray-800 border-r border-gray-200">{{
                            $material->material_name }}</td>
                        <td class="px-3 py-2 font-bold text-gray-800 border-r border-gray-200">{{ $material->quantity }}
                        </td>
                        <td class="px-3 py-2 text-gray-700 border-r border-gray-200">{{ $material->unit }}</td>
                        <td class="px-3 py-2 text-gray-700 border-r border-gray-200">₱{{ number_format($material->price,
                            2) }}</td>
                        <td class="px-3 py-2 font-bold text-blue-600 border-r border-gray-200">₱{{
                            number_format($material->quantity * $material->price, 2) }}</td>
                        <td class="px-3 py-2">
                            <span
                                class="px-2 py-1 text-xs rounded-full font-medium {{ $material->status === 'available' ? 'bg-green-100 text-green-800' : ($material->status === 'low stock' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($material->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-blue-50">
                    <tr>
                        <td colspan="5" class="px-3 py-3 text-sm font-bold text-gray-800 text-right">Total Inventory
                            Value:</td>
                        <td colspan="2" class="px-3 py-3 text-lg font-bold text-blue-600">₱{{
                            number_format($totalInventoryValue, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Material Insights -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b-2 pb-2">📦 Material Insights</h2>
            <div class="grid grid-cols-3 gap-4">
                @php
                $avgCostPerMaterial = $data['material_usages']->count() > 0 ? $data['total_material_cost'] /
                $data['material_usages']->count() : 0;
                $mostExpensive = $data['material_usages']->sortByDesc('total_cost')->first();
                @endphp
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Average Cost Per Material</h3>
                    <p class="text-2xl font-bold text-purple-600">₱{{ number_format($avgCostPerMaterial, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">In this period</p>
                </div>
                @if($mostExpensive)
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Costliest Material Used</h3>
                    <p class="text-lg font-bold text-red-600">{{ $mostExpensive->material_name }}</p>
                    <p class="text-sm text-gray-700 mt-1">₱{{ number_format($mostExpensive->total_cost, 2) }}</p>
                </div>
                @endif
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Materials Needing Attention</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ $data['low_stock_materials']->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Low or out of stock</p>
                </div>
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