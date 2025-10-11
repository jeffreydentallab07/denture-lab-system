<div class="space-y-6">
    <!-- Header Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Clinic Performance Report</h3>
        <p class="text-sm text-gray-600">Performance metrics for all clinics during the selected period</p>
    </div>

    <!-- Clinic Performance Table -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Clinic Rankings</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Clinic Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Orders</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Completed</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Completion Rate</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($data['clinic_stats'] as $index => $clinic)
                    <tr class="{{ $index < 3 ? 'bg-yellow-50' : '' }}">
                        <td class="px-4 py-3 text-sm">
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
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $clinic->clinic_name }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600">{{ $clinic->total_orders }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-green-600">{{ $clinic->completed_orders }}</td>
                        <td class="px-4 py-3 text-sm">
                            @php
                            $rate = $clinic->total_orders > 0 ? ($clinic->completed_orders / $clinic->total_orders *
                            100) : 0;
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $rate }}%"></div>
                                </div>
                                <span class="font-semibold text-gray-700">{{ number_format($rate, 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm font-bold text-green-600">₱{{ number_format($clinic->total_revenue,
                            2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No clinic data for this period</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Performers -->
    @if($data['clinic_stats']->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Most Orders -->
        @php
        $topOrders = $data['clinic_stats']->sortByDesc('total_orders')->first();
        @endphp
        @if($topOrders)
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <h4 class="text-sm text-gray-500 mb-2">Most Orders</h4>
            <p class="text-xl font-bold text-gray-800">{{ $topOrders->clinic_name }}</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $topOrders->total_orders }} orders</p>
        </div>
        @endif

        <!-- Highest Revenue -->
        @php
        $topRevenue = $data['clinic_stats']->sortByDesc('total_revenue')->first();
        @endphp
        @if($topRevenue)
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <h4 class="text-sm text-gray-500 mb-2">Highest Revenue</h4>
            <p class="text-xl font-bold text-gray-800">{{ $topRevenue->clinic_name }}</p>
            <p class="text-2xl font-bold text-green-600 mt-2">₱{{ number_format($topRevenue->total_revenue, 2) }}</p>
        </div>
        @endif

        <!-- Best Completion Rate -->
        @php
        $bestRate = $data['clinic_stats']->map(function($clinic) {
        $clinic->completion_rate = $clinic->total_orders > 0 ? ($clinic->completed_orders / $clinic->total_orders * 100)
        : 0;
        return $clinic;
        })->sortByDesc('completion_rate')->first();
        @endphp
        @if($bestRate)
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <h4 class="text-sm text-gray-500 mb-2">Best Completion Rate</h4>
            <p class="text-xl font-bold text-gray-800">{{ $bestRate->clinic_name }}</p>
            <p class="text-2xl font-bold text-purple-600 mt-2">{{ number_format($bestRate->completion_rate, 1) }}%</p>
        </div>
        @endif
    </div>
    @endif
</div>