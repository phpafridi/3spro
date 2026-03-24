@extends('parts.layout')
@section('title', 'Parts Sale')
@section('content')

<style>
body { background:#fce8dc; }
.tble { border-collapse:collapse; width:100%; }
.tble_td { padding:6px 10px; font-size:13px; color:#333; }
.thd { background:linear-gradient(135deg,#c0392b,#7a1a1a); color:#fff; padding:6px 10px; }
.imp_span { color:#dc2626; font-weight:700; }
input.form_inp, select.form_inp {
    border:1px solid #d1d5db; border-radius:20px; padding:5px 10px;
    font-size:13px; background:#fff0f0; width:100%;
}
input.form_inp:focus, select.form_inp:focus { outline:none; border-color:#dc2626; }
.btn {
    background:linear-gradient(135deg,#5a1a1a,#3d0000);
    color:#fff; padding:8px 28px; font-size:13px; font-weight:700;
    border-radius:20px; border:none; cursor:pointer;
}
.btn-print {
    background:linear-gradient(135deg,#1e3a5f,#0f2440);
    color:#fff; padding:9px 30px; font-size:14px; font-weight:700;
    border-radius:20px; border:none; cursor:pointer; width:100%;
}
.tble-outer {
    background:linear-gradient(135deg,#f8d7d7,#fce4e4);
    border:1px solid #e2a8a8; border-radius:12px; padding:16px; max-width:480px;
}
.tble-outer h1 {
    text-align:center; font-size:20px; font-weight:900; color:#3d0000;
    margin-bottom:10px;
}
/* Stock history table */
#stock_available th { background:#3498db; color:#fff; padding:4px 7px; font-size:12px; }
#stock_available td { padding:4px 7px; font-size:12px; border-bottom:1px solid #e5e7eb; }
.trrr:hover td { background:rgba(52,152,219,0.3); cursor:pointer; }
#description { color:#dc2626; font-size:12px; font-weight:700; margin-top:3px; }
#lbl { font-weight:700; color:#dc2626; font-size:15px; min-height:18px; }
/* Invoice lines table */
.inv-tbl { border-collapse:collapse; width:100%; font-size:13px; }
.inv-tbl th { background:#374151; color:#fff; padding:6px 8px; text-align:left; }
.inv-tbl td { padding:5px 8px; border-bottom:1px solid #e5e7eb; }
.inv-tbl tr:hover td { background:#f9fafb; }
/* Totals */
.totals-box { float:right; margin-top:8px; font-size:13px; border-collapse:collapse; }
.totals-box td { padding:3px 10px; }
.totals-box .net-row td { font-weight:700; font-size:15px; border-top:2px solid #374151; color:#dc2626; }
/* Typeahead */
.tt-dropdown-menu {
    background:#fff; border:1px solid #ccc; border-radius:6px;
    box-shadow:0 4px 12px rgba(0,0,0,0.15); min-width:220px; z-index:9999;
}
.tt-suggestion { padding:6px 12px; font-size:13px; cursor:pointer; }
.tt-suggestion:hover { background:#3498db; color:#fff; }
</style>

@if(session('success'))
<div style="background:#d1fae5;border-left:4px solid #10b981;padding:8px 12px;margin-bottom:10px;border-radius:4px;font-size:13px;">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#fee2e2;border-left:4px solid #ef4444;padding:8px 12px;margin-bottom:10px;border-radius:4px;font-size:13px;">
    {{ session('error') }}
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════
     TOP ROW: Left = form | Right = stock table
════════════════════════════════════════════════════════════ --}}
<table width="100%" border="0" style="margin-bottom:0;"><tr>

<td width="50%" valign="top">
<div class="tble-outer">
    <h1>Parts Sell</h1>

    {{-- ── STEP 1: No invoice yet — show jobber/payment form ── --}}
    @if(!isset($sale_inv))
    <form action="{{ route('parts.sale.store') }}" method="POST">
        @csrf
        <table class="tble">
            <tr>
                <td class="tble_td">Jobber <span class="imp_span">*</span>:</td>
                <td class="tble_td">
                    <select name="required_jobber" class="form_inp" id="jobber"
                            onchange="jobberCheck()" required>
                        <option value=""></option>
                        <option>Counter Sale</option>
                        @foreach($jobbers as $j)
                        <option>{{ $j->jbr_name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td class="tble_td">Transaction:</td>
                <td class="tble_td">
                    <select class="form_inp" name="payment_method" id="payment_method" required>
                        <option>Cash</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center" style="padding-top:12px;">
                    <input type="submit" value="Create Invoice →" class="btn">
                </td>
            </tr>
        </table>
    </form>

    {{-- ── STEP 2: Invoice exists — show part entry form ── --}}
    @else
    <form action="{{ route('parts.sale.add.part', $sale_inv) }}" method="POST"
          name="saleForm" onsubmit="return validateSaleForm()">
        @csrf
        <table class="tble">
            <tr>
                <td class="tble_td" colspan="2" style="font-size:14px;">
                    Invoice — <strong style="color:#dc2626;">SJ-{{ $sale_inv }}</strong>
                    &nbsp;|&nbsp; {{ $invoice->Jobber ?? '' }}
                    &nbsp;|&nbsp; {{ $invoice->payment_method ?? '' }}
                </td>
            </tr>

            {{-- Part Number typeahead --}}
            <tr>
                <td class="tble_td">Part Number <span class="imp_span">*</span>:</td>
                <td class="tble_td">
                    <input type="text" name="typeahead" id="partno"
                           class="form_inp" autocomplete="off"
                           placeholder="Type Part number" required>
                    <input type="hidden" name="descript" id="descript">
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

            {{-- Qty --}}
            <tr>
                <td class="tble_td">Quantity <span class="imp_span">*</span>:</td>
                <td class="tble_td">
                    <input type="number" class="form_inp" name="required_qty" id="qty"
                           min="1" autocomplete="off" oninput="calculate()" required>
                </td>
            </tr>

            {{-- Sale Price --}}
            <tr>
                <td class="tble_td">Sale Price <span class="imp_span">*</span>:</td>
                <td class="tble_td">
                    <input placeholder="Rs" type="number" autocomplete="off"
                           name="required_uprice" class="form_inp" id="unit_price"
                           oninput="calculate()" required min="0" step="0.01">
                </td>
            </tr>

            {{-- Discount --}}
            <tr>
                <td class="tble_td">Discount:</td>
                <td class="tble_td" style="display:flex;gap:4px;align-items:center;">
                    <input type="number" style="width:52px;" value="0" step="any"
                           class="form_inp" name="dis_per" id="dis_per"
                           oninput="disPerF()" max="100">%
                    <input style="width:80px;" type="number" value="0"
                           name="discount" class="form_inp" id="discount"
                           oninput="disAmtF()" required>
                </td>
            </tr>

            {{-- Tax --}}
            <tr>
                <td class="tble_td">Tax:</td>
                <td class="tble_td" style="display:flex;gap:4px;align-items:center;">
                    <input type="number" style="width:52px;" value="0" step="any"
                           class="form_inp" name="tax_per" id="tax_per"
                           oninput="taxPerF()" max="100">%
                    <input style="width:80px;" type="number" value="0"
                           name="tax" class="form_inp" id="tax"
                           oninput="taxAmtF()" required>
                </td>
            </tr>

            {{-- Net Amount --}}
            <tr>
                <td class="tble_td">Net Amount</td>
                <td class="tble_td">
                    <input type="hidden" name="required_netprice" id="net_amount">
                    <div id="netprice" style="font-weight:700;font-size:15px;"></div>
                </td>
            </tr>

            <tr>
                <td align="center" colspan="2" style="padding-top:10px;">
                    <input type="submit" value="Submit &amp; Add More" name="submit3" class="btn">
                </td>
            </tr>
        </table>
    </form>
    @endif

</div>
</td>

{{-- ── RIGHT: Stock table ── --}}
<td width="50%" valign="top" style="padding-left:12px;">
    <div id="stock_available"></div>
</td>

</tr></table>

{{-- ═══════════════════════════════════════════════════════════
     BOTTOM: Invoice lines table + totals + print
     (Only shown after invoice created and has parts)
════════════════════════════════════════════════════════════ --}}
@if(isset($sale_inv) && isset($parts) && $parts->count() > 0)
<div style="margin-top:20px;background:#fff;border-radius:12px;border:1px solid #e2a8a8;padding:16px;">

    <h3 style="margin:0 0 10px;font-size:15px;color:#3d0000;">
        Invoice SJ-{{ $sale_inv }}
        &nbsp;—&nbsp; {{ $invoice->Jobber ?? '' }}
        &nbsp;|&nbsp; {{ $invoice->payment_method ?? '' }}
        <span style="float:right;font-size:13px;color:#666;">{{ $parts->count() }} items</span>
    </h3>

    <table class="inv-tbl">
        <thead>
            <tr>
                <th>#</th>
                <th>Part No</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Net Amount</th>
                <th>Edit</th>
                <th>Del</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parts as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $p->part_no }}</strong></td>
                <td style="font-size:11px;">{{ $p->Description ?? '' }}</td>
                <td>{{ $p->quantity }}</td>
                <td>{{ number_format($p->sale_price, 0) }}</td>
                <td style="color:#f97316;">{{ number_format($p->discount, 0) }}</td>
                <td style="color:#3b82f6;">{{ number_format($p->tax, 0) }}</td>
                <td><strong>{{ number_format($p->netamount + $p->tax, 0) }}</strong></td>

                {{-- Edit --}}
                <td>
                    <a href="{{ route('parts.sale.invoice.edit', $p->sell_id) }}"
                       style="background:#f59e0b;color:#fff;padding:2px 8px;border-radius:4px;
                              font-size:11px;text-decoration:none;">Edit</a>
                </td>

                {{-- Delete --}}
                <td>
                    <form method="POST" action="{{ route('parts.sale.invoice.delete') }}"
                          onsubmit="return confirm('Delete this part and restore stock?')">
                        @csrf
                        <input type="hidden" name="sell_id"  value="{{ $p->sell_id }}">
                        <input type="hidden" name="quantity" value="{{ $p->quantity }}">
                        <input type="hidden" name="stock_id" value="{{ $p->stock_id }}">
                        <input type="hidden" name="inv_no"   value="{{ $sale_inv }}">
                        <button type="submit"
                                style="background:#dc2626;color:#fff;border:none;border-radius:4px;
                                       padding:2px 8px;cursor:pointer;font-size:11px;">✕</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    @php
        $grossSale     = $parts->sum(fn($p) => $p->sale_price * $p->quantity);
        $totalDiscount = $parts->sum('discount');
        $totalTax      = $parts->sum('tax');
        $netAmount     = $grossSale - $totalDiscount + $totalTax;
    @endphp

    <div style="overflow:hidden;margin-top:10px;">
        <table class="totals-box">
            <tr><td style="color:#555;">Gross Sale:</td>
                <td style="text-align:right;">{{ number_format($grossSale, 0) }}</td></tr>
            <tr><td style="color:#f97316;">Discount:</td>
                <td style="text-align:right;color:#f97316;">{{ number_format($totalDiscount, 0) }}</td></tr>
            <tr><td style="color:#3b82f6;">GST:</td>
                <td style="text-align:right;color:#3b82f6;">{{ number_format($totalTax, 0) }}</td></tr>
            <tr class="net-row">
                <td>Net Amount:</td>
                <td style="text-align:right;">Rs {{ number_format($netAmount, 0) }}</td>
            </tr>
        </table>
    </div>

    {{-- Print / Close form --}}
    <div style="clear:both;margin-top:14px;">
        <form method="POST" action="{{ route('parts.sale.close', $sale_inv) }}" target="_blank">
            @csrf
            <div style="margin-bottom:8px;">
                <textarea name="remarks" rows="2" placeholder="Remarks (optional)..."
                          style="width:100%;border:1px solid #d1d5db;border-radius:8px;
                                 padding:6px 10px;font-size:13px;"></textarea>
            </div>
            <button type="submit" class="btn-print">
                🖨 Close &amp; Print Invoice
            </button>
        </form>
    </div>

</div>
@elseif(isset($sale_inv))
<div style="margin-top:14px;padding:12px;background:#fff8e1;border-radius:8px;
            border-left:4px solid #f59e0b;font-size:13px;color:#92400e;">
    Invoice SJ-{{ $sale_inv }} created. Type a part number above to add items.
</div>
@endif

{{-- ═══════ SCRIPTS ═══════ --}}
<script>
/* Jobber → payment method */
function jobberCheck() {
    var sel = document.getElementById('payment_method');
    sel.options.length = 0;
    sel.options[0] = document.getElementById('jobber').value === 'Counter Sale'
        ? new Option('Cash') : new Option('Credit');
}

@if(isset($sale_inv))
/* ── Typeahead ── */
var partInput = document.getElementById('partno');
var ttMenu = document.createElement('div');
ttMenu.className = 'tt-dropdown-menu';
ttMenu.style.cssText = 'display:none;position:absolute;z-index:9999;';
partInput.parentNode.style.position = 'relative';
partInput.parentNode.appendChild(ttMenu);

var ttTimer;
partInput.addEventListener('input', function() {
    clearTimeout(ttTimer);
    var val = this.value.trim();
    if (val.length < 2) { ttMenu.style.display = 'none'; return; }
    ttTimer = setTimeout(function() {
        fetch('{{ route("parts.ajax.search-part") }}?key=' + encodeURIComponent(val))
            .then(r => r.json())
            .then(data => {
                ttMenu.innerHTML = '';
                if (!data.length) { ttMenu.style.display = 'none'; return; }
                data.forEach(function(item) {
                    var d = document.createElement('div');
                    d.className = 'tt-suggestion';
                    d.textContent = item.value + (item.desc ? ' — ' + item.desc : '');
                    d.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        partInput.value = item.value;
                        document.getElementById('descript').value = item.desc || '';
                        document.getElementById('description').textContent = item.desc || '';
                        ttMenu.style.display = 'none';
                        loadStock(item.value);
                    });
                    ttMenu.appendChild(d);
                });
                ttMenu.style.display = 'block';
            });
    }, 300);
});
document.addEventListener('click', function(e) {
    if (!partInput.contains(e.target)) ttMenu.style.display = 'none';
});

/* Load stock table */
function loadStock(partNo) {
    fetch('{{ route("parts.ajax.search-stock") }}?key=' + encodeURIComponent(partNo))
        .then(r => r.json())
        .then(data => renderStockTable(data, partNo));
}

function renderStockTable(data, partNo) {
    var html = '<div style="max-height:480px;overflow:auto;">' +
        '<table style="border-collapse:collapse;width:100%;">' +
        '<thead><tr>' +
        ['Stock ID','GRN','Inv','P-Date','Jobber','P-Qty','R-Qty','U-Price','Model','Loc'].map(function(h) {
            return '<th style="background:#3498db;color:#fff;padding:4px 7px;font-size:12px;white-space:nowrap;">' + h + '</th>';
        }).join('') +
        '</tr></thead><tbody>';

    if (!data || !data.length) {
        html += '<tr><td colspan="10" style="padding:10px;text-align:center;color:#dc2626;">Part not in stock</td></tr>';
    } else {
        data.forEach(function(s) {
            var clickable = s.remain_qty > 0;
            var rowBg = clickable ? '' : ' style="background:#ffcccc;"';
            var click = clickable
                ? ' onclick="selectStock(' + s.stock_id + ',' + s.remain_qty + ',' + (s.price||0) + ')" style="cursor:pointer;"'
                : ' style="cursor:not-allowed;opacity:0.6;"';
            html += '<tr class="trrr"' + rowBg + '>';
            html += '<td' + click + '>↖ ' + s.stock_id + '</td>';
            html += '<td>' + (s.grn||'') + '</td>';
            html += '<td>' + (s.inv_number||'') + '</td>';
            html += '<td style="font-size:11px;">' + (s.purch_date||'') + '</td>';
            html += '<td style="font-size:11px;">' + (s.jobber||'') + '</td>';
            html += '<td>' + (s.quantity||'') + '</td>';
            html += '<td style="font-weight:700;color:' + (s.remain_qty>0?'#16a34a':'#dc2626') + ';">' + s.remain_qty + '</td>';
            html += '<td>Rs ' + (s.price||0) + '</td>';
            html += '<td style="font-size:11px;">' + (s.model||'') + '</td>';
            html += '<td style="font-size:11px;">' + (s.location||'') + '</td>';
            html += '</tr>';
        });
    }
    html += '</tbody></table></div>';
    document.getElementById('stock_available').innerHTML = html;
}

function selectStock(stockId, remQty, price) {
    document.getElementById('stk').value            = stockId;
    document.getElementById('remaining_qtyy').value = remQty;
    document.getElementById('lbl').innerHTML        =
        '<span style="color:#dc2626;">' + stockId + '</span>' +
        ' &nbsp; Avail: <strong>' + remQty + '</strong>';
    if (price && !document.getElementById('unit_price').value) {
        document.getElementById('unit_price').value = price;
        calculate();
    }
}

function getstock_id(val, rem_qty) { selectStock(val, rem_qty, 0); }

/* Calculations */
function calculate() {
    var p = parseFloat(document.getElementById('unit_price').value)||0;
    var q = parseFloat(document.getElementById('qty').value)||0;
    var d = parseFloat(document.getElementById('discount').value)||0;
    var t = parseFloat(document.getElementById('tax').value)||0;
    var n = (p*q) - d + t;
    document.getElementById('net_amount').value   = n;
    document.getElementById('netprice').innerHTML = n.toFixed(2);
}
function taxAmtF()  { var p=parseFloat(document.getElementById('unit_price').value)||0,t=parseFloat(document.getElementById('tax').value)||0,q=parseFloat(document.getElementById('qty').value)||0; document.getElementById('tax_per').value=(p&&q)?((t/(p*q))*100).toFixed(2):0; calculate(); }
function taxPerF()  { var p=parseFloat(document.getElementById('unit_price').value)||0,tp=parseFloat(document.getElementById('tax_per').value)||0,q=parseFloat(document.getElementById('qty').value)||0; document.getElementById('tax').value=((tp/100)*(p*q)).toFixed(2); calculate(); }
function disAmtF()  { var p=parseFloat(document.getElementById('unit_price').value)||0,d=parseFloat(document.getElementById('discount').value)||0,q=parseFloat(document.getElementById('qty').value)||0; document.getElementById('dis_per').value=(p&&q)?((d/(p*q))*100).toFixed(2):0; calculate(); }
function disPerF()  { var p=parseFloat(document.getElementById('unit_price').value)||0,dp=parseFloat(document.getElementById('dis_per').value)||0,q=parseFloat(document.getElementById('qty').value)||0; document.getElementById('discount').value=((dp/100)*(p*q)).toFixed(2); calculate(); }

function validateSaleForm() {
    var stk  = document.getElementById('stk').value;
    var req  = parseFloat(document.getElementById('qty').value)||0;
    var avbl = parseFloat(document.getElementById('remaining_qtyy').value)||0;
    if (!stk || req > avbl) {
        alert('Stock ID not selected OR quantity exceeds available stock (' + avbl + ')!');
        return false;
    }
    return true;
}
@endif
</script>

@endsection
