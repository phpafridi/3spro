@extends('parts.layout')
@section('title', 'Purchase Return — PRJV #' . $maxPRJV)
@section('content')

<div class="mb-4">
    <h2 class="text-xl font-bold text-gray-800">Purchase Return — PRJV #{{ $maxPRJV }}</h2>
    <p class="text-sm text-gray-500">Enter GRN number to find parts, then select which to return</p>
</div>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-800 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded shadow-sm border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Purchase Return Form</h3>

        <form action="{{ route('parts.purchase-return.store') }}" method="POST">
            @csrf
            <input type="hidden" name="PRJV" value="{{ $maxPRJV }}">

            {{-- Step 1: Enter GRN --}}
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">GRN Number <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="number" id="grnInput" name="GRN" required
                           placeholder="Enter GRN #"
                           class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <button type="button" onclick="searchGrn()"
                            class="px-4 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700">Search</button>
                </div>
            </div>

            {{-- Stock results from GRN --}}
            <div id="grnResults" class="mb-3 hidden">
                <p class="text-xs text-gray-500 mb-1">Click a row to select the part to return:</p>
                <div class="overflow-x-auto border border-gray-200 rounded max-h-48 overflow-y-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-red-600 text-white sticky top-0">
                            <tr>
                                <th class="px-2 py-1 text-left">Stock ID</th>
                                <th class="px-2 py-1 text-left">Part#</th>
                                <th class="px-2 py-1 text-left">Description</th>
                                <th class="px-2 py-1 text-right">R-Qty</th>
                                <th class="px-2 py-1 text-right">Price</th>
                                <th class="px-2 py-1 text-left">Bill#</th>
                                <th class="px-2 py-1 text-left">Jobber</th>
                            </tr>
                        </thead>
                        <tbody id="grnBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Hidden filled by click --}}
            <input type="hidden" name="required_stock_id" id="selectedStockId">
            <input type="hidden" name="unit_price"        id="selectedPrice">

            {{-- Selected info --}}
            <div id="selectedInfo" class="mb-3 p-2 bg-green-50 border border-green-200 rounded text-sm hidden">
                Selected: <strong id="selDesc"></strong> — Stock ID: <strong id="selStk"></strong>
                &bull; Remain: <strong id="selRemain"></strong>
                &bull; Price: Rs <strong id="selPrice"></strong>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Return By <span class="text-red-500">*</span></label>
                <input type="text" name="return_by" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Person name">
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
                Record Purchase Return
            </button>
        </form>
    </div>

    <div class="bg-white rounded shadow-sm border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 mb-2">Instructions</h3>
        <ol class="text-sm text-gray-600 space-y-2 list-decimal list-inside">
            <li>Enter the GRN (Invoice) number and click <strong>Search</strong></li>
            <li>All stock items from that GRN will appear</li>
            <li>Click a row to select the part you want to return</li>
            <li>Enter the return quantity and reason</li>
            <li>Click <strong>Record Purchase Return</strong></li>
        </ol>
    </div>
</div>

@push('scripts')
<script>
function searchGrn() {
    var grn = document.getElementById('grnInput').value.trim();
    if (!grn) return;

    axios.post('{{ route("parts.ajax.search-stock-by-grn") }}', { partn: grn })
        .then(function(res) {
            var data = res.data;
            var tbody = document.getElementById('grnBody');
            tbody.innerHTML = '';

            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-3 py-4 text-center text-gray-400">No items found for GRN #' + grn + '</td></tr>';
                document.getElementById('grnResults').classList.remove('hidden');
                return;
            }

            data.forEach(function(s) {
                var tr = document.createElement('tr');
                tr.className = 'cursor-pointer hover:bg-red-50 border-b border-gray-100';
                tr.innerHTML =
                    '<td class="px-2 py-2 font-medium text-red-600">' + s.stock_id + '</td>' +
                    '<td class="px-2 py-2">' + s.part_no + '</td>' +
                    '<td class="px-2 py-2 text-xs">' + (s.desc || '') + '</td>' +
                    '<td class="px-2 py-2 text-right ' + (s.remain_qty == 0 ? 'text-red-500' : 'text-green-600') + '">' + s.remain_qty + '</td>' +
                    '<td class="px-2 py-2 text-right">Rs:' + s.price + '</td>' +
                    '<td class="px-2 py-2 text-xs">' + (s.bill_no || '') + '</td>' +
                    '<td class="px-2 py-2 text-xs">' + (s.jobber || '') + '</td>';

                tr.addEventListener('click', function() {
                    document.getElementById('selectedStockId').value = s.stock_id;
                    document.getElementById('selectedPrice').value   = s.price;
                    document.getElementById('selDesc').textContent    = (s.desc || s.part_no);
                    document.getElementById('selStk').textContent     = s.stock_id;
                    document.getElementById('selRemain').textContent  = s.remain_qty;
                    document.getElementById('selPrice').textContent   = s.price;
                    document.getElementById('selectedInfo').classList.remove('hidden');

                    document.querySelectorAll('#grnBody tr').forEach(function(r) {
                        r.classList.remove('bg-green-50', 'font-bold');
                    });
                    tr.classList.add('bg-green-50', 'font-bold');
                });
                tbody.appendChild(tr);
            });
            document.getElementById('grnResults').classList.remove('hidden');
        });
}

document.getElementById('grnInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); searchGrn(); }
});
</script>
@endpush
@endsection
