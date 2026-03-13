{{-- resources/views/parts/entry/sale/files/print_sale_inv.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sale Invoice #{{ $invoice->sale_inv }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
        th { background: #f0f0f0; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .info-row { display: flex; gap: 20px; margin-bottom: 15px; font-size: 12px; }
        .total { text-align: right; font-weight: bold; font-size: 14px; margin-top: 10px; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
<div class="header">
    <h2>Counter Sale Invoice</h2>
    <p>Invoice No: <strong>#{{ $invoice->sale_inv }}</strong></p>
</div>
<div class="info-row">
    <span>Jobber/Customer: <strong>{{ $invoice->Jobber }}</strong></span>
    <span>Payment: <strong>{{ $invoice->payment_method }}</strong></span>
    <span>Date: <strong>{{ $invoice->created_at ? \Carbon\Carbon::parse($invoice->created_at)->format('d-M-Y') : '-' }}</strong></span>
    <span>User: <strong>{{ $invoice->user }}</strong></span>
</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Part No</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Net Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->parts ?? [] as $i => $part)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $part->part_no }}</td>
            <td>{{ $part->Description ?? '' }}</td>
            <td>{{ $part->quantity }}</td>
            <td>{{ number_format($part->sale_price, 2) }}</td>
            <td>{{ number_format($part->netamount, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="total">Total: {{ number_format($invoice->Total_amount ?? 0, 2) }}</div>
<br>
<button onclick="window.print()">Print</button>
</body>
</html>
