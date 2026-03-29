@extends('parts.layout')
@section('title','MG vs Local Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">MG vs Local Parts Report</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-purple-600 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Category Summary</h3>
        <span class="text-sm text-white">Total: Rs {{ number_format($totalSale,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Category</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Sale</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">% Share</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-purple-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->cate_type ?: 'N/A' }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->qty) }}</td>
                    <td class="px-3 py-2 text-right font-semibold">Rs {{ number_format($r->sale,0) }}</td>
                    <td class="px-3 py-2 text-right text-blue-700">{{ $totalSale > 0 ? number_format($r->sale / $totalSale * 100,1) : 0 }}%</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
