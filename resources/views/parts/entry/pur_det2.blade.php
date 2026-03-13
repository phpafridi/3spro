{{-- resources/views/parts/entry/pur_det2.blade.php --}}
@extends('parts.layout')
@section('title', 'Invoice View - Parts')
@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Invoice #{{ $invoice->Invoice_no }}</h2>
        <p class="text-sm text-gray-500">
            Jobber: <strong>{{ $invoice->jobber }}</strong> &bull;
            Bill#: {{ $invoice->Invoice_number }} &bull;
            Date: {{ $invoice->mdate ? \Carbon\Carbon::parse($invoice->mdate)->format('d M Y') : '-' }}
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('parts.purchase.detail', $invoice->Invoice_no) }}"
           class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-xl text-sm hover:bg-indigo-200 transition-colors">
            + Add Parts
        </a>
        <a href="{{ route('parts.print.purchase', $invoice->Invoice_no) }}" target="_blank"
           class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm hover:bg-indigo-700 transition-colors">
            Print Invoice
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Payment Method</p>
        <p class="font-semibold text-gray-800">{{ $invoice->payment_method }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Purchase Requisition</p>
        <p class="font-semibold text-gray-800">{{ $invoice->Purchase_Requis }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total Amount</p>
        <p class="font-semibold text-gray-800 text-xl">{{ number_format($invoice->Total_amount ?? 0, 2) }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Part No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Unit</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Qty</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Remain</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Price</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Net Amt</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoice->stockItems as $i => $item)
                <tr class="hover:bg-indigo-50/30">
                    <td class="px-4 py-3 text-gray-500">{{ $i+1 }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $item->part_no }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $item->Description }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $item->cate_type }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $item->unit }}</td>
                    <td class="px-4 py-3 text-right">{{ $item->quantity }}</td>
                    <td class="px-4 py-3 text-right {{ $item->remain_qty <= 0 ? 'text-red-600' : 'text-green-700' }}">{{ $item->remain_qty }}</td>
                    <td class="px-4 py-3 text-right">{{ number_format($item->Price, 2) }}</td>
                    <td class="px-4 py-3 text-right font-semibold">{{ number_format($item->Netamount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="8" class="px-4 py-3 text-right font-bold text-gray-700">Total:</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-900 text-base">{{ number_format($invoice->Total_amount ?? 0, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
