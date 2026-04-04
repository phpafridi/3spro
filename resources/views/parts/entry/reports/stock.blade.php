@extends('parts.layout')
@section('title', 'Stock Report')
@section('content')
@include('partials.company-header')
<h2 class="text-xl font-bold text-gray-800 mb-4">Stock Report</h2>
@include('parts.entry.reports._filter', ['showType'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-red-600 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Current Stock</h3>
        <span class="text-white text-sm">Total Value: Rs <strong>{{ number_format($totalValue,0) }}</strong></span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 sticky top-0"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Stock ID</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Unit</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Category</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Location</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">P-Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">R-Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Price</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Value</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($stocks as $i => $s)
                <tr class="{{ $s->remain_qty==0 ? 'bg-red-50' : 'hover:bg-red-50' }}">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $s->stock_id }}</td>
                    <td class="px-3 py-2 font-medium">{{ $s->part_no }}</td>
                    <td class="px-3 py-2 text-xs text-gray-600">{{ $s->Description }}</td>
                    <td class="px-3 py-2 text-xs">{{ $s->unit }}</td>
                    <td class="px-3 py-2 text-xs">{{ $s->cate_type }}</td>
                    <td class="px-3 py-2 text-xs">{{ $s->Location }}</td>
                    <td class="px-3 py-2 text-right">{{ $s->quantity }}</td>
                    <td class="px-3 py-2 text-right {{ $s->remain_qty==0 ? 'text-red-500 font-bold' : 'text-green-600' }}">{{ $s->remain_qty }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($s->Price,2) }}</td>
                    <td class="px-3 py-2 text-right font-medium">{{ number_format($s->stock_value,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="11" class="px-4 py-8 text-center text-gray-400">No stock data</td></tr>
                @endforelse
                @if($stocks->count())
                <tr class="bg-red-600 font-bold">
                    <td colspan="10" class="px-3 py-2 text-right text-white">Total Stock Value</td>
                    <td class="px-3 py-2 text-right text-white">Rs {{ number_format($totalValue,0) }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
