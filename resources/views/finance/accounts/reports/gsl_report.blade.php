<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $gslName }} — Ledger</title>
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
.report-title { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color: #fff; }
</style>
</head>
<body>
<div class="page-wrap">

  <div class="company-header">
    @php $logoPath = public_path(config('company.logo_path','images/logo.png')); @endphp
    @if(file_exists($logoPath))
    <img src="{{ asset(config('company.logo_path','images/logo.png')) }}" alt="{{ config('company.name') }}" onerror="this.style.display='none'">
    @endif
    <div class="company-name">{{ config('company.name','Your Company') }}</div>
    <div class="company-sub">{{ config('company.location','') }} &nbsp;|&nbsp; {{ config('company.phone','') }}</div>
  </div>

  <div class="report-title">
    <div>
      <h2>Analytical Account Ledger</h2>
      <div class="period">Period: {{ $from }} &nbsp;–&nbsp; {{ $to }}</div>
    </div>
    <div>
      <div class="badge">Code: {{ $gslCode }}</div>
      <div style="font-size:13px;margin-top:5px;opacity:.9;font-weight:600;">{{ $gslName }}</div>
    </div>
  </div>

  {{-- Summary Strip --}}
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;">
    <div style="background:#fff;border-radius:8px;padding:12px 16px;box-shadow:0 1px 4px rgba(0,0,0,.07);border-top:3px solid #94a3b8;">
      <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#888;">Opening Balance</div>
      <div style="font-size:17px;font-weight:700;font-family:monospace;color:#374151;">{{ number_format($opening,2) }}</div>
    </div>
    <div style="background:#fff;border-radius:8px;padding:12px 16px;box-shadow:0 1px 4px rgba(0,0,0,.07);border-top:3px solid #dc2626;">
      <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#888;">Total Debit</div>
      <div style="font-size:17px;font-weight:700;font-family:monospace;color:#dc2626;">{{ number_format($totalDr,2) }}</div>
    </div>
    <div style="background:#fff;border-radius:8px;padding:12px 16px;box-shadow:0 1px 4px rgba(0,0,0,.07);border-top:3px solid #16a34a;">
      <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#888;">Total Credit</div>
      <div style="font-size:17px;font-weight:700;font-family:monospace;color:#16a34a;">{{ number_format($totalCr,2) }}</div>
    </div>
    <div style="background:#fff;border-radius:8px;padding:12px 16px;box-shadow:0 1px 4px rgba(0,0,0,.07);border-top:3px solid #2563eb;">
      <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#888;">Closing Balance</div>
      <div style="font-size:17px;font-weight:700;font-family:monospace;color:#2563eb;">{{ number_format($opening + $totalDr - $totalCr,2) }}</div>
    </div>
  </div>

  <div class="toolbar">
    <button class="btn-act" onclick="window.print()">&#128438; Print</button>
  </div>

  <table class="rpt-table" id="myTable">
    <thead>
      <tr>
        <th>Date</th>
        <th>Ref No</th>
        <th>Voucher</th>
        <th>Description</th>
        <th class="num">Debit</th>
        <th class="num">Credit</th>
        <th class="num">Balance</th>
      </tr>
    </thead>
    <tbody>
    @php $balance = $opening; @endphp
    @if($opening != 0)
    <tr class="opening-row">
      <td colspan="4"><strong>Opening Balance</strong></td>
      <td class="num"></td>
      <td class="num"></td>
      <td class="num"><strong>{{ number_format($opening,2) }}</strong></td>
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
      <td style="font-family:monospace;font-size:12px;">{{ $row->RefNo }}</td>
      <td><span style="background:#e5e7eb;border-radius:4px;padding:2px 7px;font-size:11px;font-weight:600;">{{ $row->vchr_type }}</span></td>
      <td>{{ $row->Description }}</td>
      <td class="num amt-dr">{{ $dr ? number_format($dr,2) : '' }}</td>
      <td class="num amt-cr">{{ $cr ? number_format($cr,2) : '' }}</td>
      <td class="num {{ $balance >= 0 ? 'amt-pos' : 'amt-neg' }}">{{ number_format($balance,2) }}</td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4">Total</td>
        <td class="num">{{ number_format($totalDr,2) }}</td>
        <td class="num">{{ number_format($totalCr,2) }}</td>
        <td class="num">{{ number_format($opening + $totalDr - $totalCr,2) }}</td>
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
$(function(){
  $('#myTable').DataTable({
    paging: false, ordering: false,
    dom: 'Bfrtip',
    buttons: [
      { extend:'excel', text:'&#128196; Excel' },
      { extend:'csv',   text:'CSV' },
      { extend:'print', text:'&#128438; Print' }
    ]
  });
});
</script>
</body></html>
