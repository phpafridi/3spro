{{-- resources/views/parts/entry/pur_det.blade.php --}}
@extends('parts.layout')

@section('title', 'Purchase Detail - Parts')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Purchase Invoice #{{ $invoice->Invoice_no }}</h2>
        <p class="text-sm text-gray-500 mt-1">
            Jobber: <strong>{{ $invoice->jobber }}</strong> &bull;
            PR: {{ $invoice->Purchase_Requis }} &bull;
            {{ $invoice->payment_method }}
        </p>
    </div>
    <a href="{{ route('parts.purchase.detail.view', $invoice->Invoice_no) }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm hover:bg-indigo-700 transition-colors">
        View Invoice
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Add Part Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Part to Invoice</h3>

        <form action="{{ route('parts.purchase.detail.store', $invoice->Invoice_no) }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Part Number <span class="text-red-500">*</span></label>
                    <input type="text" name="typeahead" id="partSearch" required autocomplete="off"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500"
                           placeholder="Search part number...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" name="desc" id="partDesc"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" name="category"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <input type="text" name="unit" value="Pcs"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="required_qty" id="qty" required min="0.01" step="0.01"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500"
                               oninput="calcNet()">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price <span class="text-red-500">*</span></label>
                        <input type="number" name="required_uprice" id="uprice" required min="0" step="0.01"
                               class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500"
                               oninput="calcNet()">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Net Amount</label>
                        <input type="text" id="netAmountDisplay" readonly value="0.00"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-600">
                        <input type="hidden" name="required_netprice" id="netprice" value="0">
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2.5 rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 transition-all">
                    Add Part
                </button>
            </div>
        </form>
    </div>

    {{-- Current Items --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Invoice Items</h3>
            <span class="text-sm text-gray-500">Total: <strong>{{ number_format($stocks->sum('Netamount'), 2) }}</strong></span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs text-gray-500">Part #</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500">Qty</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500">Price</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500">Net</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($stocks as $stock)
                    <tr class="hover:bg-indigo-50/30">
                        <td class="px-3 py-2">
                            <p class="font-medium text-gray-800">{{ $stock->part_no }}</p>
                            <p class="text-xs text-gray-400">{{ $stock->Description }}</p>
                        </td>
                        <td class="px-3 py-2">{{ $stock->quantity }}</td>
                        <td class="px-3 py-2">{{ number_format($stock->Price, 2) }}</td>
                        <td class="px-3 py-2 font-medium">{{ number_format($stock->Netamount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-xs">No parts added yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calcNet() {
    const qty    = parseFloat(document.getElementById('qty').value) || 0;
    const uprice = parseFloat(document.getElementById('uprice').value) || 0;
    const net    = (qty * uprice).toFixed(2);
    document.getElementById('netAmountDisplay').value = net;
    document.getElementById('netprice').value = net;
}

// Typeahead for part search
const partSearch = document.getElementById('partSearch');
let timeout;
partSearch.addEventListener('input', function() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        fetch('{{ route("parts.ajax.search-part") }}?key=' + encodeURIComponent(this.value))
            .then(r => r.json())
            .then(data => {
                // Simple datalist fallback
                let list = document.getElementById('partList');
                if (!list) {
                    list = document.createElement('datalist');
                    list.id = 'partList';
                    document.body.appendChild(list);
                    partSearch.setAttribute('list', 'partList');
                }
                list.innerHTML = data.map(p => `<option value="${p.value}">${p.label}</option>`).join('');
            });
    }, 300);
});
</script>
@endpush
