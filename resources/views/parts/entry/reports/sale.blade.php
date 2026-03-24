@extends('parts.layout')
@section('title', 'Sale Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Sale Report — {{ $from }} to {{ $to }}</h2>
@include('parts.entry.reports._filter', ['showDates'=>true, 'showVendor'=>true])
<div class="space-y-4">
    @forelse($invoices as $inv)
    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-100 p-3 flex justify-between items-center">
            <div>
                <span class="font-bold text-gray-800">Sale Inv #{{ $inv->sale_inv }}</span>
                <span class="ml-3 text-sm text-gray-500">{{ $inv->Jobber }}</span>
                <span class="ml-3 text-sm text-gray-500">{{ $inv->payment_method }}</span>
            </div>
            <span class="font-bold text-red-600">Rs {{ number_format($inv->items->sum('netamount'),0) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Price</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Net</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($inv->items as $item)
                    <tr class="hover:bg-red-50">
                        <td class="px-3 py-1.5 font-medium">{{ $item->part_no }}</td>
                        <td class="px-3 py-1.5 text-xs text-gray-600">{{ $item->Description }}</td>
                        <td class="px-3 py-1.5 text-right">{{ $item->quantity }}</td>
                        <td class="px-3 py-1.5 text-right">{{ number_format($item->sale_price,2) }}</td>
                        <td class="px-3 py-1.5 text-right font-medium">{{ number_format($item->netamount,0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white rounded p-8 text-center text-gray-400">No sale data for selected period</div>
    @endforelse
    @if($invoices->count())
    <div class="bg-red-600 rounded p-4 text-white flex justify-between">
        <span class="font-bold text-lg">Grand Total</span>
        <span class="font-bold text-xl">Rs {{ number_format($grandTotal,0) }}</span>
    </div>
    @endif
</div>
@endsection
