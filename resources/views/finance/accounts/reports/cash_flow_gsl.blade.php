<!DOCTYPE html>
<html>
<head>
<title>Cash Flow - {{ $glName }}</title>
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
<h4 align="center">{{ $glName }}'s Cash Flow</h4>
<p align="center">{{ $from }} to {{ $to }}</p>
<table id="myTable" class="display">
  <thead><tr>
    <th>GSL Code</th><th>GSL Name</th><th>Amount</th>
  </tr></thead>
  <tbody>
  @foreach($rows as $row)
  @php $closing = $row->totalCredit - $row->totalDebit; @endphp
  <tr>
    <td>{{ $row->GSL_code }}</td>
    <td>{{ $row->GSL_name }}</td>
    <td class="{{ $closing >= 0 ? 'pos' : 'neg' }}">{{ number_format($closing,2) }}</td>
  </tr>
  @endforeach
  </tbody>
  <tfoot><tr>
    <td colspan="2"><strong>Net</strong></td>
    <td class="{{ $netClosing >= 0 ? 'pos' : 'neg' }}"><strong>{{ number_format($netClosing,2) }}</strong></td>
  </tr></tfoot>
</table>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(function(){ $('#myTable').DataTable({paging:false}); });
</script>
</body></html>
