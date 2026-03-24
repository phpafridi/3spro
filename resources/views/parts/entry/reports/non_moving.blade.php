@extends('parts.layout')
@section('title', 'Non Moving Stock')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Non Moving Stock — No sale in {{ $months }} month(s)</h2>
@include('parts.entry.reports._filter', ['showMonths'=>true, 'showType'=>true, 'showDates'=>false])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-red-600 p-3 flex justify-between">
        <h3 class="font-semibold text-white">{{ $stocks->count() }} items</h3>
        <span class="text-white text-sm">Value: Rs {{ number_format($totalValue,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Stock ID</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Location</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">R-Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Price</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Days</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($stocks as $i => $s)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $s->stock_id }}</td>
                    <td class="px-3 py-2 font-medium text-red-600">{{ $s->part_no }}</td>
                    <td class="px-3 py-2 text-xs text-gray-600">{{ $s->Description }}</td>
                    <td class="px-3 py-2 text-xs">{{ $s->Location }}</td>
                    <td class="px-3 py-2 text-right">{{ $s->remain_qty }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($s->Price,2) }}</td>
                    <td class="px-3 py-2 text-right font-bold text-red-600">{{ $s->StockDays }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No non-moving stock</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
