@extends('parts.layout')
@section('title','Moving Stock — MAD')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Monthly Average Demand ({{ $months }} months)</h2>
@include('parts.entry.reports._filter',['showDates'=>false])
<form method="GET" class="flex gap-2 mb-4">
    <label class="text-sm text-gray-600 self-center">Months:</label>
    <input type="number" name="months" value="{{ $months }}" min="1" max="24"
           class="border border-gray-300 rounded px-3 py-1.5 text-sm w-20 focus:outline-none focus:border-red-400">
    <button class="bg-red-600 text-white px-4 py-1.5 rounded text-sm hover:bg-red-700">Apply</button>
</form>
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-emerald-600 p-3"><h3 class="font-semibold text-white">Average Monthly Demand</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Total Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">MAD</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-emerald-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->part_no }}</td>
                    <td class="px-3 py-2">{{ $r->Description }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->total_qty) }}</td>
                    <td class="px-3 py-2 text-right font-semibold text-blue-700">{{ $r->avg_monthly }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
