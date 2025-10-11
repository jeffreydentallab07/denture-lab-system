<div class="space-y-6">
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.reports.caseOrdersDetail', ['date_from' => $dateFrom ?? '', 'date_to' => $dateTo ?? '']) }}"
            class="bg-blue-600 text-white px-5 py-2 rounded font-semibold hover:bg-blue-700 transition">
            View Detailed Breakdown
        </a>
    </div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-500">Total Cases</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $data['total_cases'] }}</p>
        </div>
        @foreach($data['status_breakdown'] as $status => $count)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-500">{{ ucfirst(str_replace('-', ' ', $status)) }}</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $count }}</p>
        </div>
        @endforeach
    </div>

    <!-- Case Type Breakdown -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Cases by Type</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Case Type</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['case_type_breakdown'] as $type)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-800">{{ $type->case_type }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $type->count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Case Orders List -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Case Orders</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Case No.</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Clinic</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Case Type</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['case_orders'] as $case)
                    <tr>
                        <td class="px-4 py-3 text-sm font-semibold">CASE-{{ str_pad($case->co_id, 5, '0', STR_PAD_LEFT)
                            }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $case->clinic->clinic_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $case->patient->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $case->case_type }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $case->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst(str_replace('-', ' ', $case->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $case->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>