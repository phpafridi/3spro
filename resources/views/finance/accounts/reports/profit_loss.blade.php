<!DOCTYPE html>
<html>
<head>
<title>Profit & Loss</title>
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
<h3>Profit & Loss Statement</h3>
<p>{{ $from }} to {{ $to }}</p>
<h4>Income</h4>
<table id="t1" class="display">
  <thead><tr><th>GSL Code</th><th>GSL Name</th><th>Amount</th></tr></thead>
  <tbody>
  @foreach($income as $r)
  <tr><td>{{ $r->GSL_code }}</td><td>{{ $r->GSL_name }}</td>
      <td class="pos">{{ number_format($r->TotalIncome,2) }}</td></tr>
  @endforeach
  </tbody>
  <tfoot><tr><td colspan="2"><strong>Total Income</strong></td>
    <td class="pos"><strong>{{ number_format($totalIncome,2) }}</strong></td></tr></tfoot>
</table>
<br>
<h4>Expenses</h4>
<table id="t2" class="display">
  <thead><tr><th>GSL Code</th><th>GSL Name</th><th>Amount</th></tr></thead>
  <tbody>
  @foreach($expenses as $r)
  <tr><td>{{ $r->GSL_code }}</td><td>{{ $r->GSL_name }}</td>
      <td class="neg">{{ number_format($r->TotalExpense,2) }}</td></tr>
  @endforeach
  </tbody>
  <tfoot><tr><td colspan="2"><strong>Total Expense</strong></td>
    <td class="neg"><strong>{{ number_format($totalExpense,2) }}</strong></td></tr></tfoot>
</table>
<br>
<table style="width:300px;margin:0 auto;">
  <tr style="background:#222;color:#fff;"><td>Net Profit / Loss</td>
    <td class="{{ $net >= 0 ? 'pos' : 'neg' }}"><strong>{{ number_format($net,2) }}</strong></td></tr>
</table>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(function(){ $('#t1,#t2').DataTable({paging:false}); });
</script>
</body></html>
