@extends('parts.layout')
@section('title', 'Part-Wise Sale Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Part-Wise Sale — {{ $from }} to {{ $to }}</h2>
@include('parts.entry.reports._filter', ['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-red-600 p-3 flex justify-between">
        <h3 class="font-semibold text-white">{{ $parts->count() }} parts sold</h3>
        <span class="text-white text-sm">Total Sale: Rs {{ number_format($totalSale,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Total Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Avg Price</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Total Sale</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Cost</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Profit</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($parts as $i => $p)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $p->part_no }}</td>
                    <td class="px-3 py-2 text-xs text-gray-600">{{ $p->Description }}</td>
                    <td class="px-3 py-2 text-right">{{ $p->total_qty }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($p->avg_price,2) }}</td>
                    <td class="px-3 py-2 text-right font-medium">{{ number_format($p->total_sale,0) }}</td>
                    <td class="px-3 py-2 text-right text-gray-500">{{ number_format($p->total_cost,0) }}</td>
                    <td class="px-3 py-2 text-right {{ $p->profit >= 0 ? 'text-green-600' : 'text-red-500' }} font-medium">{{ number_format($p->profit,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No sale data</td></tr>
                @endforelse
                @if($parts->count())
                <tr class="bg-red-600 font-bold">
                    <td colspan="5" class="px-3 py-2 text-right text-white">Totals</td>
                    <td class="px-3 py-2 text-right text-white">{{ number_format($totalSale,0) }}</td>
                    <td class="px-3 py-2 text-right text-white">{{ number_format($totalCost,0) }}</td>
                    <td class="px-3 py-2 text-right text-white">{{ number_format($totalSale-$totalCost,0) }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
