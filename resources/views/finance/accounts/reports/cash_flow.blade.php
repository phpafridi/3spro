<!DOCTYPE html>
<html>
<head>
<title>Cash Flow Report</title>
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
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
<h3>Cash Flow Report</h3>
<p>{{ $from }} to {{ $to }}</p>
<table id="myTable" class="display">
  <thead><tr>
    <th>GL Name</th><th>Opening</th><th>Period Receipts</th><th>Period Payments</th><th>Closing</th>
  </tr></thead>
  <tbody>
  @foreach($rows as $row)
  @php $closing = $row->opening + $row->totalCredit - $row->totalDebit; @endphp
  <tr>
    <td>{{ $row->GL_name }}</td>
    <td>{{ number_format($row->opening,2) }}</td>
    <td class="pos">{{ number_format($row->totalCredit,2) }}</td>
    <td class="neg">{{ number_format($row->totalDebit,2) }}</td>
    <td class="{{ $closing >= 0 ? 'pos' : 'neg' }}">{{ number_format($closing,2) }}</td>
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
