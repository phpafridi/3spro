<!DOCTYPE html>
<html>
<head>
<title>{{ $gslName }} GSL Report</title>
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
<style>
body{font-family:Arial,sans-serif;padding:20px;}
.header{text-align:center;margin-bottom:15px;}
th{background:#555;color:#fff;padding:8px;}
td{padding:6px 10px;border-bottom:1px solid #eee;font-size:13px;}
tfoot tr{background:#ffffcc;font-weight:bold;}
</style>
</head>
<body>
<div class="header">
  <h4>Account Code: <strong>{{ $gslCode }}</strong></h4>
  <h4>{{ $gslName }}</h4>
  <p>{{ $from }} to {{ $to }}</p>
</div>
<table id="myTable" class="display">
  <thead><tr>
    <th>Date</th><th>Ref No</th><th>Voucher</th><th>Description</th><th>Debit</th><th>Credit</th><th>Balance</th>
  </tr></thead>
  <tbody>
  @php $balance = $opening; @endphp
  @if($opening != 0)
  <tr style="background:#e8f4fd;">
    <td colspan="4"><strong>Opening Balance</strong></td>
    <td></td><td></td>
    <td>{{ number_format($opening,2) }}</td>
  </tr>
  @endif
  @foreach($rows as $row)
  @php
    $dr = (float)$row->Debit;
    $cr = (float)$row->Credit;
    $balance += $dr - $cr;
  @endphp
  <tr>
    <td>{{ \Carbon\Carbon::parse($row->VoucherDate)->format('d-M-y') }}</td>
    <td>{{ $row->RefNo }}</td>
    <td>{{ $row->vchr_type }}</td>
    <td>{{ $row->Description }}</td>
    <td style="color:red">{{ $dr ? number_format($dr,2) : '' }}</td>
    <td style="color:green">{{ $cr ? number_format($cr,2) : '' }}</td>
    <td>{{ number_format($balance,2) }}</td>
  </tr>
  @endforeach
  </tbody>
  <tfoot><tr>
    <td colspan="4"><strong>Total</strong></td>
    <td style="color:red"><strong>{{ number_format($totalDr,2) }}</strong></td>
    <td style="color:green"><strong>{{ number_format($totalCr,2) }}</strong></td>
    <td><strong>{{ number_format($totalDr - $totalCr,2) }}</strong></td>
  </tr></tfoot>
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
