@extends('parts.layout')
@section('title','Purchase Credit/Cash Report')
@section('content')
@include('partials.company-header')
<h2 class="text-xl font-bold text-gray-800 mb-4">{{ $reportType }} Purchase Report</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-blue-600 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">{{ $reportType }} Purchased Report</h3>
        <span class="text-sm text-white">Grand Total: Rs {{ number_format($grandTotal,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"> 
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Invoice#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Supplier</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Date</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Amount</th>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-blue-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->Invoice_no ?? $r->Invoice_number ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $r->jobber ?? '—' }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->mdate ?? $r->date ?? '' }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->Total_amount ?? 0, 0) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection