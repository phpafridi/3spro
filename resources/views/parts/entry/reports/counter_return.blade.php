@extends("parts.layout")
@section("title","Counter Return Report")
@section("content")
<h2 class="text-xl font-bold text-gray-800 mb-4">Counter Return Report</h2>
@include("parts.entry.reports._filter",["showDates"=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-red-600 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Returns</h3>
        <span class="text-sm text-white">Total: Rs {{ number_format($total,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Invoice</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Customer</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Date</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Qty</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Amount</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->invoice_no }}</td>
                    <td class="px-3 py-2">{{ $r->return_by }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->datetime }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->return_qty }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->unit_price * $r->return_qty,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
