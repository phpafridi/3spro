{{-- resources/views/parts/entry/sale/files/print_purch.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Invoice #{{ $invoice->Invoice_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
        th { background: #f0f0f0; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .total { text-align: right; font-weight: bold; font-size: 14px; margin-top: 10px; }
        .info-row { display: flex; gap: 20px; margin-bottom: 15px; flex-wrap: wrap; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
<div class="header">
    @include('partials.company-header')
    <h2>Purchase Invoice {{ $tax ? '(With Tax)' : '' }}</h2>
    <p>Invoice No: <strong>#{{ $invoice->Invoice_no }}</strong> | Bill No: {{ $invoice->Invoice_number }}</p>
</div>
<div class="info-row">
    <span>Jobber: <strong>{{ $invoice->jobber }}</strong></span>
    <span>Payment: <strong>{{ $invoice->payment_method }}</strong></span>
    <span>PR: <strong>{{ $invoice->Purchase_Requis }}</strong></span>
    <span>Date: <strong>{{ $invoice->mdate ? \Carbon\Carbon::parse($invoice->mdate)->format('d-M-Y') : '-' }}</strong></span>
    <span>Delivery Note: {{ $invoice->deleverynote }}</span>
    <span>Receiver: {{ $invoice->Receivername }}</span>
</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Part No</th>
            <th>Description</th>
            <th>Category</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Net Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->stockItems ?? [] as $i => $item)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $item->part_no }}</td>
            <td>{{ $item->Description }}</td>
            <td>{{ $item->cate_type }}</td>
            <td>{{ $item->unit }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->Price, 2) }}</td>
            <td>{{ number_format($item->Netamount, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="total">Total Amount: {{ number_format($invoice->Total_amount ?? 0, 2) }}</div>
@if($tax ?? false)
<div class="total">Tax (17%): {{ number_format(($invoice->Total_amount ?? 0) * 0.17, 2) }}</div>
<div class="total">Grand Total: {{ number_format(($invoice->Total_amount ?? 0) * 1.17, 2) }}</div>
@endif
<br>
<button onclick="window.print()">Print</button>
</body>
</html>
