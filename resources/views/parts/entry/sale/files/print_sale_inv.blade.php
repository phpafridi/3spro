<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Parts Sale Invoice GDN-{{ $invoice->sale_inv }}</title>
<style>
* { box-sizing:border-box; margin:0; padding:0; }
body { font-family:Arial,sans-serif; font-size:12px; margin:20px; }
.header { text-align:center; margin-bottom:14px; }
.header h2 { font-family:'Courier New',monospace; text-decoration:underline; font-size:16px; }
table { border-collapse:collapse; }
.info-table { width:800px; margin-bottom:10px; }
.info-table td { padding:2px 6px; }
.theads     { background:#CCC; color:#000; font-size:12px; font-weight:bold; }
.theads_ans { background:#CCC; font-family:'Courier New',monospace; font-size:12px; }
.parts-table { width:800px; margin-top:10px; }
.parts-table th { background:#8E8E8E; color:#fff; font-size:13px; padding:5px 8px; text-align:left; }
.parts-table td { padding:4px 8px; border-bottom:1px solid #ddd; }
.total-row th, .total-row td { background:#8E8E8E; color:#fff; padding:5px 8px; }
.sub-row   th, .sub-row   td { background:#CCC; color:#000; padding:4px 8px; }
.sig-table { width:800px; margin-top:28px; }
.sig-table td { padding:4px 10px; }
/* Gate pass */
.gate { border:dashed 1px #999; margin-top:30px; padding:10px; }
.gate h1 { font-family:'Courier New',monospace; text-decoration:underline; text-align:center; font-size:18px; margin:6px 0 10px; }
.gate-parts th { background:#8E8E8E; color:#fff; padding:5px 8px; }
.gate-parts td { padding:4px 8px; border-bottom:1px solid #ddd; }
@media print {
    .no-print { display:none !important; }
    .gate { page-break-before:always; }
}
</style>
</head>
<body onload="window.print()">

{{-- Header --}}
<div class="header">
    <img src="{{ asset('images/header1.png') }}" width="800" onerror="this.style.display='none'"><br>
    <h2>Parts Sale Invoice
        <small style="font-size:13px;">({{ request()->has('inv_no') ? 'Copy' : 'Original' }})</small>
    </h2>
</div>

@php
    $grossSale     = $parts->sum(fn($p) => $p->sale_price * $p->quantity);
    $totalDiscount = $parts->sum('discount');
    $totalTax      = $parts->sum('tax');
    $netAmount     = $grossSale - $totalDiscount + $totalTax;
    $saleDate      = \Carbon\Carbon::parse($invoice->datetime ?? now())->format('d M y');
@endphp

{{-- Invoice Info --}}
<table class="info-table">
    <tr>
        <td colspan="5"></td>
        <td class="theads" colspan="2">Sale Date:</td>
        <td class="theads_ans" colspan="2">{{ $saleDate }}</td>
    </tr>
    <tr>
        <td class="theads" colspan="2">Jobber:</td>
        <td class="theads_ans">{{ $invoice->Jobber }}</td>
        <td colspan="2"></td>
        <td class="theads" colspan="2">Invoice Number:</td>
        <td class="theads_ans" colspan="2">GDN-{{ $invoice->sale_inv }}</td>
    </tr>
    <tr>
        <td class="theads" colspan="2">Payment Method:</td>
        <td class="theads_ans">{{ $invoice->payment_method }}</td>
    </tr>
    @if($invoice->remarks)
    <tr>
        <td class="theads" colspan="2">Remarks:</td>
        <td class="theads_ans" colspan="6">{{ $invoice->remarks }}</td>
    </tr>
    @endif
</table>

{{-- Parts Table --}}
<table class="parts-table">
    <thead>
        <tr>
            <th>S.no</th>
            <th>Part No</th>
            <th colspan="2">Description</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Discount</th>
            <th>Tax</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($parts as $i => $p)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->part_no }}</td>
            <td colspan="2" style="font-size:10px;">{{ $p->Description ?? '' }}</td>
            <td align="center">{{ $p->quantity }}</td>
            <td align="center">{{ $p->sale_price }}</td>
            <td align="center">{{ $p->discount }}</td>
            <td align="center">{{ $p->tax }}</td>
            <td>{{ number_format($p->netamount + $p->tax, 0) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tr><td colspan="9">&nbsp;</td></tr>
    <tr class="total-row">
        <th colspan="5"></th>
        <th colspan="2" align="right">Gross Sale:</th>
        <th colspan="2" align="center">{{ number_format($grossSale, 0) }}</th>
    </tr>
    <tr class="sub-row">
        <td colspan="5"></td>
        <th colspan="2" align="right">Discount:</th>
        <th colspan="2" align="center">{{ number_format($totalDiscount, 0) }}</th>
    </tr>
    <tr class="sub-row">
        <td colspan="5"></td>
        <th colspan="2" align="right">GST 17%:</th>
        <th colspan="2" align="center">{{ number_format($totalTax, 0) }}</th>
    </tr>
    <tr class="total-row">
        <th colspan="5"></th>
        <th colspan="2" align="right">Net Amount:</th>
        <th colspan="2" align="center">Rs {{ number_format($netAmount, 0) }}</th>
    </tr>
</table>

{{-- Signatures --}}
<table class="sig-table">
    <tr>
        <td>________________________________<br>Sold By: {{ $invoice->user }}</td>
        <td>________________________________<br>Jobber: {{ $invoice->Jobber }}</td>
        <td>________________________________<br>Warehouse Incharge</td>
    </tr>
</table>

{{-- Gate Pass --}}
<div class="gate">
    <h1>GATE PASS</h1>
    <table class="info-table">
        <tr>
            <td colspan="5"></td>
            <td class="theads" colspan="2">Sale Date:</td>
            <td class="theads_ans" colspan="2">{{ $saleDate }}</td>
        </tr>
        <tr>
            <td class="theads" colspan="2">Jobber:</td>
            <td class="theads_ans">{{ $invoice->Jobber }}</td>
            <td colspan="2"></td>
            <td class="theads" colspan="2">Invoice Number:</td>
            <td class="theads_ans" colspan="2">SJV-{{ $invoice->sale_inv }}</td>
        </tr>
        <tr>
            <td class="theads" colspan="2">Payment Method:</td>
            <td class="theads_ans">{{ $invoice->payment_method }}</td>
        </tr>
    </table>
    <table class="gate-parts" style="width:700px;border-collapse:collapse;margin-top:8px;">
        <thead>
            <tr>
                <th>S.no</th>
                <th colspan="2">Description</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parts as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td colspan="2">{{ $p->Description ?? $p->part_no }}</td>
                <td>{{ $p->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <table width="100%" style="margin-top:28px;">
        <tr>
            <td align="center">Parts Manager<br><br>_____________________</td>
            <td></td>
            <td align="center">Issued By ({{ $invoice->user }})<br><br>_____________________</td>
        </tr>
    </table>
</div>

<div class="no-print" style="margin-top:20px;text-align:center;">
    <button onclick="window.print()" style="padding:8px 24px;font-size:14px;cursor:pointer;">Print</button>
</div>
</body>
</html>
