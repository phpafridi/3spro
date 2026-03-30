<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Workshop Parts Return WPR #{{ $WPR }}</title>
<style>body{font-family:Arial,sans-serif;font-size:12px;margin:20px}table{width:100%;border-collapse:collapse;margin-top:10px}th,td{border:1px solid #ccc;padding:6px 10px}th{background:#f0f0f0}@media print{button{display:none}}</style>
</head><body>
@include('partials.company-header')
<h2>Workshop Parts Return{{ $WPR ? ' - WPR #'.$WPR : '' }}</h2>
<table><thead><tr><th>#</th><th>Part No</th><th>Qty</th><th>Status</th></tr></thead>
<tbody>@forelse($parts as $i => $p)<tr><td>{{ $i+1 }}</td><td>{{ $p->part_number }}</td><td>{{ $p->quantity }}</td><td>{{ $p->p_return }}</td></tr>@empty<tr><td colspan="4" style="text-align:center">No data</td></tr>@endforelse</tbody></table>
<br><button onclick="window.print()">Print</button>
</body></html>
