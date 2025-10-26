@extends('layouts.app')

@section('page-title', 'Materials Report - Detailed Breakdown')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('admin.reports.index', ['type' => 'materials', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
                class="text-purple-600 hover:underline mb-2 inline-block">
                ← Back to Reports
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Materials - Detailed Breakdown</h1>
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
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Inventory Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
                <h3 class="text-sm text-gray-600">Total Materials</h3>
                <p class="text-4xl font-bold text-blue-600 mt-2">{{ $data['materials']->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">In inventory</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg border-2 border-purple-200">
                <h3 class="text-sm text-gray-600">Total Material Cost</h3>
                <p class="text-4xl font-bold text-purple-600 mt-2">₱{{ number_format($data['total_material_cost'], 2) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Usage cost in period</p>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg border-2 border-red-200">
                <h3 class="text-sm text-gray-600">Low/Out of Stock</h3>
                <p class="text-4xl font-bold text-red-600 mt-2">{{ $data['low_stock_materials']->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Need attention</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg border-2 border-green-200">
                <h3 class="text-sm text-gray-600">Materials Used</h3>
                <p class="text-4xl font-bold text-green-600 mt-2">{{ $data['material_usages']->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Different materials</p>
            </div>
        </div>
    </div>

    <!-- Inventory Status Distribution -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Inventory Status Distribution</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
            $available = $data['materials']->where('status', 'available')->count();
            $lowStock = $data['materials']->where('status', 'low stock')->count();
            $outOfStock = $data['materials']->where('status', 'out of stock')->count();
            $total = $data['materials']->count();
            @endphp
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Available</span>
                    <span class="text-sm font-bold text-green-600">{{ $available }} ({{ $total > 0 ?
                        number_format(($available/$total)*100, 1) : 0 }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-6">
                    <div class="bg-green-500 h-6 rounded-full flex items-center justify-center"
                        style="width: {{ $total > 0 ? ($available/$total)*100 : 0 }}%">
                        <span class="text-xs text-white font-bold">{{ $available }}</span>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Low Stock</span>
                    <span class="text-sm font-bold text-orange-600">{{ $lowStock }} ({{ $total > 0 ?
                        number_format(($lowStock/$total)*100, 1) : 0 }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-6">
                    <div class="bg-orange-500 h-6 rounded-full flex items-center justify-center"
                        style="width: {{ $total > 0 ? ($lowStock/$total)*100 : 0 }}%">
                        <span class="text-xs text-white font-bold">{{ $lowStock }}</span>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Out of Stock</span>
                    <span class="text-sm font-bold text-red-600">{{ $outOfStock }} ({{ $total > 0 ?
                        number_format(($outOfStock/$total)*100, 1) : 0 }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-6">
                    <div class="bg-red-500 h-6 rounded-full flex items-center justify-center"
                        style="width: {{ $total > 0 ? ($outOfStock/$total)*100 : 0 }}%">
                        <span class="text-xs text-white font-bold">{{ $outOfStock }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Material Usage -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Top 10 Most Used Materials</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-purple-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Material Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Total Used</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Total Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">% of Total Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Visual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['material_usages']->take(10) as $index => $usage)
                    <tr class="hover:bg-gray-50 {{ $index < 3 ? 'bg-yellow-50' : '' }}">
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
                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $usage->material_name }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600">{{ number_format($usage->total_used, 2)
                            }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $usage->unit }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-purple-600">₱{{ number_format($usage->total_cost, 2)
                            }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-700">
                            {{ $data['total_material_cost'] > 0 ? number_format(($usage->total_cost /
                            $data['total_material_cost']) * 100, 1) : 0 }}%
                        </td>
                        <td class="px-4 py-3">
                            <div class="w-24 bg-gray-200 rounded-full h-3">
                                <div class="bg-purple-500 h-3 rounded-full"
                                    style="width: {{ $data['total_material_cost'] > 0 ? ($usage->total_cost / $data['total_material_cost']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- All Materials Usage -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">All Materials Usage in Period</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Material Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity Used</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Cost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">% of Total Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['material_usages'] as $index => $usage)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $usage->material_name }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600">{{ number_format($usage->total_used, 2)
                            }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $usage->unit }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-purple-600">₱{{ number_format($usage->total_cost, 2)
                            }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $data['total_material_cost'] > 0 ? number_format(($usage->total_cost /
                            $data['total_material_cost']) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-purple-50">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-sm font-bold text-gray-800 text-right">Total Material
                            Cost:
                        </td>
                        <td colspan="2" class="px-4 py-3 text-lg font-bold text-purple-600">₱{{
                            number_format($data['total_material_cost'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Low Stock / Out of Stock Alert -->
    @if($data['low_stock_materials']->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-red-300">
        <h2 class="text-xl font-bold text-red-600 mb-4 border-b pb-2">⚠️ Materials Needing Immediate Attention</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-red-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Material Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Current Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Unit Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['low_stock_materials'] as $material)
                    <tr
                        class="hover:bg-red-50 {{ $material->status === 'out of stock' ? 'bg-red-100' : 'bg-orange-50' }}">
                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $material->material_name }}</td>
                        <td
                            class="px-4 py-3 text-sm font-bold {{ $material->quantity == 0 ? 'text-red-600' : 'text-orange-600' }}">
                            {{ $material->quantity }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $material->unit }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-xs rounded-full font-medium {{ $material->status === 'out of stock' ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ ucfirst($material->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ number_format($material->price, 2) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.materials.edit', $material->material_id) }}"
                                class="text-blue-600 hover:underline text-xs font-semibold">Restock Now</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Current Full Inventory -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Complete Inventory Status</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Material Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Value</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php
                    $totalInventoryValue = 0;
                    @endphp
                    @foreach($data['materials'] as $index => $material)
                    @php
                    $totalInventoryValue += ($material->quantity * $material->price);
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $material->material_name }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-gray-800">{{ $material->quantity }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $material->unit }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ number_format($material->price, 2) }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600">₱{{ number_format($material->quantity *
                            $material->price, 2) }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-xs rounded-full font-medium
                                {{ $material->status === 'available' ? 'bg-green-100 text-green-800' : 
                                   ($material->status === 'low stock' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($material->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-blue-50">
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-sm font-bold text-gray-800 text-right">Total Inventory
                            Value:</td>
                        <td colspan="2" class="px-4 py-3 text-lg font-bold text-blue-600">₱{{
                            number_format($totalInventoryValue, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Materials Insights -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📦 Material Insights</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
            $avgCostPerMaterial = $data['material_usages']->count() > 0 ? $data['total_material_cost'] /
            $data['material_usages']->count() : 0;
            $mostExpensive = $data['material_usages']->sortByDesc('total_cost')->first();
            @endphp
            <div class="bg-white p-4 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-600 mb-2">Average Cost Per Material</h3>
                <p class="text-2xl font-bold text-purple-600">₱{{ number_format($avgCostPerMaterial, 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">In this period</p>
            </div>
            @if($mostExpensive)
            <div class="bg-white p-4 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-600 mb-2">Costliest Material Used</h3>
                <p class="text-lg font-bold text-red-600">{{ $mostExpensive->material_name }}</p>
                <p class="text-sm text-gray-700 mt-1">₱{{ number_format($mostExpensive->total_cost, 2) }}</p>
            </div>
            @endif
            <div class="bg-white p-4 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-600 mb-2">Materials Needing Attention</h3>
                <p class="text-2xl font-bold text-orange-600">{{ $data['low_stock_materials']->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Low or out of stock</p>
            </div>
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
    const printUrl = '{{ route("admin.reports.printMaterialsDetail") }}?' + params.toString();
    window.open(printUrl, '_blank');
}
</script>
@endsection