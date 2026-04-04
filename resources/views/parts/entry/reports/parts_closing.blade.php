@extends('parts.layout')
@section('title','Parts Workshop Sales Business')
@section('content')
@include('partials.company-header')
<h2 class="text-xl font-bold text-gray-800 mb-4">Parts Workshop Sales Business</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-orange-500 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Part-wise Workshop Sales</h3>
        <span class="text-sm text-white">Sale: Rs {{ number_format($totalSale,0) }} | Cost: Rs {{ number_format($totalCost,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Sale</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Cost</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Profit</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                @php $profit = $r->sale - $r->cost; @endphp
                <tr class="hover:bg-orange-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->part_no }}</td>
                    <td class="px-3 py-2">{{ $r->Description }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->qty) }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->sale,0) }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->cost,0) }}</td>
                    <td class="px-3 py-2 text-right {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">Rs {{ number_format($profit,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
