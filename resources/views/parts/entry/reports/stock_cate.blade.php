@extends('parts.layout')
@section('title','Stock by Category')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Stock Category Report</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-emerald-600 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Purchase History by Category</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Category</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Total Parts</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Stock Value</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-emerald-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->cate_type ?: 'N/A' }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->total_parts) }}</td>
                    <td class="px-3 py-2 text-right font-semibold">Rs {{ number_format($r->total_value,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
