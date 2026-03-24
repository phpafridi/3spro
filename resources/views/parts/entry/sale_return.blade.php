@extends('parts.layout')
@section('title', 'Sale Return — SRJV #' . $maxSRJV)
@section('content')

<div class="mb-4">
    <h2 class="text-xl font-bold text-gray-800">Sale Return — SRJV #{{ $maxSRJV }}</h2>
    <p class="text-sm text-gray-500">Enter sale invoice number to find parts, then select which to return</p>
</div>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-800 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded shadow-sm border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Sale Return Form</h3>

        <form action="{{ route('parts.sale-return.store') }}" method="POST">
            @csrf
            <input type="hidden" name="SRJV"              value="{{ $maxSRJV }}">
            <input type="hidden" name="GRN"               id="hiddenGrn">
            <input type="hidden" name="required_stock_id" id="hiddenStockId">
            <input type="hidden" name="sell_id"           id="hiddenSellId">
            <input type="hidden" name="unit_price"        id="hiddenPrice">
            <input type="hidden" name="jobber"            id="hiddenJobber">

            {{-- Step 1: Enter Sale Invoice # --}}
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sale Invoice # (SJV) <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="number" id="saleInvInput"
                           placeholder="Enter Sale Invoice #"
                           class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <button type="button" onclick="searchSaleInv()"
                            class="px-4 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700">Search</button>
                </div>
            </div>

            {{-- Invoice info --}}
            <div id="invoiceInfo" class="mb-3 p-2 bg-blue-50 border border-blue-200 rounded text-sm hidden">
                Customer: <strong id="invJobber"></strong>
            </div>

            {{-- Parts from sale invoice --}}
            <div id="saleResults" class="mb-3 hidden">
                <p class="text-xs text-gray-500 mb-1">Click a part to select for return:</p>
                <div class="overflow-x-auto border border-gray-200 rounded max-h-48 overflow-y-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-red-600 text-white sticky top-0">
                            <tr>
                                <th class="px-2 py-1 text-left">Stock ID</th>
                                <th class="px-2 py-1 text-left">Part#</th>
                                <th class="px-2 py-1 text-left">Customer</th>
                                <th class="px-2 py-1 text-right">S-Qty</th>
                                <th class="px-2 py-1 text-right">Sale Price</th>
                            </tr>
                        </thead>
                        <tbody id="saleBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Selected part info --}}
            <div id="selectedInfo" class="mb-3 p-2 bg-green-50 border border-green-200 rounded text-sm hidden">
                Selected: Part# <strong id="selPart"></strong>
                &bull; Sell ID: <strong id="selSellId"></strong>
                &bull; Price: Rs <strong id="selPrice"></strong>
                &bull; Remain: <strong id="selRemain"></strong>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Return By <span class="text-red-500">*</span></label>
                <input type="text" name="return_by" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Return Qty <span class="text-red-500">*</span></label>
                <input type="number" name="required_qty" required min="1"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                <input type="text" name="reason"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-medium text-sm">
                Record Sale Return
            </button>
        </form>
    </div>

    <div class="bg-white rounded shadow-sm border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 mb-2">Instructions</h3>
        <ol class="text-sm text-gray-600 space-y-2 list-decimal list-inside">
            <li>Enter the <strong>Sale Invoice number</strong> and click Search</li>
            <li>All parts from that invoice will appear below</li>
            <li>Click a part row to select it for return</li>
            <li>Enter return quantity and reason</li>
            <li>Click <strong>Record Sale Return</strong></li>
        </ol>
        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800">
            Note: Stock will be restored and the sale part's remaining quantity will be reduced.
        </div>
    </div>
</div>

@push('scripts')
<script>
function searchSaleInv() {
    var inv = document.getElementById('saleInvInput').value.trim();
    if (!inv) return;
    document.getElementById('hiddenGrn').value = inv;

    axios.post('{{ route("parts.ajax.search-sale-inv") }}', { partn: inv })
        .then(function(res) {
            var d = res.data;
            var tbody = document.getElementById('saleBody');
            tbody.innerHTML = '';

            // Show invoice info
            if (d.jobber) {
                document.getElementById('invJobber').textContent = d.jobber;
                document.getElementById('invoiceInfo').classList.remove('hidden');
            }

            if (!d.parts || !d.parts.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-gray-400">No returnable parts found for Invoice #' + inv + '</td></tr>';
                document.getElementById('saleResults').classList.remove('hidden');
                return;
            }

            d.parts.forEach(function(p) {
                var tr = document.createElement('tr');
                tr.className = 'cursor-pointer hover:bg-red-50 border-b border-gray-100';
                tr.innerHTML =
                    '<td class="px-2 py-2 font-medium text-red-600">' + p.stock_id + '</td>' +
                    '<td class="px-2 py-2">' + p.part_no + '</td>' +
                    '<td class="px-2 py-2 text-xs">' + (d.jobber || '') + '</td>' +
                    '<td class="px-2 py-2 text-right text-green-600">' + p.remain_qty + '</td>' +
                    '<td class="px-2 py-2 text-right">Rs:' + p.sale_price + '</td>';

                tr.addEventListener('click', function() {
                    document.getElementById('hiddenStockId').value = p.stock_id;
                    document.getElementById('hiddenSellId').value  = p.sell_id;
                    document.getElementById('hiddenPrice').value   = p.sale_price;
                    document.getElementById('hiddenJobber').value  = d.jobber || '';

                    document.getElementById('selPart').textContent   = p.part_no;
                    document.getElementById('selSellId').textContent = p.sell_id;
                    document.getElementById('selPrice').textContent  = p.sale_price;
                    document.getElementById('selRemain').textContent = p.remain_qty;
                    document.getElementById('selectedInfo').classList.remove('hidden');

                    document.querySelectorAll('#saleBody tr').forEach(function(r) {
                        r.classList.remove('bg-green-50', 'font-bold');
                    });
                    tr.classList.add('bg-green-50', 'font-bold');
                });
                tbody.appendChild(tr);
            });
            document.getElementById('saleResults').classList.remove('hidden');
        });
}

document.getElementById('saleInvInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); searchSaleInv(); }
});
</script>
@endpush
@endsection
