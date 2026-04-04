<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>P&L — {{ $deptName }}</title>
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; color: #222; }
  .page-wrap { max-width: 960px; margin: 0 auto; padding: 24px 20px 40px; }

  /* Company Header */
  .company-header { background: #fff; border-radius: 10px; padding: 20px 24px 14px; text-align: center;
    border-bottom: 3px solid #b91c1c; margin-bottom: 20px; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
  .company-header img { height: 54px; margin: 0 auto 6px; display: block; }
  .company-name { font-size: 20px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #111; }
  .company-sub  { font-size: 11px; color: #555; margin-top: 3px; }

  /* Report Title */
  .report-title { background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
    color: #fff; border-radius: 10px; padding: 16px 24px; margin-bottom: 20px;
    display: flex; justify-content: space-between; align-items: center; }
  .report-title h2 { font-size: 16px; font-weight: 600; }
  .report-title .period { font-size: 12px; opacity: .85; margin-top: 3px; }
  .report-title .badge { background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.4);
    border-radius: 6px; padding: 4px 12px; font-size: 11px; font-weight: 600; }

  /* Summary Cards */
  .summary-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 24px; }
  .card { background: #fff; border-radius: 10px; padding: 16px 20px; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
  .card-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: #888; margin-bottom: 4px; }
  .card-value { font-size: 22px; font-weight: 700; font-family: 'Courier New', monospace; }
  .c-income  { border-top: 4px solid #16a34a; } .c-income  .card-value { color: #16a34a; }
  .c-expense { border-top: 4px solid #dc2626; } .c-expense .card-value { color: #dc2626; }
  .c-net     { border-top: 4px solid #2563eb; } .c-net     .card-value { color: #2563eb; }
  .c-net.loss { border-top-color: #dc2626; }   .c-net.loss .card-value { color: #dc2626; }

  /* Section Headers */
  .section-header { display: flex; align-items: center; gap: 10px; margin: 24px 0 10px; }
  .section-header .pill { display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px; }
  .pill-income  { background: #dcfce7; color: #166534; }
  .pill-expense { background: #fee2e2; color: #991b1b; }
  .section-header .line { flex: 1; height: 1px; background: #e5e7eb; }

  /* Tables */
  .rpt-table { width: 100%; border-collapse: collapse; background: #fff;
    border-radius: 10px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); margin-bottom: 14px; }
  .rpt-table thead th { background: #374151; color: #fff; padding: 10px 14px;
    text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
  .rpt-table thead th.num { text-align: right; }
  .rpt-table tbody tr:hover { background: #f9fafb; }
  .rpt-table tbody td { padding: 9px 14px; font-size: 13px; border-bottom: 1px solid #f1f3f5; }
  .rpt-table tbody td.num { text-align: right; font-family: 'Courier New', monospace; font-size: 12px; }
  .rpt-table tfoot td { padding: 10px 14px; font-size: 13px; font-weight: 700; background: #f8fafc; border-top: 2px solid #e5e7eb; }
  .rpt-table tfoot td.num { text-align: right; font-family: 'Courier New', monospace; }

  .gl-group { background: #eff6ff; font-weight: 600; color: #1e40af; }
  .gl-group td { font-size: 12px; padding: 7px 14px; }
  .gsl-row td:first-child { padding-left: 30px; color: #555; }
  .amt-income  { color: #16a34a; }
  .amt-expense { color: #dc2626; }

  /* Print / export toolbar */
  .toolbar { display: flex; justify-content: flex-end; gap-8px; margin-bottom: 14px; gap: 8px; }
  .btn-print { padding: 6px 16px; background: #374151; color: #fff; border: none; border-radius: 6px;
    font-size: 12px; cursor: pointer; font-weight: 600; }
  .btn-print:hover { background: #1f2937; }
  .dt-buttons { margin-left: auto; }

  @media print {
    body { background: #fff; }
    .toolbar, .dataTables_filter, .dataTables_length, .dt-buttons { display: none !important; }
    .page-wrap { padding: 0; }
  }
</style>
</head>
<body>
<div class="page-wrap">

  {{-- Company Header --}}
  <div class="company-header">
    @php $logoPath = public_path(config('company.logo_path','images/logo.png')); @endphp
    @if(file_exists($logoPath))
    <img src="{{ asset(config('company.logo_path','images/logo.png')) }}" alt="{{ config('company.name') }}" onerror="this.style.display='none'">
    @endif
    <div class="company-name">{{ config('company.name','Your Company') }}</div>
    <div class="company-sub">{{ config('company.location','') }} &nbsp;|&nbsp; {{ config('company.phone','') }}</div>
  </div>

  {{-- Report Title --}}
  <div class="report-title">
    <div>
      <h2>Profit &amp; Loss Report — Department</h2>
      <div class="period">Period: {{ $from }} &nbsp;to&nbsp; {{ $to }}</div>
    </div>
    <div class="badge">{{ $deptName }}</div>
  </div>

  {{-- Summary Cards --}}
  <div class="summary-grid">
    <div class="card c-income">
      <div class="card-label">Total Income</div>
      <div class="card-value">{{ number_format($totalIncome,2) }}</div>
    </div>
    <div class="card c-expense">
      <div class="card-label">Total Expenses</div>
      <div class="card-value">{{ number_format($totalExpense,2) }}</div>
    </div>
    <div class="card c-net {{ $net < 0 ? 'loss' : '' }}">
      <div class="card-label">Net {{ $net >= 0 ? 'Profit' : 'Loss' }}</div>
      <div class="card-value">{{ number_format(abs($net),2) }}</div>
    </div>
  </div>

  {{-- ── INCOME SECTION ──────────────────────────────────────────────────────── --}}
  @php
    $incomeGls  = collect($glRows)->where('main_account','Revenues');
    $expenseGls = collect($glRows)->where('main_account','Expenses');
    $incomeGsls  = collect($gslRows)->where('main_account','Revenues');
    $expenseGsls = collect($gslRows)->where('main_account','Expenses');
  @endphp

  @if($incomeGls->count() > 0)
  <div class="section-header">
    <span class="pill pill-income"><span>&#9650;</span> Income / Revenue</span>
    <div class="line"></div>
    <span style="font-size:14px;font-weight:700;color:#16a34a;">{{ number_format($totalIncome,2) }}</span>
  </div>

  <div class="toolbar">
    <button class="btn-print" onclick="window.print()"><i>&#128438;</i> Print</button>
  </div>

  <table class="rpt-table" id="tblIncome">
    <thead>
      <tr>
        <th>GL Group</th>
        <th>GSL Code</th>
        <th>Account Name</th>
        <th class="num">Debit</th>
        <th class="num">Credit</th>
        <th class="num">Net Income</th>
      </tr>
    </thead>
    <tbody>
    @foreach($incomeGls as $glRow)
      @php $glGsls = $incomeGsls->where('GL_name', $glRow->GL_name); @endphp
      <tr class="gl-group">
        <td colspan="2"><strong>{{ $glRow->GL_name }}</strong></td>
        <td></td>
        <td class="num">{{ number_format($glRow->TotalDebit,2) }}</td>
        <td class="num">{{ number_format($glRow->TotalCredit,2) }}</td>
        <td class="num amt-income"><strong>{{ number_format($glRow->NetIncome,2) }}</strong></td>
      </tr>
      @foreach($glGsls as $gsl)
      <tr class="gsl-row">
        <td></td>
        <td style="font-family:monospace;font-size:12px;">{{ $gsl->GSL_code }}</td>
        <td>{{ $gsl->GSL_name }}</td>
        <td class="num">{{ number_format($gsl->TotalDebit,2) }}</td>
        <td class="num">{{ number_format($gsl->TotalCredit,2) }}</td>
        <td class="num amt-income">{{ number_format($gsl->NetIncome,2) }}</td>
      </tr>
      @endforeach
    @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5"><strong>Total Income</strong></td>
        <td class="num amt-income"><strong>{{ number_format($totalIncome,2) }}</strong></td>
      </tr>
    </tfoot>
  </table>
  @else
  <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;font-size:13px;color:#166534;margin-bottom:14px;">
    No income entries found for this department in the selected period.
  </div>
  @endif

  {{-- ── EXPENSE SECTION ─────────────────────────────────────────────────────── --}}
  @if($expenseGls->count() > 0)
  <div class="section-header">
    <span class="pill pill-expense"><span>&#9660;</span> Expenses</span>
    <div class="line"></div>
    <span style="font-size:14px;font-weight:700;color:#dc2626;">{{ number_format($totalExpense,2) }}</span>
  </div>

  <table class="rpt-table" id="tblExpense">
    <thead>
      <tr>
        <th>GL Group</th>
        <th>GSL Code</th>
        <th>Account Name</th>
        <th class="num">Debit</th>
        <th class="num">Credit</th>
        <th class="num">Net Expense</th>
      </tr>
    </thead>
    <tbody>
    @foreach($expenseGls as $glRow)
      @php $glGsls = $expenseGsls->where('GL_name', $glRow->GL_name); @endphp
      <tr class="gl-group">
        <td colspan="2"><strong>{{ $glRow->GL_name }}</strong></td>
        <td></td>
        <td class="num">{{ number_format($glRow->TotalDebit,2) }}</td>
        <td class="num">{{ number_format($glRow->TotalCredit,2) }}</td>
        <td class="num amt-expense"><strong>{{ number_format($glRow->NetExpense,2) }}</strong></td>
      </tr>
      @foreach($glGsls as $gsl)
      <tr class="gsl-row">
        <td></td>
        <td style="font-family:monospace;font-size:12px;">{{ $gsl->GSL_code }}</td>
        <td>{{ $gsl->GSL_name }}</td>
        <td class="num">{{ number_format($gsl->TotalDebit,2) }}</td>
        <td class="num">{{ number_format($gsl->TotalCredit,2) }}</td>
        <td class="num amt-expense">{{ number_format($gsl->NetExpense,2) }}</td>
      </tr>
      @endforeach
    @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5"><strong>Total Expenses</strong></td>
        <td class="num amt-expense"><strong>{{ number_format($totalExpense,2) }}</strong></td>
      </tr>
    </tfoot>
  </table>
  @else
  <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;font-size:13px;color:#991b1b;margin-bottom:14px;">
    No expense entries found for this department in the selected period.
  </div>
  @endif

  {{-- ── NET P/L FOOTER ───────────────────────────────────────────────────────── --}}
  <div style="background:{{ $net >= 0 ? '#f0fdf4' : '#fef2f2' }};border:2px solid {{ $net >= 0 ? '#16a34a' : '#dc2626' }};border-radius:10px;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;margin-top:10px;">
    <div>
      <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#555;">Net {{ $net >= 0 ? 'Profit' : 'Loss' }} — {{ $deptName }}</div>
      <div style="font-size:11px;color:#777;margin-top:2px;">Period: {{ $from }} to {{ $to }}</div>
    </div>
    <div style="font-size:26px;font-weight:700;color:{{ $net >= 0 ? '#16a34a' : '#dc2626' }};font-family:'Courier New',monospace;">
      {{ $net >= 0 ? '' : '(' }}{{ number_format(abs($net),2) }}{{ $net >= 0 ? '' : ')' }}
    </div>
  </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
</body>
</html>
