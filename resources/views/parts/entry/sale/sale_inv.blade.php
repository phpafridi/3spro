@extends('parts.layout')
@section('title', 'Parts Sale - Invoice {{ $invoice->sale_inv }}')
@section('content')

<style>
.sale-table { width:100%; border-collapse:collapse; }
.sale-table td, .sale-table th { padding:6px 10px; }
.sale-table .lbl { font-weight:600; font-size:13px; color:#555; white-space:nowrap; }
.sale-table input[type=text], .sale-table input[type=number], .sale-table select {
    border:1px solid #d1d5db; border-radius:4px; padding:5px 8px; font-size:13px; width:100%;
}
.sale-table input:focus { outline:none; border-color:#dc2626; }
.imp { color:#dc2626; }
#description { color:#dc2626; font-size:12px; font-weight:600; margin-top:2px; }
#lbl { font-weight:700; color:#dc2626; font-size:14px; min-height:20px; }
#netprice { font-weight:700; font-size:15px; color:#1a1a1a; }
.sale-panel {
    background:linear-gradient(135deg,#f8e8e8,#fce4e4);
    border:1px solid #f5c6c6; border-radius:10px; padding:20px; max-width:480px;
}
.sale-panel h1 { text-align:center; font-size:22px; font-weight:800; color:#991b1b; margin-bottom:16px; }
#stock_table_wrap table { width:100%; font-size:12px; border-collapse:collapse; }
#stock_table_wrap th { background:#3498db; color:#fff; padding:4px 8px; }
#stock_table_wrap td { padding:4px 8px; border-bottom:1px solid #e5e7eb; }
#stock_table_wrap tr.clickrow:hover td { background:rgba(0,0,255,0.1); cursor:pointer; }
#stock_table_wrap tr.clickrow td { cursor:pointer; }
/* Invoice lines table */
.inv-lines { width:100%; border-collapse:collapse; font-size:13px; margin-top:10px; }
.inv-lines th { background:#374151; color:#fff; padding:6px 8px; text-align:left; }
.inv-lines td { padding:5px 8px; border-bottom:1px solid #e5e7eb; }
.inv-lines tr:hover td { background:#f9fafb; }
</style>

@if(session('success'))
<div style="background:#d1fae5;border-left:4px solid #10b981;padding:10px 14px;border-radius:4px;margin-bottom:12px;font-size:13px;">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#fee2e2;border-left:4px solid #ef4444;padding:10px 14px;border-radius:4px;margin-bottom:12px;font-size:13px;">
    {{ session('error') }}
</div>
@endif

<table width="100%" border="0"><tr>

{{-- ── LEFT: Add Part Form ─────────────────────────────── --}}
<td width="48%" valign="top">
<div class="sale-panel">
    <h1>Parts Sell</h1>

    <form action="{{ route('parts.sale.invoice.part.store') }}" method="POST" name="saleForm"
          onsubmit="return validateSaleForm()">
        @csrf
        <input type="hidden" name="sale_inv"  value="{{ $invoice->sale_inv }}">
        <input type="hidden" name="stock_id"  id="stk">
        <input type="hidden" name="part_no"   id="partNoHidden">
        <input type="hidden" name="descript"  id="descript">
        <input type="hidden" name="remaining_qtyy" id="remaining_qtyy">

        <table class="sale-table">
            <tr>
                <td class="lbl" colspan="2">
                    Invoice ID — <span style="color:#dc2626;font-size:16px;font-weight:700;">{{ $invoice->sale_inv }}</span>
                    &nbsp;|&nbsp; {{ $invoice->Jobber }} &nbsp;|&nbsp; {{ $invoice->payment_method }}
                </td>
            </tr>

            {{-- Part number search --}}
            <tr>
                <td class="lbl">Part Number <span class="imp">*</span>:</td>
                <td>
                    <input type="text" id="partno" autocomplete="off"
                           placeholder="Type Part number" required oninput="searchPart(this.value)">
                    <div id="description"></div>
                </td>
            </tr>

            {{-- Stock ID — auto-filled by clicking row --}}
            <tr>
                <td class="lbl">Stock ID <span class="imp">*</span>:</td>
                <td><div id="lbl" style="min-height:20px;"></div></td>
            </tr>

            {{-- Quantity --}}
            <tr>
                <td class="lbl">Quantity <span class="imp">*</span>:</td>
                <td>
                    <input type="number" name="quantity" id="qty" min="1" required oninput="calculate()">
                </td>
            </tr>

            {{-- Sale Price --}}
            <tr>
                <td class="lbl">Sale Price <span class="imp">*</span>:</td>
                <td>
                    <input type="number" name="sale_price" id="unit_price"
                           placeholder="Rs" min="0" step="0.01" required oninput="calculate()">
                </td>
            </tr>

            {{-- Discount --}}
            <tr>
                <td class="lbl">Discount:</td>
                <td style="display:flex;gap:4px;align-items:center;">
                    <input type="number" name="dis_per" id="dis_per" value="0" min="0" max="100"
                           step="any" style="width:50px;" oninput="disPerF()">
                    <span>%</span>
                    <input type="number" name="discount" id="discount" value="0" min="0" step="0.01"
                           required oninput="disAmtF()">
                </td>
            </tr>

            {{-- Tax --}}
            <tr>
                <td class="lbl">Tax:</td>
                <td style="display:flex;gap:4px;align-items:center;">
                    <input type="number" name="tax_per" id="tax_per" value="0" min="0" max="100"
                           step="any" style="width:50px;" oninput="taxPerF()">
                    <span>%</span>
                    <input type="number" name="tax" id="tax" value="0" min="0" step="0.01"
                           required oninput="taxAmtF()">
                </td>
            </tr>

            {{-- Net Amount --}}
            <tr>
                <td class="lbl">Net Amount</td>
                <td><div id="netprice">0.00</div></td>
            </tr>

            <tr>
                <td colspan="2" style="text-align:center;padding-top:10px;">
                    <button type="submit"
                            style="background:#7a1a1a;color:#fff;padding:8px 28px;font-size:14px;
                                   font-weight:700;border-radius:20px;border:none;cursor:pointer;">
                        Submit &amp; Add More
                    </button>
                </td>
            </tr>
        </table>
    </form>
</div>
</td>

{{-- ── RIGHT: Stock search results + Invoice lines ──────── --}}
<td width="52%" valign="top" style="padding-left:16px;">

    {{-- Stock search results (click a row to select) --}}
    <div id="stock_table_wrap"></div>

    {{-- Invoice lines --}}
    @if($parts->count() > 0)
    <div style="margin-top:20px;">
        <h3 style="font-size:14px;font-weight:700;color:#1f2937;margin-bottom:6px;">
            Invoice Lines — SJ-{{ $invoice->sale_inv }}
        </h3>
        <table class="inv-lines">
            <thead>
                <tr>
                    <th>#</th><th>Part No</th><th>Qty</th>
                    <th>Price</th><th>Disc</th><th>Tax</th><th>Net</th><th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($parts as $i => $part)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><strong>{{ $part->part_no }}</strong></td>
                    <td>{{ $part->quantity }}</td>
                    <td>{{ number_format($part->sale_price,0) }}</td>
                    <td>{{ number_format($part->discount,0) }}</td>
                    <td>{{ number_format($part->tax,0) }}</td>
                    <td><strong>{{ number_format($part->netamount + $part->tax, 0) }}</strong></td>
                    <td>
<form method="POST" action="{{ route('parts.sale.invoice.delete') }}"
      onsubmit="return confirm('Delete and restore stock?')">
    @csrf
    <input type="hidden" name="sell_id"  value="{{ $part->sell_id }}">
    <input type="hidden" name="stock_id" value="{{ $part->stock_id }}">
    <input type="hidden" name="quantity" value="{{ $part->quantity }}">
    <input type="hidden" name="inv_no"   value="{{ $invoice->sale_inv }}">
    <button type="submit"
            style="background:#dc2626;color:#fff;border:none;border-radius:4px;
                   padding:2px 8px;cursor:pointer;font-size:12px;">✕</button>
</form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <table style="width:220px;margin-left:auto;margin-top:8px;font-size:13px;border-collapse:collapse;">
            <tr><td style="padding:3px 8px;color:#555;">Gross Sale:</td>
                <td style="padding:3px 8px;text-align:right;">{{ number_format($grossSale,0) }}</td></tr>
            <tr><td style="padding:3px 8px;color:#f97316;">Discount:</td>
                <td style="padding:3px 8px;text-align:right;color:#f97316;">{{ number_format($totalDiscount,0) }}</td></tr>
            <tr><td style="padding:3px 8px;color:#3b82f6;">Tax:</td>
                <td style="padding:3px 8px;text-align:right;color:#3b82f6;">{{ number_format($totalTax,0) }}</td></tr>
            <tr style="border-top:2px solid #374151;font-weight:700;font-size:15px;">
                <td style="padding:4px 8px;">Net Amount:</td>
                <td style="padding:4px 8px;text-align:right;color:#dc2626;">
                    Rs {{ number_format($netAmount,0) }}
                </td>
            </tr>
        </table>

        {{-- Close & Print --}}
        <form method="POST" action="{{ route('parts.sale.close') }}" style="margin-top:12px;">
            @csrf
            <input type="hidden" name="inv_no"      value="{{ $invoice->sale_inv }}">
            <input type="hidden" name="totalamount" value="{{ $totalAmount }}">
            <input type="hidden" name="discount"    value="{{ $totalDiscount }}">
            <input type="hidden" name="tax"         value="{{ $totalTax }}">
            <div style="margin-bottom:6px;">
                <textarea name="remarks" rows="2" placeholder="Remarks (optional)..."
                          style="width:100%;border:1px solid #d1d5db;border-radius:4px;
                                 padding:6px 8px;font-size:13px;"></textarea>
            </div>
            <button type="submit"
                    style="width:100%;background:#1e3a5f;color:#fff;padding:10px;font-size:14px;
                           font-weight:700;border-radius:6px;border:none;cursor:pointer;">
                Close &amp; Print Invoice
            </button>
        </form>
    </div>
    @endif

</td>
</tr></table>

<script>
var searchTimer;

function searchPart(val) {
    clearTimeout(searchTimer);
    val = val.trim();
    if (val.length < 2) {
        document.getElementById('stock_table_wrap').innerHTML = '';
        document.getElementById('description').innerHTML = '';
        return;
    }
    searchTimer = setTimeout(function() {
        // Get description (like description.php in original)
        fetch('{{ route("parts.ajax.search-part-desc") }}?partn=' + encodeURIComponent(val))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                document.getElementById('description').innerHTML = data.desc || '';
                document.getElementById('descript').value = data.desc || '';
            })
            .catch(function(e) { console.error('Description fetch error:', e); });

        // Get stock table (like search_stock.php in original)
        fetch('{{ route("parts.ajax.search-stock") }}?key=' + encodeURIComponent(val))
            .then(function(r) { return r.json(); })
            .then(function(data) { renderStockTable(data); })
            .catch(function(e) { 
                console.error('Stock fetch error:', e);
                document.getElementById('stock_table_wrap').innerHTML = 
                    '<p style="color:#dc2626;font-size:13px;margin-top:10px;">Error loading stock data</p>';
            });
    }, 350);
}
function renderStockTable(data) {
    var wrap = document.getElementById('stock_table_wrap');
    if (!data.length) {
        wrap.innerHTML = '<p style="color:#dc2626;font-size:13px;margin-top:10px;">Part not found in stock</p>';
        return;
    }
    var html = '<div style="max-height:320px;overflow-y:auto;margin-top:6px;">' +
        '<table><thead><tr>' +
        '<th>Stock ID</th><th>Part No</th><th>Description</th>' +
        '<th>Avail Qty</th><th>Price</th>' +
        '</tr></thead><tbody>';

    data.forEach(function(s) {
        var canClick = s.remain_qty > 0;
        var rowStyle = canClick ? '' : 'style="background:#fee2e2;opacity:0.7;"';
        var clickAttr = canClick
            ? 'class="clickrow" onclick="selectStock(' + s.value + ',' + s.remain_qty + ',\'' +
              escStr(s.part_no) + '\',\'' + escStr(s.desc) + '\',' + (s.price||0) + ')"'
            : '';
        html += '<tr ' + rowStyle + ' ' + clickAttr + '>';
        html += '<td><img src="{{ asset("images/arrow.png") }}" onerror="this.style.display=\'none\'" style="width:12px;margin-right:3px;">' + s.value + '</td>';
        html += '<td>' + s.part_no + '</td>';
        html += '<td style="font-size:11px;">' + (s.desc||'') + '</td>';
        html += '<td style="font-weight:700;color:' + (s.remain_qty>0?'#16a34a':'#dc2626') + ';">' + s.remain_qty + '</td>';
        html += '<td>Rs ' + (s.price||0) + '</td>';
        html += '</tr>';
    });

    html += '</tbody></table></div>';
    wrap.innerHTML = html;
}

function escStr(s) {
    return (s||'').replace(/'/g, "\\'").replace(/"/g, '&quot;');
}

function selectStock(stockId, remQty, partNo, desc, price) {
    document.getElementById('stk').value           = stockId;
    document.getElementById('remaining_qtyy').value = remQty;
    document.getElementById('partNoHidden').value   = partNo;
    document.getElementById('descript').value       = desc;
    document.getElementById('lbl').innerHTML        = '<span style="color:#dc2626;">' + stockId + '</span> &nbsp; Avail: <strong>' + remQty + '</strong>';
    document.getElementById('description').textContent = desc;
    // Auto-fill price
    if (price && !document.getElementById('unit_price').value) {
        document.getElementById('unit_price').value = price;
        calculate();
    }
}

function calculate() {
    var price    = parseFloat(document.getElementById('unit_price').value) || 0;
    var qty      = parseFloat(document.getElementById('qty').value) || 0;
    var discount = parseFloat(document.getElementById('discount').value) || 0;
    var tax      = parseFloat(document.getElementById('tax').value) || 0;
    var net      = (price * qty) - discount + tax;
    document.getElementById('netprice').textContent = net.toFixed(2);
}
function taxAmtF() {
    var price = parseFloat(document.getElementById('unit_price').value)||0;
    var qty   = parseFloat(document.getElementById('qty').value)||0;
    var tax   = parseFloat(document.getElementById('tax').value)||0;
    document.getElementById('tax_per').value = (price&&qty) ? ((tax/(price*qty))*100).toFixed(2) : 0;
    calculate();
}
function taxPerF() {
    var price  = parseFloat(document.getElementById('unit_price').value)||0;
    var qty    = parseFloat(document.getElementById('qty').value)||0;
    var taxPer = parseFloat(document.getElementById('tax_per').value)||0;
    document.getElementById('tax').value = ((taxPer/100)*(price*qty)).toFixed(2);
    calculate();
}
function disAmtF() {
    var price    = parseFloat(document.getElementById('unit_price').value)||0;
    var qty      = parseFloat(document.getElementById('qty').value)||0;
    var discount = parseFloat(document.getElementById('discount').value)||0;
    document.getElementById('dis_per').value = (price&&qty) ? ((discount/(price*qty))*100).toFixed(2) : 0;
    calculate();
}
function disPerF() {
    var price  = parseFloat(document.getElementById('unit_price').value)||0;
    var qty    = parseFloat(document.getElementById('qty').value)||0;
    var disPer = parseFloat(document.getElementById('dis_per').value)||0;
    document.getElementById('discount').value = ((disPer/100)*(price*qty)).toFixed(2);
    calculate();
}
function validateSaleForm() {
    var stk  = document.getElementById('stk').value;
    var qty  = parseFloat(document.getElementById('qty').value)||0;
    var avbl = parseFloat(document.getElementById('remaining_qtyy').value)||0;
    if (!stk) { alert('Click a stock row first to select the stock!'); return false; }
    if (qty > avbl) { alert('Not enough stock! Available: ' + avbl); return false; }
    return true;
}
</script>
@endsection
