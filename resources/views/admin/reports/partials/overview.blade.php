<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Case Orders -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Case Orders</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Cases</span>
                <span class="font-bold text-blue-600">{{ $data['total_case_orders'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Completed</span>
                <span class="font-bold text-green-600">{{ $data['completed_cases'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Pending</span>
                <span class="font-bold text-yellow-600">{{ $data['pending_cases'] }}</span>
            </div>
        </div>
    </div>

    <!-- Appointments -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Appointments</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Appointments</span>
                <span class="font-bold text-blue-600">{{ $data['total_appointments'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Completed</span>
                <span class="font-bold text-green-600">{{ $data['completed_appointments'] }}</span>
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Revenue</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Revenue</span>
                <span class="font-bold text-green-600">₱{{ number_format($data['total_revenue'], 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Pending</span>
                <span class="font-bold text-yellow-600">₱{{ number_format($data['pending_revenue'], 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Deliveries -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Deliveries</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Deliveries</span>
                <span class="font-bold text-blue-600">{{ $data['total_deliveries'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Completed</span>
                <span class="font-bold text-green-600">{{ $data['completed_deliveries'] }}</span>
            </div>
        </div>
    </div>

    <!-- Staff -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Staff & Clinics</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Clinics</span>
                <span class="font-bold text-blue-600">{{ $data['total_clinics'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Technicians</span>
                <span class="font-bold text-purple-600">{{ $data['total_technicians'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Riders</span>
                <span class="font-bold text-orange-600">{{ $data['total_riders'] }}</span>
            </div>
        </div>
    </div>

    <!-- Inventory -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Inventory Status</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Low Stock</span>
                <span class="font-bold text-orange-600">{{ $data['low_stock_materials'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Out of Stock</span>
                <span class="font-bold text-red-600">{{ $data['out_of_stock_materials'] }}</span>
            </div>
        </div>
    </div>
</div>