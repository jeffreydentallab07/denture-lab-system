@extends('layouts.clinic')

@section('page-title', 'Case Orders')

@section('content')
<div class="py-12">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">📋 Case Orders</h1>
            <a href="{{ route('clinic.case-orders.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                + New Case Order
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
            {{ session('error') }}
        </div>
        @endif

        <!-- Filters -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('clinic.case-orders.index') }}"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by ID, type, or patient..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="in progress" {{ request('status')=='in progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="under review" {{ request('status')=='under review' ? 'selected' : '' }}>Under
                                Review</option>
                            <option value="adjustment requested" {{ request('status')=='adjustment requested'
                                ? 'selected' : '' }}>Adjustment Requested</option>
                            <option value="revision in progress" {{ request('status')=='revision in progress'
                                ? 'selected' : '' }}>Revision in Progress</option>
                            <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                            🔍 Filter
                        </button>
                        <a href="{{ route('clinic.case-orders.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                            Reset
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <!-- Case Orders Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Case ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dentist</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Technician</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($caseOrders as $caseOrder)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-blue-600">
                                    CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $caseOrder->patient->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $caseOrder->dentist->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($caseOrder->case_type) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $statusColors = [
                                'pending' => 'bg-gray-100 text-gray-800', 'for appointment' => 'bg-blue-50
                                text-blue-700',
                                'in progress' => 'bg-blue-100 text-blue-800',
                                'under review' => 'bg-purple-100 text-purple-800',
                                'adjustment requested' => 'bg-orange-100 text-orange-800',
                                'revision in progress' => 'bg-yellow-100 text-yellow-800',
                                'completed' => 'bg-green-100 text-green-800',
                                ];
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$caseOrder->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($caseOrder->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $caseOrder->latestAppointment?->technician?->name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $caseOrder->created_at->format('M j, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('clinic.case-orders.show', $caseOrder->co_id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    View
                                </a>

                                @if($caseOrder->status === 'under review')
                                <a href="{{ route('clinic.case-orders.review', $caseOrder->co_id) }}"
                                    class="text-purple-600 hover:text-purple-900 mr-3">
                                    Review
                                </a>
                                @endif

                                @if(in_array($caseOrder->status, ['pending', 'adjustment requested']))
                                <a href="{{ route('clinic.case-orders.edit', $caseOrder->co_id) }}"
                                    class="text-green-600 hover:text-green-900 mr-3">
                                    Edit
                                </a>
                                @endif

                                @if($caseOrder->status === 'pending')
                                <form action="{{ route('clinic.case-orders.destroy', $caseOrder->co_id) }}"
                                    method="POST" class="inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this case order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-lg">No case orders found</p>
                                <p class="text-sm mt-2">Create your first case order to get started</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($caseOrders->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $caseOrders->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
@endsection