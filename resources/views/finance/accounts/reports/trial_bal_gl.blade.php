<!DOCTYPE html>
<html>
<head>
<title>GSL Trial Balance</title>
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
<style>
body{font-family:Arial,sans-serif;padding:20px;}
.header{text-align:center;margin-bottom:15px;}
th{background:#555;color:#fff;padding:8px;}
td{padding:6px 10px;border-bottom:1px solid #eee;font-size:13px;}
.pos{color:green;font-weight:bold;}
.neg{color:red;font-weight:bold;}
tfoot tr{background:yellow;}
</style>
</head>
<body>
<div class="header">
  <h3>GSL Trial Balance</h3>
  <p>As on {{ $asOn }}</p>
</div>
<table id="myTable" class="display">
  <thead><tr>
    <th>GSL/Range</th><th>GSL Name</th><th>Debit</th><th>Credit</th><th>Balance</th>
  </tr></thead>
  <tbody>
  @foreach($rows as $row)
  <tr>
    <td>
      <form method="POST" action="{{ route('accounts.report.gsl-report') }}" target="_blank">
        @csrf
        <input type="hidden" name="reservation" value="{{ $reservation }}">
        <input type="hidden" name="GSL_code" value="{{ $row->GSL_code }}">
        <input type="hidden" name="GLS_name" value="{{ $row->GSL_name }}">
        <button type="submit" style="background:none;border:none;color:blue;text-decoration:underline;cursor:pointer;padding:0;">
          {{ $row->GSL_code }}
        </button>
      </form>
    </td>
    <td>{{ $row->GSL_name }}</td>
    <td>{{ number_format($row->Debit,2) }}</td>
    <td>{{ number_format($row->Credit,2) }}</td>
    <td class="{{ $row->Balance >= 0 ? 'pos' : 'neg' }}">{{ number_format($row->Balance,2) }}</td>
  </tr>
  @endforeach
  </tbody>
  @if($total)
  <tfoot><tr>
    <td>{{ $total->rang_start }} – {{ $total->rang_end }}</td>
    <td>{{ $total->GL_name }}</td>
    <td>{{ number_format($total->Debit,2) }}</td>
    <td>{{ number_format($total->Credit,2) }}</td>
    <td class="{{ $total->TrialBalance >= 0 ? 'pos' : 'neg' }}">{{ number_format($total->TrialBalance,2) }}</td>
  </tr></tfoot>
  @endif
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
