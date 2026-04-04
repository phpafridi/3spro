@extends('parts.layout')
@section('title','Part Wise Stock')
@section('content')
@include('partials.company-header')
<h2 class="text-xl font-bold text-gray-800 mb-4">Part Wise Stock Details</h2>
<form method="GET" class="flex gap-2 mb-4">
    <input name="part_no" value="{{ $partNo }}" placeholder="Enter Part Number"
           class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-400 w-52">
    <button class="bg-red-600 text-white px-4 py-1.5 rounded text-sm hover:bg-red-700">Search</button>
</form>
@if($partNo)
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-emerald-600 p-3"><h3 class="font-semibold text-white">Stock: {{ $partNo }}</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Stock ID</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Price</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Value</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-emerald-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->stock_id }}</td>
                    <td class="px-3 py-2">{{ $r->Description }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->Quantity }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->Price,0) }}</td>
                    <td class="px-3 py-2 text-right font-medium">Rs {{ number_format($r->Quantity * $r->Price,0) }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->purch_date ?? '' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-3 py-6 text-center text-gray-400">No stock found for this part</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
