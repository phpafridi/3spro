@extends('parts.layout')
@section('title','Workshop Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Workshop Report</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-orange-500 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Workshop Business</h3>
        <span class="text-sm text-white">Total Business: Rs {{ number_format($totalSale,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Jobcard#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Customer</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Vehicle</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Parts</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Amount</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-orange-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->jobcard_no }}</td>
                    <td class="px-3 py-2">{{ $r->customer_name }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->vehicle_no }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->parts_count }}</td>
                    <td class="px-3 py-2 text-right font-semibold">Rs {{ number_format($r->total_amount,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
