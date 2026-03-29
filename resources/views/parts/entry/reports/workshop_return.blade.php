@extends('parts.layout')
@section('title','Workshop Return')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Workshop Returned Parts</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-orange-500 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">{{ $natureType }} Returns</h3>
        <span class="text-sm text-white">Returned Amount: Rs {{ number_format($totalReturn,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Jobcard#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Amount</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-orange-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->jobcard_no ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $r->part_no ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $r->Description ?? '—' }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->quantity ?? 0 }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->netamount ?? 0,0) }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->return_date ?? '' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
