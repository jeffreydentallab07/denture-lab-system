@extends('layouts.clinic')

@section('title', 'Billing')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Billing</h1>
            <p class="text-gray-600">View all your billing records and payment status</p>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Total Bills</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $billings->total() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Unpaid</h3>
                <p class="text-3xl font-bold text-red-600 mt-2">
                    {{ $billings->where('payment_status', 'unpaid')->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Partial</h3>
                <p class="text-3xl font-bold text-yellow-600 mt-2">
                    {{ $billings->where('payment_status', 'partial')->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Paid</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    {{ $billings->where('payment_status', 'paid')->count() }}
                </p>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="flex border-b">
                <button onclick="filterBillings('all')"
                    class="filter-tab px-6 py-3 font-medium text-blue-600 border-b-2 border-blue-600">
                    All
                </button>
                <button onclick="filterBillings('unpaid')"
                    class="filter-tab px-6 py-3 font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent">
                    Unpaid
                </button>
                <button onclick="filterBillings('partial')"
                    class="filter-tab px-6 py-3 font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent">
                    Partial
                </button>
                <button onclick="filterBillings('paid')"
                    class="filter-tab px-6 py-3 font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent">
                    Paid
                </button>
            </div>
        </div>

        <!-- Billing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($billings as $billing)
            <div class="billing-card bg-white rounded-lg shadow hover:shadow-lg transition"
                data-status="{{ $billing->payment_status }}">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-xs text-gray-500">Billing ID</p>
                            <p class="text-lg font-bold text-gray-800">BILL-{{ str_pad($billing->id, 5, '0',
                                STR_PAD_LEFT) }}</p>
                        </div>
                        <span
                            class="px-3 py-1 text-xs rounded-full font-medium
                            {{ $billing->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                               ($billing->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($billing->payment_status) }}
                        </span>
                    </div>

                    <!-- Amount -->
                    <div class="mb-4 pb-4 border-b">
                        <p class="text-xs text-gray-500">Total Amount</p>
                        <p class="text-2xl font-bold text-green-600">₱{{ number_format($billing->total_amount, 2) }}</p>
                    </div>

                    <!-- Details -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <p class="text-sm text-gray-700">{{ $billing->appointment->caseOrder->patient->name ?? 'N/A'
                                }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="text-sm text-gray-700">CASE-{{ str_pad($billing->appointment->case_order_id, 5,
                                '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-sm text-gray-700">{{ $billing->created_at->format('M d, Y') }}</p>
                        </div>
                        @if($billing->payment_method)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            <p class="text-sm text-gray-700">{{ $billing->payment_method }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Action Button -->
                    <div class="border-t pt-4">
                        <a href="{{ route('clinic.billing.show', $billing->id) }}"
                            class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <p class="text-gray-500">No billing records found</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($billings->hasPages())
        <div class="mt-6">
            {{ $billings->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function filterBillings(status) {
    const cards = document.querySelectorAll('.billing-card');
    const tabs = document.querySelectorAll('.filter-tab');
    
    // Update tab styles
    tabs.forEach(tab => {
        tab.classList.remove('border-blue-600', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-600');
    });
    event.target.classList.add('border-blue-600', 'text-blue-600');
    event.target.classList.remove('border-transparent');
    
    // Filter cards
    cards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection