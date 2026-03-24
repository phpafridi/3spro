@extends('parts.layout')
@section('title', 'Sale Invoice #{{ $invoice->sale_inv }}')
@section('content')

<style>
body { background:#fce8dc; }
.tble { border-collapse:collapse; width:100%; }
.tble_td { padding:6px 12px; font-size:13px; color:#333; }
.thd { background:linear-gradient(135deg,#c0392b,#7a1a1a); color:#fff; padding:6px 10px; }
.form_inp { border:1px solid #ccc; border-radius:4px; padding:5px 8px; font-size:13px; }
.imp_span { color:#dc2626; font-weight:700; }
.btn {
    background:linear-gradient(135deg,#5a1a1a,#3d0000);
    color:#fff; padding:8px 28px; font-size:13px; font-weight:700;
    border-radius:20px; border:none; cursor:pointer;
}
.btn-add {
    background:linear-gradient(135deg,#1e3a5f,#0f2440);
    color:#fff; padding:7px 20px; font-size:13px; font-weight:700;
    border-radius:20px; border:none; cursor:pointer; text-decoration:none; display:inline-block;
}
/* Left panel */
.sale-panel {
    background:linear-gradient(135deg,#f8d7d7,#fce4e4,#f5c6c6);
    border:1px solid #e2a8a8; border-radius:12px; padding:20px; max-width:500px;
}
.sale-panel h1 {
    text-align:center; font-size:22px; font-weight:900; color:#3d0000;
    font-family:'Georgia',serif; margin-bottom:12px;
}
input.form_inp:focus { outline:none; border-color:#dc2626; }
#description { color:#dc2626; font-size:12px; font-weight:700; margin-top:3px; }
#lbl { font-weight:700; color:#dc2626; font-size:15px; min-height:18px; }
/* Stock table */
#stock_available { padding:8px; }
#stock_available table { border-collapse:collapse; }
#stock_available th { background:#3498db; color:#fff; padding:4px 7px; font-size:12px; }
#stock_available td { padding:4px 7px; font-size:12px; border-bottom:1px solid #e5e7eb; }
.trrr:hover td { background:rgba(100,150,255,0.35); cursor:pointer; }
/* Invoice lines */
.inv-lines { width:100%; border-collapse:collapse; font-size:13px; margin-top:10px; }
.inv-lines th { background:#374151; color:#fff; padding:6px 8px; text-align:left; }
.inv-lines td { padding:5px 8px; border-bottom:1px solid #e5e7eb; }
.inv-lines tr:hover td { background:#f9fafb; }
/* Typeahead */
.tt-dropdown-menu {
    background:#fff; border:1px solid #ccc; border-radius:6px;
    box-shadow:0 4px 12px rgba(0,0,0,0.15); width:280px; z-index:9999; position:absolute;
}
.tt-suggestion { padding:6px 12px; font-size:13px; cursor:pointer; }
.tt-suggestion:hover { background:#3498db; color:#fff; }
</style>

@if(session('success'))
<div style="background:#d1fae5;border-left:4px solid #10b981;padding:10px;margin-bottom:10px;border-radius:4px;font-size:13px;">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#fee2e2;border-left:4px solid #ef4444;padding:10px;margin-bottom:10px;border-radius:4px;font-size:13px;">
    {{ session('error') }}
</div>
@endif

<table width="100%" border="0"><tr>

{{-- ── LEFT: Add more parts form ── --}}
<td width="50%" valign="top">
<div class="sale-panel">
    <h1>SJ-{{ $invoice->sale_inv }}</h1>
    <p style="text-align:center;font-size:13px;color:#555;margin-bottom:10px;">
        {{ $invoice->Jobber }} &nbsp;|&nbsp; {{ $invoice->payment_method }}
    </p>

    {{--
        This form matches original sale_inv.php "Add Part" button:
        it posts inv back to sale_part.php (in our case: sale/{id}/add)
        so user can add more parts to the same invoice.
    --}}
    <form action="{{ route('parts.sale.add.part', $invoice->sale_inv) }}"
          method="POST" name="myForm" onsubmit="return validateForm()">
        @csrf

        <table class="tble">
            {{-- Part Number typeahead --}}
            <tr>
                <td class="tble_td">Part Number <span class="imp_span">*</span>:</td>
                <td class="tble_td" style="position:relative;">
                    <input type="text" name="typeahead" id="partno"
                           class="form_inp" autocomplete="off" spellcheck="false"
                           placeholder="Type Part number" required style="width:200px;">
                    <input type="hidden" name="descript" id="descript">
                    <div id="tt-menu" class="tt-dropdown-menu" style="display:none;"></div>
                    <div id="description"></div>
                </td>
            </tr>

            {{-- Stock ID --}}
            <tr>
                <td class="tble_td">Stock ID <span class="imp_span">*</span>:</td>
                <td class="tble_td">
                    <div id="lbl"></div>
                    <input type="hidden" name="required_stock_id" id="stk">
                    <input type="hidden" name="remaining_qtyy"    id="remaining_qtyy">
                </td>
            </tr>

            {{-- Quantity --}}
            <tr>
                <td class="tble_td">Quantity <span class="imp_span">*</span>:</td>
                <td class="tble_td">
                    <input type="number" class="form_inp" name="required_qty" id="qty"
                           autocomplete="off" oninput="calculate()" required min="1" style="width:100px;">
                </td>
            </tr>

            {{-- Sale Price --}}
            <tr>
                <td class="tble_td">Sale Price <span class="imp_span">*</span>:</td>
                <td class="tble_td">
                    <input placeholder="Rs" type="number" autocomplete="off"
                           name="required_uprice" class="form_inp" id="unit_price"
                           oninput="calculate()" required min="0" step="0.01" style="width:100px;">
                </td>
            </tr>

            {{-- Discount --}}
            <tr>
                <td class="tble_td">Discount <span class="imp_span">*</span>:</td>
                <td class="tble_td">
                    <input type="number" style="width:3em;" value="0" step="any"
                           class="form_inp" name="dis_per" id="dis_per"
                           oninput="dis_per_f()" max="100">%
                    <input style="width:7em;" type="number" value="0"
                           name="discount" class="form_inp" id="discount"
                           oninput="dis_amt_f()" required>
                </td>
            </tr>

            {{-- Tax --}}
            <tr>
                <td class="tble_td">Tax <span class="imp_span">*</span>:</td>
                <td class="tble_td">
                    <input type="number" style="width:3em;" value="0" step="any"
                           class="form_inp" name="tax_per" id="tax_per"
                           oninput="tax_per_f()" max="100">%
                    <input style="width:7em;" type="number" value="0"
                           name="tax" class="form_inp" id="tax"
                           oninput="tax_amt_f()" required>
                </td>
            </tr>

            {{-- Net Amount --}}
            <tr>
                <td class="tble_td">Net Amount</td>
                <td class="tble_td">
                    <input type="hidden" name="required_netprice" id="net_amount">
                    <div id="netprice"></div>
                </td>
            </tr>

            <tr>
                <td align="center" colspan="2" style="padding-top:10px;">
                    <input type="submit" value="Submit &amp; Add more" name="submit3" class="btn">
                </td>
            </tr>
        </table>
    </form>
</div>

{{-- Stock purchase history — filled by JS --}}
<div id="stock_available" style="margin-top:12px;"></div>
</td>

{{-- ── RIGHT: Invoice lines + totals + close button ── --}}
<td width="50%" valign="top" style="padding-left:16px;">

    <h3 style="font-size:15px;font-weight:700;color:#1f2937;margin-bottom:8px;">
        Invoice Lines — SJ-{{ $invoice->sale_inv }}
    </h3>

    @if($parts->count() > 0)
    <table class="inv-lines">
        <thead>
            <tr>
                <th>sr#</th>
                <th>Part Number</th>
                <th>Qty</th>
                <th>UnitPrice</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Net-Amount</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($parts as $i => $part)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $part->part_no }}</strong></td>
                <td>{{ $part->quantity }}</td>
                <td>{{ $part->sale_price }}</td>
                <td>{{ $part->discount }}</td>
                <td>{{ $part->tax }}</td>
                {{-- net-amount column: netamount + tax (matches original sale_inv.php) --}}
                <td><strong>{{ $part->netamount + $part->tax }}</strong></td>
                <td>
                    {{-- Delete button (matches original) --}}
                    <form action="{{ route('parts.sale.invoice.delete') }}" method="POST"
                          onsubmit="return confirm('Delete this part and restore stock?')">
                        @csrf
                        <input type="hidden" name="sell_id"  value="{{ $part->sell_id }}">
                        <input type="hidden" name="inv_no"   value="{{ $invoice->sale_inv }}">
                        <input type="hidden" name="quantity" value="{{ $part->quantity }}">
                        <input type="hidden" name="stock_id" value="{{ $part->stock_id }}">
                        <button type="submit" class="btn" style="padding:3px 10px;font-size:11px;background:#c0392b;">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals — matches original sale_inv.php exactly --}}
    <table style="margin-top:10px;font-size:13px;border-collapse:collapse;margin-left:auto;">
        <tr>
            <th style="text-align:right;padding:4px 10px;">Gross Sale</th>
            <th style="padding:4px 10px;">{{ number_format($grossSale, 0) }}</th>
        </tr>
        <tr>
            <td style="text-align:right;padding:4px 10px;">Discount</td>
            <td style="padding:4px 10px;">{{ number_format($totalDiscount, 0) }}</td>
        </tr>
        <tr>
            <td style="text-align:right;padding:4px 10px;">Sales Tax</td>
            <td style="padding:4px 10px;">{{ number_format($totalTax, 0) }}</td>
        </tr>
        <tr style="border-top:2px solid #374151;font-weight:700;font-size:15px;">
            <td style="text-align:right;padding:6px 10px;">Net Amount:</td>
            <td style="padding:6px 10px;color:#dc2626;">
                <strong>{{ number_format($netAmount, 0) }}</strong>
            </td>
        </tr>
    </table>

    {{--
        Close & Print form
        Posts to route('parts.sale.close', $invoice->sale_inv)
        which maps to POST /sale/{sale_inv}/close
        printAndClose($request, $sale_inv) gets sale_inv from route parameter — no missing parameter error
    --}}
    <form action="{{ route('parts.sale.close', $invoice->sale_inv) }}" method="POST" style="margin-top:14px;">
        @csrf
        <div style="margin-bottom:6px;">
            Remarks:<br>
            <textarea name="remarks" cols="40" rows="3" placeholder="if any"
                      style="border:1px solid #ccc;border-radius:4px;padding:6px;font-size:13px;width:100%;"></textarea>
        </div>
        {{-- Pass totals as hidden fields (controller also recalculates from DB for safety) --}}
        <input type="hidden" name="inv_no"      value="{{ $invoice->sale_inv }}">
        <input type="hidden" name="discount"    value="{{ $totalDiscount }}">
        <input type="hidden" name="tax"         value="{{ $totalTax }}">
        <input type="hidden" name="totalamount" value="{{ $totalAmount }}">
        <button type="submit" class="btn" style="width:100%;padding:10px;font-size:14px;border-radius:6px;">
            Close &amp; Print
        </button>
    </form>
    @else
    <p style="color:#666;font-size:13px;">No parts added yet.</p>
    @endif

    {{-- "Add Part" button — goes back to sale_part.php with inv — matches original --}}
    <div style="margin-top:12px;text-align:center;">
        <a href="{{ route('parts.sale.add', $invoice->sale_inv) }}" class="btn-add">
            + Add More Parts
        </a>
    </div>

</td>
</tr></table>

{{-- ── Scripts ── --}}
<script>
var partInput = document.getElementById('partno');
var ttMenu    = document.getElementById('tt-menu');
var ttTimer;

partInput.addEventListener('input', function() {
    clearTimeout(ttTimer);
    var val = this.value.trim();
    if (val.length < 1) { ttMenu.style.display = 'none'; return; }
    ttTimer = setTimeout(function() {
        fetch('{{ route("parts.ajax.search-part") }}?key=' + encodeURIComponent(val))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                ttMenu.innerHTML = '';
                if (!data || !data.length) { ttMenu.style.display = 'none'; return; }
                data.forEach(function(item) {
                    var div = document.createElement('div');
                    div.className = 'tt-suggestion';
                    div.textContent = item.value + (item.desc ? ' — ' + item.desc : '');
                    div.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        partInput.value = item.value;
                        document.getElementById('descript').value        = item.desc || '';
                        document.getElementById('description').innerHTML = item.desc || '';
                        ttMenu.style.display = 'none';
                        onPartSelected(item.value);
                    });
                    ttMenu.appendChild(div);
                });
                ttMenu.style.display = 'block';
            }).catch(function() {});
    }, 300);
});

document.addEventListener('click', function(e) {
    if (!partInput.contains(e.target) && !ttMenu.contains(e.target))
        ttMenu.style.display = 'none';
});

function onPartSelected(partNo) {
    /* description.php equivalent */
    fetch('{{ route("parts.ajax.search-part-desc") }}?partn=' + encodeURIComponent(partNo))
        .then(function(r) { return r.json(); })
        .then(function(d) {
            document.getElementById('description').innerHTML = d.desc || '';
            document.getElementById('descript').value        = d.desc || '';
        }).catch(function() {});

    /* search_stock.php equivalent — exact part_no match */
    fetch('{{ route("parts.ajax.search-stock") }}?key=' + encodeURIComponent(partNo))
        .then(function(r) { return r.json(); })
        .then(function(d) { renderStockTable(d); })
        .catch(function() {});
}

function renderStockTable(data) {
    var html = '<div style="height:400px;overflow:auto;margin-top:8px;">' +
        '<table align="center" class="tble">' +
        '<tr><th colspan="10" align="center">Purchase History</th></tr>' +
        '<tr>' +
        '<th>Stock ID</th><th>GRN</th><th>Inv</th><th>P-Date</th>' +
        '<th>Jobber</th><th>P-Qty</th><th>R-Qty</th><th>U-Price</th>' +
        '<th>Model</th><th>Location</th>' +
        '</tr>';

    if (!data || !data.length) {
        html += '<tr><td colspan="10" align="center">Required Part# is not available in Stock</td></tr>';
    } else {
        data.forEach(function(s) {
            var clickable = s.remain_qty > 0;
            var rowBg     = clickable ? '' : ' bgcolor="red"';
            var tdClick   = clickable
                ? ' onclick="getstock_id(' + s.stock_id + ',' + s.remain_qty + ');" style="cursor:pointer;"'
                : '';
            html += '<tr class="trrr" align="center"' + rowBg + '>';
            html += '<td align="left"' + tdClick + '>&#8598; ' + s.stock_id + '</td>';
            html += '<td>' + (s.grn        || '') + '</td>';
            html += '<td>' + (s.inv_number || '') + '</td>';
            html += '<td>' + (s.purch_date || '') + '</td>';
            html += '<td style="font-size:11px;">' + (s.jobber   || '') + '</td>';
            html += '<td>' + (s.quantity   || 0)  + '</td>';
            html += '<td>' + s.remain_qty          + '</td>';
            html += '<td align="left">Rs:' + s.price + '</td>';
            html += '<td align="left">' + (s.model    || '') + '</td>';
            html += '<td align="left">' + (s.location || '') + '</td>';
            html += '</tr>';
        });
    }
    html += '</table></div>';
    document.getElementById('stock_available').innerHTML = html;
}

/* Called by clicking a stock row */
function getstock_id(val, rem_qty) {
    document.getElementById('stk').value            = val;
    document.getElementById('remaining_qtyy').value = rem_qty;
    document.getElementById('lbl').innerHTML        = val;
}

/* Calculations — exact match of original JS */
function calculate() {
    var myBox1   = parseFloat(document.getElementById('unit_price').value) || 0;
    var discount = parseFloat(document.getElementById('discount').value)   || 0;
    var tax      = parseFloat(document.getElementById('tax').value)        || 0;
    var myBox2   = parseFloat(document.getElementById('qty').value)        || 0;
    var myResult = ((myBox1 * myBox2) - discount) + tax;
    document.getElementById('net_amount').value   = myResult;
    document.getElementById('netprice').innerHTML = myResult;
}
function tax_amt_f() {
    var u = parseFloat(document.getElementById('unit_price').value) || 0;
    var t = parseFloat(document.getElementById('tax').value)        || 0;
    var q = parseFloat(document.getElementById('qty').value)        || 0;
    document.getElementById('tax_per').value = (u && q) ? ((t / u) * q) * 100 : 0;
    calculate();
}
function tax_per_f() {
    var u  = parseFloat(document.getElementById('unit_price').value) || 0;
    var tp = parseFloat(document.getElementById('tax_per').value)    || 0;
    var q  = parseFloat(document.getElementById('qty').value)        || 0;
    document.getElementById('tax').value = (tp / 100) * (u * q);
    calculate();
}
function dis_amt_f() {
    var u = parseFloat(document.getElementById('unit_price').value) || 0;
    var d = parseFloat(document.getElementById('discount').value)   || 0;
    var q = parseFloat(document.getElementById('qty').value)        || 0;
    document.getElementById('dis_per').value = (u && q) ? ((d / u) * q) * 100 : 0;
    calculate();
}
function dis_per_f() {
    var u  = parseFloat(document.getElementById('unit_price').value) || 0;
    var dp = parseFloat(document.getElementById('dis_per').value)    || 0;
    var q  = parseFloat(document.getElementById('qty').value)        || 0;
    document.getElementById('discount').value = (dp / 100) * (u * q);
    calculate();
}
function validateForm() {
    var x    = document.forms['myForm']['required_stock_id'].value;
    var req  = parseFloat(document.forms['myForm']['required_qty'].value)   || 0;
    var avbl = parseFloat(document.forms['myForm']['remaining_qtyy'].value) || 0;
    if (x === '' || req > avbl) {
        alert('Stock ID not selected OR Available stock is less than required!!!');
        return false;
    }
    return true;
}
</script>
@endsection
