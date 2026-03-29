@extends('parts.layout')
@section('title','KPI Stock Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">KPI Stock Report</h2>
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-purple-600 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Stock by Category</h3>
        <span class="text-sm text-white">Total Value: Rs {{ number_format($totalValue,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Category</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Lines</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Stock Value</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-purple-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->cate_type ?: 'N/A' }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->lines) }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->qty) }}</td>
                    <td class="px-3 py-2 text-right font-semibold">Rs {{ number_format($r->stock_value,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400">No stock found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
