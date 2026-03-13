@extends('parts.layout')
@section('title', 'Sale Invoice - Parts')
@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Sale Invoice #{{ $invoice->sale_inv }}</h2>
        <p class="text-sm text-gray-500">Customer: <strong>{{ $invoice->Jobber }}</strong> &bull; {{ $invoice->payment_method }}</p>
    </div>
    <a href="{{ route('parts.print.sale-invoice', $invoice->sale_inv) }}" target="_blank"
       class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm hover:bg-indigo-700 transition-colors">Print Invoice</a>
</div>
@if(session('success'))<div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">{{ session('success') }}</div>@endif
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <h3 class="font-semibold text-gray-800 mb-4">Add Part</h3>
    <form action="{{ route('parts.sale.part.store') }}" method="POST">
    @csrf
    <input type="hidden" name="sale_inv" value="{{ $invoice->sale_inv }}">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Search <span class="text-red-500">*</span></label>
            <input type="text" id="stockSearch" autocomplete="off" placeholder="Search part/stock..."
                   class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
            <input type="hidden" name="stock_id" id="stockId">
            <input type="hidden" name="part_no" id="partNo">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                <input type="number" name="quantity" id="saleQty" required min="1" step="1"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500" oninput="calcSaleNet()">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price <span class="text-red-500">*</span></label>
                <input type="number" name="sale_price" id="salePrice" required min="0" step="0.01"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500" oninput="calcSaleNet()">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Net Amount</label>
            <input type="text" id="saleNetDisplay" readonly class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
        </div>
    </div>
    <div class="mt-5">
        <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-2.5 rounded-xl font-medium">Add to Sale</button>
    </div>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">Sale Items</h3>
        <span class="text-sm text-gray-500">Total: <strong>{{ number_format($parts->sum('netamount'), 2) }}</strong></span>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr>
            <th class="px-3 py-2 text-left text-xs text-gray-500">Part #</th>
            <th class="px-3 py-2 text-right text-xs text-gray-500">Qty</th>
            <th class="px-3 py-2 text-right text-xs text-gray-500">Price</th>
            <th class="px-3 py-2 text-right text-xs text-gray-500">Net</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-50">
        @forelse($parts as $part)
        <tr><td class="px-3 py-2 font-medium">{{ $part->part_no }}</td>
            <td class="px-3 py-2 text-right">{{ $part->quantity }}</td>
            <td class="px-3 py-2 text-right">{{ number_format($part->sale_price, 2) }}</td>
            <td class="px-3 py-2 text-right font-medium">{{ number_format($part->netamount, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400 text-xs">No parts added yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection
@push('scripts')
<script>
function calcSaleNet() {
    const q = parseFloat(document.getElementById('saleQty').value)||0;
    const p = parseFloat(document.getElementById('salePrice').value)||0;
    document.getElementById('saleNetDisplay').value = (q*p).toFixed(2);
}
const stockS = document.getElementById('stockSearch');
let st;
stockS.addEventListener('input', function() {
    clearTimeout(st);
    st = setTimeout(() => {
        fetch('{{ route("parts.ajax.search-stock") }}?key=' + encodeURIComponent(this.value))
            .then(r => r.json())
            .then(data => {
                let list = document.getElementById('stockList') || document.createElement('datalist');
                list.id = 'stockList';
                document.body.appendChild(list);
                stockS.setAttribute('list', 'stockList');
                list.innerHTML = data.map(s => `<option value="${s.value}" data-part="${s.part_no}" data-price="${s.price}">${s.label}</option>`).join('');
            });
    }, 300);
});
stockS.addEventListener('change', function() {
    const opt = document.querySelector(`#stockList option[value="${this.value}"]`);
    if(opt) {
        document.getElementById('stockId').value = this.value;
        document.getElementById('partNo').value = opt.dataset.part || '';
        document.getElementById('salePrice').value = opt.dataset.price || '';
    }
});
</script>
@endpush
