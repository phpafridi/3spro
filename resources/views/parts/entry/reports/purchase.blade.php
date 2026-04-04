@extends('parts.layout')
@section('title', 'Purchase Report')
@section('content')
@include('partials.company-header')
<h2 class="text-xl font-bold text-gray-800 mb-4">Purchase Report — {{ $from }} to {{ $to }}</h2>
@include('parts.entry.reports._filter', ['showDates'=>true, 'showVendor'=>true])
<div class="space-y-4">
    @forelse($invoices as $inv)
    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-100 p-3 flex justify-between items-center">
            <div>
                <span class="font-bold text-gray-800">GRN #{{ $inv->Invoice_no }}</span>
                <span class="ml-3 text-sm text-gray-500">Bill: {{ $inv->Invoice_number }}</span>
                <span class="ml-3 text-sm text-gray-500">Jobber: {{ $inv->jobber }}</span>
                <span class="ml-3 text-sm text-gray-500">{{ $inv->payment_method }}</span>
            </div>
            <span class="font-bold text-red-600">Rs {{ number_format($inv->items->sum('Netamount'),0) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Unit</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">R-Qty</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Price</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Net</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($inv->items as $item)
                    <tr class="hover:bg-red-50">
                        <td class="px-3 py-1.5 font-medium">{{ $item->part_no }}</td>
                        <td class="px-3 py-1.5 text-xs text-gray-600">{{ $item->Description }}</td>
                        <td class="px-3 py-1.5 text-xs">{{ $item->unit }}</td>
                        <td class="px-3 py-1.5 text-right">{{ $item->quantity }}</td>
                        <td class="px-3 py-1.5 text-right {{ $item->remain_qty==0 ? 'text-red-500' : 'text-green-600' }}">{{ $item->remain_qty }}</td>
                        <td class="px-3 py-1.5 text-right">{{ number_format($item->Price,2) }}</td>
                        <td class="px-3 py-1.5 text-right font-medium">{{ number_format($item->Netamount,0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white rounded p-8 text-center text-gray-400">No purchase data for selected period</div>
    @endforelse
    @if($invoices->count())
    <div class="bg-red-600 rounded p-4 text-white flex justify-between">
        <span class="font-bold">Grand Total Purchase: Rs {{ number_format($totalPurchase,0) }}</span>
        <span class="font-bold">Total Returns: Rs {{ number_format($totalReturn,0) }}</span>
        <span class="font-bold">Net: Rs {{ number_format($totalPurchase-$totalReturn,0) }}</span>
    </div>
    @endif
</div>
@endsection
