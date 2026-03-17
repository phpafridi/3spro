<!DOCTYPE html>
<html>
<head>
<title>GL Trial Balances</title>
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
<style>
body{font-family:Arial,sans-serif;padding:20px;}
.header{text-align:center;margin-bottom:15px;}
table{width:100%;border-collapse:collapse;}
th{background:#555;color:#fff;padding:8px;}
td{padding:6px 10px;border-bottom:1px solid #eee;font-size:13px;}
.pos{color:green;font-weight:bold;}
.neg{color:red;font-weight:bold;}
</style>
</head>
<body>
<div class="header">
  <h3>GL Trial Balances</h3>
  <p>As on {{ $asOn }}</p>
</div>
<table id="myTable" class="display">
  <thead><tr>
    <th>Sr#</th><th>Range</th><th>GL Name</th><th>Debit</th><th>Credit</th><th>Balance</th>
  </tr></thead>
  <tbody>
  @foreach($rows as $i => $row)
  <tr>
    <td>{{ $i+1 }}</td>
    <td>{{ $row->rang_start }} – {{ $row->rang_end }}</td>
    <td>{{ $row->GL_name }}</td>
    <td>{{ number_format($row->Debit,2) }}</td>
    <td>{{ number_format($row->Credit,2) }}</td>
    <td class="{{ $row->TrialBalance >= 0 ? 'pos' : 'neg' }}">{{ number_format($row->TrialBalance,2) }}</td>
  </tr>
  @endforeach
  </tbody>
</table>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script>
$(function(){ $('#myTable').DataTable({paging:false,dom:'Bfrtip',buttons:['excel','csv','print']}); });
</script>
</body></html>
