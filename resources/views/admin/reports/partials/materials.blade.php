<div class="space-y-6">
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.reports.materialsDetail', ['date_from' => $dateFrom ?? '', 'date_to' => $dateTo ?? '']) }}"
            class="bg-purple-600 text-white px-5 py-2 rounded font-semibold hover:bg-purple-700 transition">
            View Detailed Breakdown
        </a>
    </div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-500">Total Materials</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $data['materials']->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-500">Total Material Cost</h3>
            <p class="text-3xl font-bold text-green-600">₱{{ number_format($data['total_material_cost'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-500">Low/Out of Stock</h3>
            <p class="text-3xl font-bold text-red-600">{{ $data['low_stock_materials']->count() }}</p>
        </div>
    </div>

    <!-- Material Usage -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Material Usage Summary</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Material Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Used</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($data['material_usages'] as $usage)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $usage->material_name }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600">{{ $usage->total_used }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $usage->unit }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-green-600">₱{{ number_format($usage->total_cost, 2)
                            }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">No material usage data for this
                            period</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($data['material_usages']->count() > 0)
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-sm font-bold text-gray-800 text-right">Total:</td>
                        <td class="px-4 py-3 text-sm font-bold text-green-600">₱{{
                            number_format($data['total_material_cost'], 2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($data['low_stock_materials']->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">⚠️ Low Stock / Out of Stock Materials</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Material Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Current Quantity
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['low_stock_materials'] as $material)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $material->material_name }}</td>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Current Inventory -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Current Inventory Status</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Material Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['materials'] as $material)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $material->material_name }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-gray-800">{{ $material->quantity }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $material->unit }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">₱{{ number_format($material->price, 2) }}</td>
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
            </table>
        </div>
    </div>
</div>