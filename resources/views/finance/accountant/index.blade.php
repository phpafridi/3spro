@extends('layouts.master')
@include('finance.accountant.sidebar')

@section('title', 'Accountant - Service Reports')

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .reports-page { font-family: 'Segoe UI', system-ui, sans-serif; }

    .section-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 10px;
        padding-left: 2px;
    }

    /* Report button grid */
    .report-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 24px;
    }

    .report-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all .18s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .report-btn:hover { opacity: .85; transform: translateY(-1px); text-decoration: none; }

    .btn-red    { background:#dc2626; color:#fff; }
    .btn-green  { background:#16a34a; color:#fff; }
    .btn-dark   { background:#1e293b; color:#fff; }
    .btn-blue   { background:#2563eb; color:#fff; }
    .btn-slate  { background:#475569; color:#fff; }
    .btn-amber  { background:#d97706; color:#fff; }
    .btn-teal   { background:#0d9488; color:#fff; }

    /* Date bar */
    .date-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 16px;
        margin-bottom: 28px;
        width: fit-content;
    }
    .date-bar label { font-size: 13px; font-weight: 600; color: #64748b; white-space: nowrap; }
    .date-bar input {
        border: none; outline: none;
        font-size: 13px; color: #1e293b;
        background: transparent; min-width: 220px; cursor: pointer;
    }
    .date-bar i { color: #94a3b8; }

    hr.section-divider { border: none; border-top: 1px solid #f1f5f9; margin: 4px 0 20px; }
</style>
@endpush

@section('content')
<div class="reports-page bg-white rounded shadow-sm p-6">

    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fas fa-chart-bar text-red-500"></i> Service Reports &amp; Scrolls
    </h2>

    {{-- Date Range --}}
    <div class="date-bar">
        <i class="fas fa-calendar-alt"></i>
        <label>Date Range</label>
        <input type="text" id="reservation" value="{{ date('m/d/Y') }} - {{ date('m/d/Y') }}">
    </div>

    {{-- ── BY TYPE ──────────────────────────────────────────── --}}
    <div class="section-label">By Type</div>
    <div class="report-grid">
        <form method="POST" action="{{ route('cashier.all-report') }}" target="_blank">
            @csrf <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-red">
                <i class="fas fa-layer-group"></i> All
            </button>
        </form>

        @foreach(['CM','DM','DMC','COMP','GW','JND','PDS','FFS','WC','CNI'] as $type)
        <form method="POST" action="{{ route('cashier.report-download') }}" target="_blank">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-green">{{ $type }}</button>
        </form>
        @endforeach
    </div>
    <hr class="section-divider">

    {{-- ── SUMMARY ──────────────────────────────────────────── --}}
    <div class="section-label">Summary</div>
    <div class="report-grid">
        <form method="POST" action="{{ route('cashier.business-summary') }}" target="_blank">
            @csrf <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-dark">
                <i class="fas fa-chart-pie"></i> Business Summary
            </button>
        </form>

        <form method="GET" action="{{ route('sm.reports.sales-tax') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-dark">
                <i class="fas fa-receipt"></i> Sales Tax Invoices
            </button>
        </form>

        <form method="GET" action="{{ route('sm.reports.labor-detail') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-dark">
                <i class="fas fa-tools"></i> Labor Business Report
            </button>
        </form>
    </div>
    <hr class="section-divider">

    {{-- ── PERFORMANCE ───────────────────────────────────────── --}}
    <div class="section-label">Performance</div>
    <div class="report-grid">
        <form method="GET" action="{{ route('sm.reports.sa') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-blue">
                <i class="fas fa-user-tie"></i> SA Performance
            </button>
        </form>

        <form method="GET" action="{{ route('sm.reports.team') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-blue">
                <i class="fas fa-users"></i> Teams Performance
            </button>
        </form>

        <form method="GET" action="{{ route('sm.reports.sa-parts') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-blue">
                <i class="fas fa-cogs"></i> Teams Parts
            </button>
        </form>

        <form method="GET" action="{{ route('sm.reports.ratings') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-blue">
                <i class="fas fa-star"></i> Customer Ratings
            </button>
        </form>

        <form method="GET" action="{{ route('sm.reports.nvs') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-blue">
                <i class="fas fa-user-plus"></i> New vs Old Customers
            </button>
        </form>
    </div>
    <hr class="section-divider">

    {{-- ── BY DEPARTMENT ─────────────────────────────────────── --}}
    <div class="section-label">By Department</div>
    <div class="report-grid">
        <form method="GET" action="{{ route('sm.reports.dept') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <input type="hidden" name="dept" value="Mechanical">
            <button type="submit" class="report-btn btn-green">
                <i class="fas fa-wrench"></i> Mechanical
            </button>
        </form>

        <form method="GET" action="{{ route('sm.reports.dept') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <input type="hidden" name="dept" value="Warranty">
            <button type="submit" class="report-btn btn-green">
                <i class="fas fa-shield-alt"></i> Warranty
            </button>
        </form>

        <form method="GET" action="{{ route('sm.reports.dept') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <input type="hidden" name="dept" value="Body / Paint">
            <button type="submit" class="report-btn btn-green">
                <i class="fas fa-paint-roller"></i> Body / Paint
            </button>
        </form>

        <form method="GET" action="{{ route('sm.reports.top-labor') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-green">
                <i class="fas fa-trophy"></i> Top 50 Services
            </button>
        </form>
    </div>
    <hr class="section-divider">

    {{-- ── EXCEL / EXPORTS ───────────────────────────────────── --}}
    <div class="section-label">Excel Genesis</div>
    <div class="report-grid">
        <form method="POST" action="{{ route('cashier.pm-export') }}" target="_blank">
            @csrf <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-slate">
                <i class="fas fa-file-excel"></i> PM Genesis
            </button>
        </form>

        <form method="POST" action="{{ route('cashier.pm-export') }}" target="_blank">
            @csrf <input type="hidden" name="daterange" class="dr-val">
            <input type="hidden" name="PMGRBP" value="1">
            <button type="submit" class="report-btn btn-slate">
                <i class="fas fa-file-excel"></i> PM GR BP Genesis
            </button>
        </form>

        <form method="POST" action="{{ route('cashier.msi-report') }}" target="_blank">
            @csrf <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-slate">
                <i class="fas fa-file-export"></i> MSI Excel Report
            </button>
        </form>

        <form method="GET" action="{{ route('sm.reports.ffs-rate') }}" target="_blank">
            <input type="hidden" name="daterange" class="dr-val">
            <button type="submit" class="report-btn btn-slate">
                <i class="fas fa-file-excel"></i> FIR Genesis Excel
            </button>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
$(document).ready(function() {
    $('#reservation').daterangepicker({
        opens: 'left',
        startDate: moment(),
        endDate: moment(),
        locale: { format: 'MM/DD/YYYY' }
    });
});

// Inject date into every .dr-val before submit
document.querySelectorAll('form').forEach(function(form) {
    form.addEventListener('submit', function() {
        var dr = document.getElementById('reservation').value;
        form.querySelectorAll('.dr-val').forEach(function(inp) {
            inp.value = dr;
        });
    });
});
</script>
@endpush
