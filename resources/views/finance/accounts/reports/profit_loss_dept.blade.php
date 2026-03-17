<!DOCTYPE html>
<html>
<head>
<title>P&L Department</title>
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
<h3>{{ $deptName }} — Profit/Loss Report</h3>
<p>{{ $from }} to {{ $to }}</p>
<table>
  <tr><td>Total Income</td><td class="pos">{{ number_format($totalIncome,2) }}</td></tr>
  <tr><td>Total Expense</td><td class="neg">{{ number_format($totalExpense,2) }}</td></tr>
  <tr style="background:#222;color:#fff;"><td><strong>Net P/L</strong></td>
    <td class="{{ $net >= 0 ? 'pos' : 'neg' }}"><strong>{{ number_format($net,2) }}</strong></td></tr>
</table>
</body></html>
