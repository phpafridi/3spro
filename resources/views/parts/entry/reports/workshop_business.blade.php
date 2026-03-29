@extends('parts.layout')
@section('title','Workshop Business')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Workshop Business Summary</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-orange-500 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Daily Workshop Business</h3>
        <span class="text-sm text-white">Grand Total: Rs {{ number_format($grandTotal,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Date</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">ROs</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Total</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-orange-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->sale_date }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->ros }}</td>
                    <td class="px-3 py-2 text-right font-semibold">Rs {{ number_format($r->total,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
