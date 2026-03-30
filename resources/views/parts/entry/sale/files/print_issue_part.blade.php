<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Workshop Parts Requisition</title>
<style>body{font-family:Arial,sans-serif;font-size:12px;margin:20px}table{width:100%;border-collapse:collapse;margin-top:10px}th,td{border:1px solid #ccc;padding:6px 10px}th{background:#f0f0f0}@media print{button{display:none}}</style>
</head><body>
@include('partials.company-header')
<h2>Workshop Parts Requisition - JC #{{ $inv_id }}</h2>
<table><thead><tr><th>#</th><th>RO No</th><th>Part No</th><th>Description</th><th>Location</th><th>Issued Qty</th><th>Req Qty</th></tr></thead>
<tbody>@forelse($parts as $i => $p)<tr><td>{{ $i+1 }}</td><td>{{ $p->RO_no }}</td><td>{{ $p->part_number }}</td><td>{{ $p->Description }}</td><td>{{ $p->Location }}</td><td>{{ $p->issued_qty ?? 0 }}</td><td>{{ $p->req_qty ?? $p->quantity }}</td></tr>@empty<tr><td colspan="8" style="text-align:center">No parts</td></tr>@endforelse</tbody></table>
<br><button onclick="window.print()">Print</button>
</body></html>
