@extends('parts.layout')
@section('title','Purchase Profit Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Purchase Profit Report — GRN# {{ $grn }}</h2>
<form method="GET" class="flex gap-2 mb-4">
    <input name="grn" value="{{ $grn }}" placeholder="Enter GRN / Invoice #"
           class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-400 w-52">
    <button class="bg-red-600 text-white px-4 py-1.5 rounded text-sm hover:bg-red-700">Search</button>
</form>
@if($grn)
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-blue-600 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Purchase Details</h3>
        <span class="text-sm text-white">Total Amount: Rs {{ number_format($totalSale,0) }} | Profit: Rs {{ number_format($totalProfit,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Invoice #</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Supplier</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Date</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-blue-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->Invoice_number ?? $r->Invoice_no }}</td>
                    <td class="px-3 py-2">{{ $r->jobber }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->mdate }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->Total_amount, 0) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400">No records found for this GRN</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection