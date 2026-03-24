@extends('parts.layout')
@section('title', 'Issue Part — RO #' . $data['RO_no'])
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- LEFT: Issue Form --}}
    <div class="bg-white rounded shadow-sm border border-gray-200 p-5">
        <h2 class="text-lg font-bold text-gray-800 mb-1">Workshop Parts Issue</h2>
        <p class="text-sm text-gray-500 mb-4">
            RO# <strong class="text-red-600">{{ $data['RO_no'] }}</strong>
            &nbsp;&bull;&nbsp; Req# <strong>{{ $data['invoice_no'] }}</strong>
        </p>
        <p class="text-sm mb-4">
            Part Description: <strong class="text-red-600">{{ $data['part_description'] }}</strong>
        </p>

        @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-800 text-sm">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        <form action="{{ route('parts.issue-part-submit') }}" method="POST"
              onsubmit="return validateIssueForm(this)">
            @csrf
            <input type="hidden" name="part_id"       value="{{ $data['part_id'] }}">
            <input type="hidden" name="inv"            value="{{ $data['invoice_no'] }}">
            <input type="hidden" name="issued_qty"     id="issued_qty" value="{{ $data['issued_qty'] }}">
            <input type="hidden" name="required_stock_id" id="stk" value="">
            <input type="hidden" name="remain_qty"     id="remain_qty" value="">

            {{-- Part number search --}}
            <div class="mb-3 relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Part Number <span class="text-red-500">*</span>
                </label>
                <input type="text" name="typeahead" id="partno" required autocomplete="off"
                       placeholder="Type part number..."
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <div id="partDropdown" class="ac-dropdown hidden"></div>
                <div id="description" class="mt-1 text-xs text-blue-600"></div>
            </div>

            {{-- Stock ID — shown after searching --}}
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock ID</label>
                <div id="lbl" class="text-sm font-bold text-red-600 min-h-5">
                    <em class="text-gray-400 font-normal">Select from stock table →</em>
                </div>
            </div>

            {{-- Issue To --}}
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Issue To <span class="text-red-500">*</span>
                </label>
                <input type="text" name="issueto" id="issueto" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Enter technician name">
            </div>

            {{-- Quantities --}}
            <div class="mb-3 p-3 bg-gray-50 rounded">
                <div class="grid grid-cols-3 gap-3 text-sm">
                    <div>
                        <div class="text-xs text-gray-500">SA Qty</div>
                        <div class="font-bold text-lg">{{ $data['qty'] }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Required Qty</div>
                        <input type="number" name="require_orignal" id="numberbox"
                               value="{{ $data['req_qty'] }}" min="1" required
                               {{ $data['issued_qty'] > 0 ? 'readonly' : '' }}
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-red-500 {{ $data['issued_qty'] > 0 ? 'bg-gray-100' : '' }}">
                        @if($data['issued_qty'] > 0)
                        <span class="text-xs text-red-500">(-{{ $data['issued_qty'] }} issued)</span>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Issue Now <span class="text-red-500">*</span></div>
                        <input type="number" name="parts_issued" id="numberbox1" required min="1"
                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>
            </div>

            <div class="mb-4 p-3 bg-gray-50 rounded grid grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="text-xs text-gray-500">Sale Price</span>
                    <div class="font-bold">Rs {{ number_format($data['unitprice'], 2) }}</div>
                </div>
                <div>
                    <span class="text-xs text-gray-500">Net Amount</span>
                    <div class="font-bold">Rs {{ number_format($data['total'], 2) }}</div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" name="first_btn" value="1"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded font-medium text-sm">
                    Submit &amp; Back to List
                </button>
                <button type="submit" name="second_btn" value="1"
                        class="flex-1 bg-gray-700 hover:bg-gray-800 text-white py-2 rounded font-medium text-sm">
                    Submit &amp; Print Invoice
                </button>
            </div>
        </form>
    </div>

    {{-- RIGHT: Stock available table --}}
    <div>
        <div class="bg-red-600 px-4 py-2 rounded-t">
            <h3 class="font-bold text-white text-sm">Available Stock — click a row to select</h3>
        </div>
        <div id="stock_available" class="border border-gray-200 rounded-b overflow-x-auto min-h-32 bg-white">
            <p class="p-4 text-xs text-gray-400 italic">Type a part number and tab to "Issue To" to load stock.</p>
        </div>
    </div>
</div>

@push('styles')
<style>
.ac-dropdown { position:absolute; background:white; border:1px solid #d1d5db;
  border-top:none; border-radius:0 0 6px 6px; box-shadow:0 4px 12px rgba(0,0,0,0.12);
  z-index:9999; width:100%; max-height:200px; overflow-y:auto; }
.ac-dropdown div { padding:8px 12px; cursor:pointer; font-size:13px; }
.ac-dropdown div:hover { background:#ef4444; color:white; }
.stock-table { width:100%; font-size:12px; border-collapse:collapse; }
.stock-table th { background:#1f2937; color:white; padding:6px 8px; text-align:left; font-size:11px; }
.stock-table td { padding:5px 8px; border-bottom:1px solid #f3f4f6; }
.stock-table tr:hover td { background:#fef2f2; cursor:pointer; }
.stock-row-zero { background:#fee2e2 !important; opacity:0.6; }
</style>
@endpush

@push('scripts')
<script>
var partTimer;
var partInput  = document.getElementById('partno');
var partDrop   = document.getElementById('partDropdown');
var issueToFld = document.getElementById('issueto');

// Autocomplete part number
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
                        document.getElementById('description').textContent = p.desc || '';
                        partDrop.classList.add('hidden');
                        loadStock(p.value);
                    });
                    partDrop.appendChild(row);
                });
                partDrop.classList.remove('hidden');
            });
    }, 300);
});

document.addEventListener('click', function(e) {
    if (!partInput.contains(e.target)) partDrop.classList.add('hidden');
});

// When tabbing to "Issue To" load stock — matches original behaviour
issueToFld.addEventListener('focus', function() {
    var partn = partInput.value.trim();
    if (partn) loadStock(partn);
});

function loadStock(partNo) {
    axios.post('{{ route("parts.ajax.search-stock-by-part") }}', { partn: partNo })
        .then(function(res) {
            var data = res.data;
            var html = '<table class="stock-table">';
            html += '<tr><th>Stock ID</th><th>GRN</th><th>Bill#</th><th>Date</th><th>Jobber</th><th>P-Qty</th><th>R-Qty</th><th>Price</th><th>Model</th><th>Loc</th></tr>';

            if (!data.length) {
                html += '<tr><td colspan="10" style="text-align:center;padding:12px;color:#9ca3af;">Part not in stock</td></tr>';
            }
            data.forEach(function(s) {
                var cls = s.remain_qty == 0 ? ' class="stock-row-zero"' : '';
                var click = s.remain_qty > 0
                    ? ' onclick="selectStock(' + s.stock_id + ',' + s.remain_qty + ')"'
                    : '';
                html += '<tr' + cls + click + '>';
                html += '<td><strong style="color:#dc2626;">' + s.stock_id + '</strong></td>';
                html += '<td>' + s.grn + '</td>';
                html += '<td>' + (s.bill_no||'') + '</td>';
                html += '<td>' + (s.purch_date||'') + '</td>';
                html += '<td style="font-size:11px;">' + (s.jobber||s.category||'') + '</td>';
                html += '<td>' + s.quantity + '</td>';
                html += '<td style="color:' + (s.remain_qty==0?'#ef4444':'#16a34a') + ';font-weight:bold;">' + s.remain_qty + '</td>';
                html += '<td>Rs:' + s.price + '</td>';
                html += '<td style="font-size:11px;">' + (s.model||'') + '</td>';
                html += '<td style="font-size:11px;">' + (s.location||'') + '</td>';
                html += '</tr>';
            });
            html += '</table>';
            document.getElementById('stock_available').innerHTML = html;
        });
}

function selectStock(stockId, remainQty) {
    document.getElementById('stk').value       = stockId;
    document.getElementById('remain_qty').value = remainQty;
    document.getElementById('lbl').innerHTML   = '<span style="color:#dc2626;font-size:16px;">' + stockId + '</span>';
}

function validateIssueForm(form) {
    var stockId    = document.getElementById('stk').value;
    var partsIssued = parseInt(form.parts_issued.value) || 0;
    var availQty   = parseInt(document.getElementById('remain_qty').value) || 0;
    var reqOrig    = parseInt(form.require_orignal.value) || 0;
    var issuedQty  = parseInt(document.getElementById('issued_qty').value) || 0;
    var totalIssued = issuedQty + partsIssued;

    if (!stockId) {
        alert('Stock ID not selected! Click a row from the stock table.');
        return false;
    }
    if (partsIssued > availQty) {
        alert('Available stock (' + availQty + ') is less than quantity to issue (' + partsIssued + ')!');
        return false;
    }
    if (totalIssued > reqOrig) {
        alert('Issued quantity (' + totalIssued + ') is greater than required (' + reqOrig + ')!');
        return false;
    }
    return true;
}
</script>
@endpush
@endsection
