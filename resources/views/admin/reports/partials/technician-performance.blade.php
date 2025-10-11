<div class="space-y-6">
    <!-- Header Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Technician Performance Report</h3>
        <p class="text-sm text-gray-600">Work performance metrics for all technicians during the selected period</p>
    </div>

    <!-- Technician Performance Table -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Technician Rankings</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Technician Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Appointments
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Completed</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Completion Rate</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Materials Used</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Material Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($data['technician_stats'] as $index => $technician)
                    <tr class="{{ $index < 3 ? 'bg-blue-50' : '' }}">
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
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $technician->photo ? asset('storage/' . $technician->photo) : asset('images/default-avatar.png') }}"
                                    alt="{{ $technician->name }}" class="w-8 h-8 rounded-full object-cover border">
                                <span class="text-sm font-medium text-gray-800">{{ $technician->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm font-bold text-blue-600">{{ $technician->total_appointments }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-green-600">{{ $technician->completed_appointments }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php
                            $rate = $technician->total_appointments > 0 ? ($technician->completed_appointments /
                            $technician->total_appointments * 100) : 0;
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $rate }}%"></div>
                                </div>
                                <span class="font-semibold text-gray-700">{{ number_format($rate, 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm font-bold text-purple-600">{{ $technician->materials_used }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-orange-600">₱{{
                            number_format($technician->total_material_cost, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">No technician data for this period
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Performers -->
    @if($data['technician_stats']->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Most Completed -->
        @php
        $topCompleted = $data['technician_stats']->sortByDesc('completed_appointments')->first();
        @endphp
        @if($topCompleted)
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <h4 class="text-sm text-gray-500 mb-2">⭐ Most Completed Work</h4>
            <p class="text-xl font-bold text-gray-800">{{ $topCompleted->name }}</p>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $topCompleted->completed_appointments }} completed</p>
        </div>
        @endif

        <!-- Most Materials Used -->
        @php
        $topMaterials = $data['technician_stats']->sortByDesc('materials_used')->first();
        @endphp
        @if($topMaterials)
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <h4 class="text-sm text-gray-500 mb-2">🔧 Most Materials Used</h4>
            <p class="text-xl font-bold text-gray-800">{{ $topMaterials->name }}</p>
            <p class="text-2xl font-bold text-purple-600 mt-2">{{ $topMaterials->materials_used }} materials</p>
        </div>
        @endif

        <!-- Best Efficiency -->
        @php
        $bestEfficiency = $data['technician_stats']->map(function($tech) {
        $tech->efficiency = $tech->total_appointments > 0 ? ($tech->completed_appointments / $tech->total_appointments *
        100) : 0;
        return $tech;
        })->sortByDesc('efficiency')->first();
        @endphp
        @if($bestEfficiency)
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <h4 class="text-sm text-gray-500 mb-2">🎯 Best Efficiency</h4>
            <p class="text-xl font-bold text-gray-800">{{ $bestEfficiency->name }}</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ number_format($bestEfficiency->efficiency, 1) }}%</p>
        </div>
        @endif
    </div>
    @endif
</div>