<div class="space-y-6">
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.reports.revenueDetail', ['date_from' => $dateFrom ?? '', 'date_to' => $dateTo ?? '']) }}"
            class="bg-green-600 text-white px-5 py-2 rounded font-semibold hover:bg-green-700 transition">
            View Detailed Breakdown
        </a>
    </div>
    <!-- Revenue Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-500">Total Revenue (Paid)</h3>
            <p class="text-3xl font-bold text-green-600">₱{{ number_format($data['total_revenue'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-500">Pending Payment</h3>
            <p class="text-3xl font-bold text-yellow-600">₱{{ number_format($data['pending_revenue'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-500">Partial Payment</h3>
            <p class="text-3xl font-bold text-orange-600">₱{{ number_format($data['partial_revenue'], 2) }}</p>
        </div>
    </div>

    <!-- Top Clinics by Revenue -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Top 10 Clinics by Revenue</h3>
        <div class="space-y-3">
            @foreach($data['revenue_by_clinic'] as $clinic => $revenue)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <span class="font-medium text-gray-800">{{ $clinic }}</span>
                <span class="font-bold text-green-600">₱{{ number_format($revenue, 2) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Revenue by Month -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Revenue by Month</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['revenue_by_month'] as $month)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-800">{{ date('F Y', mktime(0, 0, 0, $month->month, 1,
                            $month->year)) }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-green-600">₱{{ number_format($month->total, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Billing Details -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Billing Records</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Billing ID</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Clinic</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['billings'] as $billing)
                    <tr>
                        <td class="px-4 py-3 text-sm font-semibold">BILL-{{ str_pad($billing->id, 5, '0', STR_PAD_LEFT)
                            }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{
                            $billing->appointment->caseOrder->clinic->clinic_name }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-green-600">₱{{ number_format($billing->total_amount,
                            2) }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $billing->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($billing->payment_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $billing->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>