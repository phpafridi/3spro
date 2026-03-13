<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Purchase Return PRJV #{{ $invoice_no }}</title>
<style>body{font-family:Arial,sans-serif;font-size:12px;margin:20px}table{width:100%;border-collapse:collapse;margin-top:10px}th,td{border:1px solid #ccc;padding:6px 10px}th{background:#f0f0f0}@media print{button{display:none}}</style>
</head><body>
<h2>Purchase Return - PRJV #{{ $invoice_no }}</h2>
<table><thead><tr><th>#</th><th>Invoice No</th><th>Stock ID</th><th>Unit Price</th><th>Return Qty</th><th>Return By</th><th>Reason</th><th>Date</th></tr></thead>
<tbody>@foreach($return as $i => $r)<tr><td>{{ $i+1 }}</td><td>{{ $r->invoice_no }}</td><td>{{ $r->stock_id }}</td><td>{{ number_format($r->unit_price,2) }}</td><td>{{ $r->return_qty }}</td><td>{{ $r->return_by }}</td><td>{{ $r->reason }}</td><td>{{ $r->datetime ?? '-' }}</td></tr>@endforeach</tbody></table>
<br><button onclick="window.print()">Print</button>
</body></html>
