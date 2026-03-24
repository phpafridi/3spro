@extends('parts.layout')
@section('title', 'Purchase Detail — GRN #' . $invoice->Invoice_no)
@section('content')

<div class="mb-4 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold text-gray-800">GRN #{{ $invoice->Invoice_no }} — {{ $invoice->jobber }}</h2>
        <p class="text-sm text-gray-500">Bill: {{ $invoice->Invoice_number }} &bull; PR: {{ $invoice->Purchase_Requis }} &bull; {{ $invoice->payment_method }}</p>
    </div>
    <a href="{{ route('parts.purchase.detail.view', $invoice->Invoice_no) }}"
       class="px-4 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700">View Invoice</a>
</div>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-800 text-sm">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-800 text-sm">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- ADD PART FORM --}}
    <div class="bg-white rounded shadow-sm border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Add Part to GRN #{{ $invoice->Invoice_no }}</h3>

        <form action="{{ route('parts.purchase.detail.store', $invoice->Invoice_no) }}" method="POST">
            @csrf

            {{-- Part number with autocomplete --}}
            <div class="mb-3 relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Part Number <span class="text-red-500">*</span></label>
                <input type="text" name="typeahead" id="partSearch" required autocomplete="off"
                       placeholder="Type part number..."
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <div id="partDropdown" class="ac-dropdown hidden"></div>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <input type="text" name="desc" id="partDesc"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" id="partCategory"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">-- Select --</option>
                        <option value="TGMO">TGMO</option>
                        <option value="Chemical">Chemical</option>
                        <option value="Accessories">Accessories</option>
                        <option value="KMP">KMP</option>
                        <option value="Body&amp;Paint">Body&amp;Paint</option>
                        <option value="IMC-imported">IMC-imported</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <select name="unit" id="partUnit"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="Pcs">Pcs</option>
                        <option value="Ltr">Ltr</option>
                        <option value="Set">Set</option>
                        <option value="Kg">Kg</option>
                        <option value="Mtr">Mtr</option>
                        <option value="Pair">Pair</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 mb-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty <span class="text-red-500">*</span></label>
                    <input type="number" name="required_qty" id="qty" required min="0.01" step="0.01"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                           oninput="calcNet()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price <span class="text-red-500">*</span></label>
                    <input type="number" name="required_uprice" id="uprice" required min="0" step="0.01"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                           oninput="calcNet()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Net Amount</label>
                    <input type="text" id="netDisplay" readonly
                           class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 text-sm text-gray-600">
                    <input type="hidden" name="required_netprice" id="netprice">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-medium text-sm transition-colors">
                Add Part
            </button>
        </form>
    </div>

    {{-- ITEMS TABLE --}}
    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-red-600">
            <h3 class="font-semibold text-white">Invoice Items</h3>
            <span class="text-sm text-white">Total: <strong>{{ number_format($stocks->sum('Netamount'), 2) }}</strong></span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Desc</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Cate</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">R-Qty</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Price</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Net</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($stocks as $i => $s)
                    <tr class="hover:bg-red-50">
                        <td class="px-3 py-2 text-gray-500">{{ $i+1 }}</td>
                        <td class="px-3 py-2 font-medium text-gray-800">{{ $s->part_no }}</td>
                        <td class="px-3 py-2 text-xs text-gray-500">{{ $s->Description }}</td>
                        <td class="px-3 py-2 text-xs">{{ $s->cate_type }}</td>
                        <td class="px-3 py-2 text-right">{{ $s->quantity }}</td>
                        <td class="px-3 py-2 text-right {{ $s->remain_qty == 0 ? 'text-red-500' : 'text-green-600' }}">{{ $s->remain_qty }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($s->Price, 2) }}</td>
                        <td class="px-3 py-2 text-right font-medium">{{ number_format($s->Netamount, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No parts added yet</td></tr>
                    @endforelse
                    @if($stocks->count())
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="7" class="px-3 py-2 text-right text-sm">Grand Total</td>
                        <td class="px-3 py-2 text-right text-sm text-red-600">{{ number_format($stocks->sum('Netamount'), 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
.ac-dropdown { position:absolute; background:white; border:1px solid #d1d5db; border-top:none;
  border-radius:0 0 6px 6px; box-shadow:0 4px 12px rgba(0,0,0,0.12); z-index:9999;
  width:100%; max-height:200px; overflow-y:auto; }
.ac-dropdown div { padding:8px 12px; cursor:pointer; font-size:13px; }
.ac-dropdown div:hover { background:#ef4444; color:white; }
</style>
@endpush

@push('scripts')
<script>
function calcNet() {
    var qty    = parseFloat(document.getElementById('qty').value) || 0;
    var uprice = parseFloat(document.getElementById('uprice').value) || 0;
    var net    = (qty * uprice).toFixed(2);
    document.getElementById('netDisplay').value = net;
    document.getElementById('netprice').value   = net;
}

// Part number autocomplete — searches p_parts
var partTimer;
var partInput = document.getElementById('partSearch');
var partDrop  = document.getElementById('partDropdown');

partInput.addEventListener('input', function() {
    clearTimeout(partTimer);
    var val = this.value;
    if (val.length < 2) { partDrop.classList.add('hidden'); return; }
    partTimer = setTimeout(function() {
        axios.get('{{ route("parts.ajax.search-part") }}', { params: { key: val } })
            .then(function(res) {
                partDrop.innerHTML = '';
                if (!res.data.length) { partDrop.classList.add('hidden'); return; }
                res.data.forEach(function(p) {
                    var row = document.createElement('div');
                    row.textContent = p.value + (p.desc ? ' — ' + p.desc : '');
                    row.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        partInput.value = p.value;
                        document.getElementById('partDesc').value     = p.desc || '';
                        // Set category if matched
                        var catSel = document.getElementById('partCategory');
                        for (var i=0; i<catSel.options.length; i++) {
                            if (catSel.options[i].value === p.category) {
                                catSel.selectedIndex = i; break;
                            }
                        }
                        partDrop.classList.add('hidden');
                    });
                    partDrop.appendChild(row);
                });
                partDrop.classList.remove('hidden');
            });
    }, 300);
});

document.addEventListener('click', function(e) {
    if (!partInput.contains(e.target) && !partDrop.contains(e.target)) {
        partDrop.classList.add('hidden');
    }
});
</script>
@endpush
@endsection
