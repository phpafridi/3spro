@extends('parts.layout')
@section('title','Purchase Profit Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Purchase Profit Report — GRN# {{ $grn }}</h2>
<form method="GET" class="flex gap-2 mb-4">
    <input name="grn" value="{{ $grn }}" placeholder="Enter GRN / Invoice #"
           class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-400 w-52">
    <button class="bg-red-600 text-white px-4 py-1.5 rounded text-sm hover:bg-red-700">Search</button>
</form>
@if($grn)
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-blue-600 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Profit Detail</h3>
        <span class="text-sm text-white">Total Sale: Rs {{ number_format($totalSale,0) }} | Profit: Rs {{ number_format($totalProfit,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Cost</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Sale</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Profit</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                @php $profit = ($r->sale_price - $r->cost_price) * $r->quantity; @endphp
                <tr class="hover:bg-blue-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->part_no ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $r->Description ?? '—' }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->quantity ?? 0 }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->cost_price ?? 0,0) }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->sale_price ?? 0,0) }}</td>
                    <td class="px-3 py-2 text-right {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">Rs {{ number_format($profit,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-3 py-6 text-center text-gray-400">No records found for this GRN</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
