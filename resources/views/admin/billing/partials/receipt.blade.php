@if($billing)
<div class="text-left mb-2">
    <h1 class="text-xl font-bold uppercase">Jeffrey Dental Laboratory</h1>
    <p class="leading-tight text-[11px]">
        Zone 7 Bulua District 1<br />
        9000 Cagayan de Oro City (CAPITAL)<br />
        Misamis Oriental Philippines<br />
        <strong>JEFFREY D. GELLANGAO - PROP.</strong><br />
        Non Vat Reg., TIN 408-400-984-00000
    </p>
</div>

<div class="flex justify-between mb-2">
    <div class="text-lg font-semibold">Delivery Receipt</div>
    <div class="flex space-x-6">
        <div>Billing No: <span class="font-bold text-red-600">{{ $billing->id }}</span></div>
        <div>Date: <span class="border-b border-black inline-block min-w-[120px]">
                {{ $billing->created_at->format('F j, Y g:ia') }}
            </span></div>
    </div>
</div>

@php
$appointment = $billing->appointment;
$caseOrder = $appointment->caseOrder ?? null;
$material = $appointment->material ?? null;

$dentist = $caseOrder && $caseOrder->dentist ? $caseOrder->dentist->name : 'N/A';
$clinic = $caseOrder && $caseOrder->clinic ? $caseOrder->clinic->clinic_name : 'N/A';
$clinicAddress = $caseOrder && $caseOrder->clinic ? $caseOrder->clinic->address : 'N/A';
$technician = $appointment->technician ? $appointment->technician->name : 'N/A';
@endphp



<div class="mb-2">
    <div>Delivered to:
        <span class="border-b border-black inline-block min-w-[200px]">
            {{ $dentist }}
            @if($dentist && $clinic) / @endif
            {{ $clinic }}
        </span>
    </div>
    <div>Address:
        <span class="inline-block border-b border-black min-w-[300px]">
            {{ $clinicAddress }}
        </span>
    </div>
</div>

<table class="w-full text-left border-t border-b border-black mb-2">
    <thead>
        <tr class="border-b border-black">
            <th class="w-10 py-1">Qty</th>
            <th class="w-12">Unit</th>
            <th>Description</th>
            <th class="w-20 text-right">U-Price</th>
            <th class="w-20 text-right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @if($material)
        <tr class="border-b border-gray-300">
            <td class="py-1">1</td>
            <td>pc</td>
            <td>{{ $material->material_name }}</td>
            <td class="text-right">
                @if($material->price)
                {{ number_format($material->price, 2) }}
                @else
                {{ number_format($billing->total_amount, 2) }}
                @endif
            </td>
            <td class="text-right">{{ number_format($billing->total_amount, 2) }}</td>
        </tr>
        @else
        <tr class="border-b border-gray-300">
            <td class="py-1">1</td>
            <td>pc</td>
            <td>Service / Product</td>
            <td class="text-right">{{ number_format($billing->total_amount, 2) }}</td>
            <td class="text-right">{{ number_format($billing->total_amount, 2) }}</td>
        </tr>
        @endif

        <tr>
            <td colspan="4" class="text-right font-semibold py-1">Subtotal</td>
            <td class="text-right">{{ number_format($billing->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="4" class="text-right font-semibold py-1">VAT (12%)</td>
            <td class="text-right">{{ number_format($billing->total_amount * 0.12, 2) }}</td>
        </tr>
        <tr class="border-t border-black">
            <td colspan="4" class="text-right font-bold py-2">Total Amount</td>
            <td class="text-right font-bold text-lg">{{ number_format($billing->total_amount * 1.12, 2) }}</td>
        </tr>
    </tbody>
</table>

<div class="flex justify-between mt-6 text-sm">
    <div class="text-left">
        <div>Prepared by:</div>
        <div class="h-16 border-t border-black mt-2 pt-1">
            <div class="text-center font-semibold">{{ $technician }}</div>
            <div class="text-xs text-center">Technician</div>
        </div>
    </div>
    <div class="text-right">
        <p class="leading-tight mb-2">
            Received the above merchandise<br />
            in good order and condition
        </p>
        <div>Delivered by:
            <div class="h-6"></div>
            <div class="text-center text-xs font-semibold">
                @if($appointment->delivery && $appointment->delivery->rider)
                {{ $appointment->delivery->rider->name }}
                @else
                Not Assigned
                @endif
            </div>
            <div class="border-t border-black w-40 text-center text-xs mt-1">Rider / Delivery</div>
        </div>
    </div>
</div>

<div class="mt-4 text-xs text-gray-600">
    <p><strong>Notes:</strong> This is a computer-generated receipt. Please retain for your records.</p>
    <p><strong>Terms:</strong> Payment due upon receipt. 12% VAT included in total amount.</p>
</div>
@else
<div class="text-center py-8 text-gray-500">
    <p>Billing record not found.</p>
</div>
@endif