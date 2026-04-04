@extends('parts.layout')
@section('title','PMGR Report')
@section('content')
@include('partials.company-header')
<h2 class="text-xl font-bold text-gray-800 mb-4">Workshop PMGR Report</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="grid grid-cols-3 gap-3 mb-4">
    <div class="bg-white border border-gray-200 rounded p-3 text-center">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total ROs</p>
        <p class="text-2xl font-bold text-orange-600">{{ number_format($totalROs) }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded p-3 text-center">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Qty</p>
        <p class="text-2xl font-bold text-blue-600">{{ number_format($totalQty) }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded p-3 text-center">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Business</p>
        <p class="text-2xl font-bold text-green-600">Rs {{ number_format($totalSum,0) }}</p>
    </div>
</div>
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-orange-500 p-3"><h3 class="font-semibold text-white">Parts & Consumables</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Jobcard#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Customer</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Vehicle</th>
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
                    <td class="px-3 py-2 font-medium">{{ $r->jobcard_no }}</td>
                    <td class="px-3 py-2">{{ $r->customer_name }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->vehicle_no }}</td>
                    <td class="px-3 py-2">{{ $r->part_no }}</td>
                    <td class="px-3 py-2">{{ $r->Description }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->quantity }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->netamount + $r->tax,0) }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->issue_date }}</td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
