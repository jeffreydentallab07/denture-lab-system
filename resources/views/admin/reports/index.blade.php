@extends('layouts.app')

@section('page-title', 'Reports & Analytics')

@section('content')
<div class="p-6 space-y-6 bg-gray-100 min-h-screen">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reports & Analytics</h1>
            <p class="text-gray-600">Generate and export comprehensive business reports</p>
        </div>
        <button onclick="exportReport()"
            class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center gap-2 shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            Export Report
        </button>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                <select name="type"
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none">
                    <option value="overview" {{ $reportType==='overview' ? 'selected' : '' }}>Overview</option>
                    <option value="case-orders" {{ $reportType==='case-orders' ? 'selected' : '' }}>Case Orders</option>
                    <option value="revenue" {{ $reportType==='revenue' ? 'selected' : '' }}>Revenue</option>
                    <option value="materials" {{ $reportType==='materials' ? 'selected' : '' }}>Materials</option>
                    <option value="clinic-performance" {{ $reportType==='clinic-performance' ? 'selected' : '' }}>Clinic
                        Performance</option>
                    <option value="technician-performance" {{ $reportType==='technician-performance' ? 'selected' : ''
                        }}>Technician Performance</option>
                    <option value="delivery-performance" {{ $reportType==='delivery-performance' ? 'selected' : '' }}>
                        Delivery Performance</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                    class="w-full border-2 border-gray-300 rounded-lg p-2 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                    Generate Report
                </button>
            </div>
        </form>
    </div>

    <!-- Report Content -->
    <div id="reportContent">
        @if($reportType === 'overview')
        @include('admin.reports.partials.overview', ['data' => $data])
        @elseif($reportType === 'case-orders')
        @include('admin.reports.partials.case-orders', ['data' => $data])
        @elseif($reportType === 'revenue')
        @include('admin.reports.partials.revenue', ['data' => $data])
        @elseif($reportType === 'materials')
        @include('admin.reports.partials.materials', ['data' => $data])
        @elseif($reportType === 'clinic-performance')
        @include('admin.reports.partials.clinic-performance', ['data' => $data])
        @elseif($reportType === 'technician-performance')
        @include('admin.reports.partials.technician-performance', ['data' => $data])
        @elseif($reportType === 'delivery-performance')
        @include('admin.reports.partials.delivery-performance', ['data' => $data])
        @endif
    </div>
</div>

<script>
    function exportReport() {
    // Get current filter parameters
    const params = new URLSearchParams(window.location.search);
    
    // Build print preview URL with parameters
    const printUrl = '{{ route("admin.reports.print") }}?' + params.toString();
    
    // Open print preview in new tab
    window.open(printUrl, '_blank');
}
</script>
@endsection