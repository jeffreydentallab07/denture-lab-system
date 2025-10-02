@extends('layouts.app')

@section('title', 'Billings Report')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen text-sm">
    <h1 class="text-xl font-bold mb-4">💰 Billings Report</h1>

   
    <form id="filterForm" class="flex flex-wrap gap-4 mb-6">

    
        <div>
            <label class="block text-gray-600 text-xs mb-1">Clinic</label>
            <select name="clinic_id" id="clinic_id" class="p-2 border rounded auto-submit">
                <option value="">All Clinics</option>
                @foreach($clinics as $clinic)
                    <option value="{{ $clinic->clinic_id }}" {{ request('clinic_id') == $clinic->clinic_id ? 'selected' : '' }}>
                        {{ $clinic->clinic_name }}
                    </option>
                @endforeach
            </select>
        </div>

       
        <div>
            <label class="block text-gray-600 text-xs mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="border p-2 rounded w-44 auto-submit">
        </div>

      
        <div>
            <label class="block text-gray-600 text-xs mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="border p-2 rounded w-44 auto-submit">
        </div>

       
        <div class="flex gap-2 items-end">
            <button type="button" id="resetBtn" class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600">
                Reset
            </button>
        </div>
    </form>

   
    <div id="reportTable">
       <table class="min-w-full border-separate border-spacing-0">
        <thead>
            <tr class="bg-blue-900 text-white">
                    <th class="px-4 py-2 text-left text-sm font-medium">#</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Clinic</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Appointment ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Created At</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($billings as $billing)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $billing->billing_id }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $billing->appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $billing->appointment_id }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $billing->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ number_format($billing->total_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-left text-sm font-medium text-center text-gray-500">No billings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

       
        <div class="mt-4">
            {{ $billings->links() }}
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterForm = document.getElementById('filterForm');
    const reportTable = document.getElementById('reportTable');
    const resetBtn = document.getElementById('resetBtn');


    filterForm.querySelectorAll('select, input[type="date"]').forEach(el => {
        el.addEventListener('change', applyFilter);
    });

    resetBtn.addEventListener('click', function() {
        filterForm.reset();
        applyFilter();
    });

    function applyFilter(page = 1) {
        const params = new URLSearchParams(new FormData(filterForm)).toString();
        fetch(`{{ route('reports.billings') }}?${params}&page=${page}`, { 
            headers: { "X-Requested-With": "XMLHttpRequest" } 
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            reportTable.innerHTML = doc.querySelector('#reportTable').innerHTML;
            bindPagination();
        })
        .catch(err => console.error("AJAX Filter Error:", err));
    }

    function bindPagination() {
        document.querySelectorAll('#reportTable .pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = new URL(this.href).searchParams.get('page');
                applyFilter(page);
            });
        });
    }

    bindPagination();
});
</script>
@endsection
