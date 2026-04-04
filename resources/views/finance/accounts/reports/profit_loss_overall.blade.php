<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>P&L — All Departments</title>
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; color: #222; }
  .page-wrap { max-width: 1060px; margin: 0 auto; padding: 24px 20px 48px; }
  .company-header { background: #fff; border-radius: 10px; padding: 20px 24px 14px; text-align: center;
    border-bottom: 3px solid #374151; margin-bottom: 20px; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
  .company-header img { height: 54px; margin: 0 auto 6px; display: block; }
  .company-name { font-size: 20px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #111; }
  .company-sub  { font-size: 11px; color: #555; margin-top: 3px; }
  .report-title { border-radius: 10px; padding: 16px 24px; margin-bottom: 20px;
    display: flex; justify-content: space-between; align-items: center; }
  .report-title h2 { font-size: 16px; font-weight: 600; }
  .report-title .period { font-size: 12px; opacity: .85; margin-top: 3px; }
  .report-title .badge { background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.4);
    border-radius: 6px; padding: 4px 14px; font-size: 11px; font-weight: 600; white-space: nowrap; }
  .rpt-table { width: 100%; border-collapse: collapse; background: #fff;
    border-radius: 10px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); margin-bottom: 20px; }
  .rpt-table thead th { background: #374151; color: #fff; padding: 10px 14px;
    text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
  .rpt-table thead th.num { text-align: right; }
  .rpt-table tbody tr:nth-child(even) { background: #f9fafb; }
  .rpt-table tbody tr:hover { background: #eff6ff; }
  .rpt-table tbody td { padding: 9px 14px; font-size: 13px; border-bottom: 1px solid #f1f3f5; }
  .rpt-table tbody td.num { text-align: right; font-family: 'Courier New', monospace; font-size: 12px; }
  .rpt-table tfoot td { padding: 10px 14px; font-size: 13px; font-weight: 700;
    background: #1e293b; color: #fff; }
  .rpt-table tfoot td.num { text-align: right; font-family: 'Courier New', monospace; }
  .amt-dr { color: #dc2626; } .amt-cr { color: #16a34a; }
  .amt-pos { color: #16a34a; font-weight: 700; } .amt-neg { color: #dc2626; font-weight: 700; }
  .opening-row { background: #eff6ff !important; font-style: italic; color: #1e40af; }
  .toolbar { display: flex; justify-content: flex-end; gap: 8px; margin-bottom: 10px; }
  .btn-act { padding: 6px 16px; background: #374151; color: #fff; border: none; border-radius: 6px;
    font-size: 12px; cursor: pointer; font-weight: 600; }
  .btn-act:hover { background: #1f2937; }
  @media print {
    body { background: #fff; }
    .toolbar, .dataTables_filter, .dataTables_length, .dt-buttons { display: none !important; }
    .page-wrap { padding: 0; }
    .rpt-table { box-shadow: none; }
  }
.report-title { background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); color: #fff; }
.bar-wrap { width:100%;background:#e5e7eb;border-radius:4px;height:8px;margin-top:4px; }
.bar-fill  { height:8px;border-radius:4px; }
</style>
</head>
<body>
<div class="page-wrap">
  <div class="company-header">
    @php $logoPath = public_path(config('company.logo_path','images/logo.png')); @endphp
    @if(file_exists($logoPath))<img src="{{ asset(config('company.logo_path','images/logo.png')) }}" alt="{{ config('company.name') }}" onerror="this.style.display='none'">@endif
    <div class="company-name">{{ config('company.name','Your Company') }}</div>
    <div class="company-sub">{{ config('company.location','') }} &nbsp;|&nbsp; {{ config('company.phone','') }}</div>
  </div>
  <div class="report-title">
    <div>
      <h2>Overall Profit / Loss — All Departments</h2>
      <div class="period">Period: {{ $from }} &nbsp;–&nbsp; {{ $to }}</div>
    </div>
    <div class="badge">{{ count($rows) }} Departments</div>
  </div>

  {{-- Summary --}}
  @php $netTotal = $totalRev - $totalExp; @endphp
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
    <div style="background:#fff;border-radius:8px;padding:12px 16px;box-shadow:0 1px 4px rgba(0,0,0,.07);border-top:3px solid #16a34a;">
      <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#888;">Total Revenue</div>
      <div style="font-size:17px;font-weight:700;font-family:monospace;color:#16a34a;">{{ number_format($totalRev,2) }}</div>
    </div>
    <div style="background:#fff;border-radius:8px;padding:12px 16px;box-shadow:0 1px 4px rgba(0,0,0,.07);border-top:3px solid #dc2626;">
      <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#888;">Total Expense</div>
      <div style="font-size:17px;font-weight:700;font-family:monospace;color:#dc2626;">{{ number_format($totalExp,2) }}</div>
    </div>
    <div style="background:#fff;border-radius:8px;padding:12px 16px;box-shadow:0 1px 4px rgba(0,0,0,.07);border-top:3px solid {{ $netTotal >= 0 ? '#2563eb' : '#dc2626' }};">
      <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#888;">Net {{ $netTotal >= 0 ? 'Profit' : 'Loss' }}</div>
      <div style="font-size:17px;font-weight:700;font-family:monospace;color:{{ $netTotal >= 0 ? '#2563eb' : '#dc2626' }};">{{ number_format(abs($netTotal),2) }}</div>
    </div>
  </div>

  <div class="toolbar"><button class="btn-act" onclick="window.print()">&#128438; Print</button></div>

  <table class="rpt-table" id="myTable">
    <thead>
      <tr>
        <th>#</th><th>Department</th>
        <th class="num">Revenue</th><th class="num">Expense</th>
        <th class="num">Profit / Loss</th><th>Profit Bar</th>
      </tr>
    </thead>
    <tbody>
    @php $maxAbs = max(1, collect($rows)->max(fn($r)=>abs($r->ProfitLoss))); @endphp
    @foreach($rows as $i => $row)
    @php $pct = min(100, round(abs($row->ProfitLoss) / $maxAbs * 100)); @endphp
    <tr>
      <td style="color:#9ca3af;font-size:12px;">{{ $i+1 }}</td>
      <td style="font-weight:600;">{{ $row->Department ?? 'Unassigned' }}</td>
      <td class="num amt-cr">{{ number_format($row->TotalRevenue,2) }}</td>
      <td class="num amt-dr">{{ number_format($row->TotalExpense,2) }}</td>
      <td class="num {{ $row->ProfitLoss >= 0 ? 'amt-pos' : 'amt-neg' }}">{{ number_format($row->ProfitLoss,2) }}</td>
      <td style="min-width:100px;">
        <div class="bar-wrap"><div class="bar-fill" style="width:{{ $pct }}%;background:{{ $row->ProfitLoss >= 0 ? '#16a34a' : '#dc2626' }};"></div></div>
      </td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
      <td colspan="2">Grand Total</td>
      <td class="num">{{ number_format($totalRev,2) }}</td>
      <td class="num">{{ number_format($totalExp,2) }}</td>
      <td class="num">{{ number_format($netTotal,2) }}</td>
      <td></td>
    </tr>
    </tfoot>
  </table>
</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script>
$(function(){ $('#myTable').DataTable({paging:false,dom:'Bfrtip',buttons:[{extend:'excel',text:'&#128196; Excel'},{extend:'csv',text:'CSV'},{extend:'print',text:'&#128438; Print'}]}); });
</script>
</body></html>
