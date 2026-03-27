<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Workshop Consumables Requisition</title>
<style>body{font-family:Arial,sans-serif;font-size:12px;margin:20px}table{width:100%;border-collapse:collapse;margin-top:10px}th,td{border:1px solid #ccc;padding:6px 10px}th{background:#f0f0f0}@media print{button{display:none}}</style>
</head><body>
<h2>Workshop Consumables Requisition - JC #{{ $inv_id }}</h2>
<table><thead><tr><th>#</th><th>RO No</th><th>Description</th><th>Qty</th><th>Issued Qty</th><th>Unit Price</th><th>Total</th></tr></thead>
<tbody>@forelse($consumbles as $i => $c)<tr><td>{{ $i+1 }}</td><td>{{ $c->RO_no }}</td><td>{{ $c->cons_description ?? $c->part_description }}</td><td>{{ $c->qty }}</td><td>{{ $c->issued_qty ?? 0 }}</td><td>{{ number_format($c->unitprice ?? 0, 2) }}</td><td>{{ number_format($c->total ?? 0, 2) }}</td></tr>@empty<tr><td colspan="8" style="text-align:center">No consumables</td></tr>@endforelse</tbody></table>
<br><button onclick="window.print()">Print</button>
</body></html>
