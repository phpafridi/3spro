<!DOCTYPE html>
<html>
<head>
<title>P&L Overall</title>
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<style>
body{font-family:Arial,sans-serif;padding:20px;}
th{background:#555;color:#fff;padding:8px;}
td{padding:6px 10px;border-bottom:1px solid #eee;font-size:13px;}
h3,p{text-align:center;}
.pos{color:green;font-weight:bold;}
.neg{color:red;font-weight:bold;}
</style>
</head>
<body>
<h3>Overall Profit / Loss Report</h3>
<p>{{ $from }} to {{ $to }}</p>
<table id="myTable" class="display">
  <thead><tr>
    <th>Department</th><th>Revenue</th><th>Expense</th><th>P/L</th>
  </tr></thead>
  <tbody>
  @foreach($rows as $row)
  <tr>
    <td>{{ $row->Department ?? 'Unassigned' }}</td>
    <td class="pos">{{ number_format($row->TotalRevenue,2) }}</td>
    <td class="neg">{{ number_format($row->TotalExpense,2) }}</td>
    <td class="{{ $row->ProfitLoss >= 0 ? 'pos' : 'neg' }}">{{ number_format($row->ProfitLoss,2) }}</td>
  </tr>
  @endforeach
  </tbody>
  <tfoot><tr>
    <td><strong>Total</strong></td>
    <td class="pos"><strong>{{ number_format($totalRev,2) }}</strong></td>
    <td class="neg"><strong>{{ number_format($totalExp,2) }}</strong></td>
    <td class="{{ ($totalRev-$totalExp) >= 0 ? 'pos' : 'neg' }}"><strong>{{ number_format($totalRev-$totalExp,2) }}</strong></td>
  </tr></tfoot>
</table>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(function(){ $('#myTable').DataTable({paging:false}); });
</script>
</body></html>
