@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Finance Reports')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<style>
    .reports-page { font-family: 'Segoe UI', system-ui, sans-serif; }

    /* Tab bar */
    .tab-bar {
        display: flex;
        gap: 4px;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 4px;
        margin-bottom: 24px;
    }
    .tab-btn {
        flex: 1;
        padding: 10px 16px;
        border: none;
        background: transparent;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
    }
    .tab-btn.active {
        background: #fff;
        color: #1e293b;
        box-shadow: 0 1px 4px rgba(0,0,0,.12);
    }
    .tab-btn:hover:not(.active) { background: rgba(255,255,255,.6); color: #334155; }

    /* Tab panels */
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* Section label */
    .section-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 10px;
        padding-left: 2px;
    }
    .section-label.mt { margin-top: 24px; }

    /* Report button grid */
    .report-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 8px;
        margin-bottom: 20px;
    }

    .report-btn {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
        padding: 12px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
        transition: all .18s;
        text-align: left;
        color: #1e293b;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        width: 100%;
    }
    .report-btn:hover {
        border-color: #94a3b8;
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
        transform: translateY(-1px);
        text-decoration: none;
        color: #1e293b;
    }
    .report-btn .icon {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        margin-bottom: 4px;
    }
    .report-btn .icon.green  { background:#dcfce7; color:#16a34a; }
    .report-btn .icon.blue   { background:#dbeafe; color:#2563eb; }
    .report-btn .icon.amber  { background:#fef3c7; color:#d97706; }
    .report-btn .icon.red    { background:#fee2e2; color:#dc2626; }
    .report-btn .icon.purple { background:#f3e8ff; color:#9333ea; }
    .report-btn .icon.slate  { background:#f1f5f9; color:#475569; }
    .report-btn .icon.teal   { background:#ccfbf1; color:#0d9488; }
    .report-btn .icon.orange { background:#ffedd5; color:#ea580c; }

    /* Variant card (grouped buttons) */
    .variant-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        padding: 14px 16px;
        margin-bottom: 16px;
    }
    .variant-card .card-title {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 10px;
    }
    .variant-btns { display: flex; flex-wrap: wrap; gap: 8px; }
    .variant-btn {
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .variant-btn:hover { opacity: .85; transform: translateY(-1px); text-decoration: none; }
    .variant-btn.red    { background:#dc2626; color:#fff; }
    .variant-btn.green  { background:#16a34a; color:#fff; }
    .variant-btn.blue   { background:#2563eb; color:#fff; }
    .variant-btn.slate  { background:#64748b; color:#fff; }
    .variant-btn.amber  { background:#d97706; color:#fff; }
    .variant-btn.teal   { background:#0d9488; color:#fff; }
    .variant-btn.orange { background:#ea580c; color:#fff; }
    .variant-btn.purple { background:#9333ea; color:#fff; }

    /* Invoice type badge grid */
    .type-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 7px;
        margin-bottom: 16px;
    }
    .type-btn {
        padding: 9px 10px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        background: #dc2626;
        color: #fff;
        transition: opacity .15s, transform .1s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        letter-spacing: .02em;
        width: 100%;
    }
    .type-btn:hover { opacity: .88; transform: translateY(-1px); }
    .type-btn.all   { background: #1e293b; }
    .type-btn.green { background: #16a34a; }
    .type-btn.amber { background: #d97706; }

    /* Date bar */
    .date-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 16px;
        margin-bottom: 24px;
        width: fit-content;
    }
    .date-bar label { font-size: 13px; font-weight: 600; color: #64748b; white-space: nowrap; }
    .date-bar input {
        border: none;
        outline: none;
        font-size: 13px;
        color: #1e293b;
        background: transparent;
        min-width: 220px;
        cursor: pointer;
    }
    .date-bar i { color: #94a3b8; }
</style>
@endpush

@section('content')
@include('partials.company-header')
<div class="reports-page bg-white rounded shadow-sm p-6">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-chart-bar text-red-500"></i> Finance / Service Reports
        </h2>
    </div>

    {{-- Date Range --}}
    <div class="date-bar">
        <i class="fas fa-calendar-alt"></i>
        <label>Date Range</label>
        <input type="text" id="fin_daterange"
               value="{{ date('m/d/Y') }} - {{ date('m/d/Y') }}">
    </div>

    {{-- Tab bar --}}
    <div class="tab-bar">
        <button class="tab-btn active" onclick="switchTab('service', this)">
            <i class="fas fa-file-invoice"></i> Service Invoices
        </button>
        <button class="tab-btn" onclick="switchTab('labor', this)">
            <i class="fas fa-tools"></i> Labor
        </button>
        <button class="tab-btn" onclick="switchTab('tax', this)">
            <i class="fas fa-receipt"></i> Tax Reports
        </button>
        <button class="tab-btn" onclick="switchTab('parts', this)">
            <i class="fas fa-cogs"></i> Parts (Finance)
        </button>
        <button class="tab-btn" onclick="switchTab('exports', this)">
            <i class="fas fa-file-export"></i> Exports
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 1 — SERVICE INVOICES
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-service" class="tab-panel active">

        <div class="section-label">Invoice Type Reports</div>

        <div class="type-grid">
            {{-- All Types --}}
            <form method="POST" action="{{ route('cashier.all-report') }}" target="_blank">
                @csrf
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="type-btn all">
                    <i class="fas fa-layer-group"></i> All Types
                </button>
            </form>

            {{-- Individual types --}}
            @foreach(['CM','DM','DMC','COMP','GW','JND','FFS','PDS','WC','CBJ','CNI'] as $type)
            <form method="POST" action="{{ route('cashier.report-download') }}" target="_blank">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="type-btn">
                    <i class="fas fa-file-alt"></i> {{ $type }}
                </button>
            </form>
            @endforeach
        </div>

        <div class="section-label mt">Summaries</div>
        <div class="report-grid">
            <form method="POST" action="{{ route('cashier.business-summary') }}" target="_blank">
                @csrf
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn">
                    <span class="icon green"><i class="fas fa-chart-pie"></i></span>
                    Business Summary
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 2 — LABOR
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-labor" class="tab-panel">

        <div class="section-label">Labor Detail Reports</div>
        <div class="variant-card">
            <div class="card-title"><i class="fas fa-tools me-1"></i> Labor by Department</div>
            <div class="variant-btns">
                <form method="GET" action="{{ route('sm.reports.labor-detail') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="type" value="Mechanical">
                    <button type="submit" class="variant-btn blue">
                        <i class="fas fa-wrench"></i> Mechanical
                    </button>
                </form>

                <form method="GET" action="{{ route('sm.reports.labor-detail') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="type" value="Body / Paint">
                    <button type="submit" class="variant-btn orange">
                        <i class="fas fa-paint-roller"></i> Body &amp; Paint
                    </button>
                </form>

                <form method="GET" action="{{ route('sm.reports.labor-detail') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="type" value="Warranty">
                    <button type="submit" class="variant-btn slate">
                        <i class="fas fa-shield-alt"></i> Warranty
                    </button>
                </form>
            </div>
        </div>

        <div class="section-label">Labor Changes</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('accountant.reopen-jc') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn">
                    <span class="icon red"><i class="fas fa-undo-alt"></i></span>
                    ReOpen Jobcards
                </button>
            </form>

            <form method="GET" action="{{ route('accountant.labor-auto') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <input type="hidden" name="change_type" value="Increase">
                <button type="submit" class="report-btn">
                    <span class="icon green"><i class="fas fa-arrow-up"></i></span>
                    Labor Price (Increase)
                </button>
            </form>

            <form method="GET" action="{{ route('accountant.labor-auto') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <input type="hidden" name="change_type" value="Decrease">
                <button type="submit" class="report-btn">
                    <span class="icon red"><i class="fas fa-arrow-down"></i></span>
                    Labor Price (Decrease)
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 3 — TAX REPORTS
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-tax" class="tab-panel">

        <div class="section-label">Tax Invoice Reports</div>
        <div class="variant-card">
            <div class="card-title"><i class="fas fa-receipt me-1"></i> Tax Type</div>
            <div class="variant-btns">
                <form method="POST" action="{{ route('cashier.tax-invoice') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="tax_type" value="labor">
                    <button type="submit" class="variant-btn green">
                        <i class="fas fa-tools"></i> Labor Tax (KPRA)
                    </button>
                </form>

                <form method="POST" action="{{ route('cashier.tax-invoice') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="tax_type" value="parts">
                    <button type="submit" class="variant-btn green">
                        <i class="fas fa-cogs"></i> Parts Tax (FBR)
                    </button>
                </form>

                <form method="POST" action="{{ route('cashier.tax-invoice') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="tax_type" value="both">
                    <button type="submit" class="variant-btn teal">
                        <i class="fas fa-file-invoice-dollar"></i> Parts &amp; Labor Tax
                    </button>
                </form>
            </div>
        </div>

        <div class="section-label">Sales Tax Summary</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('sm.reports.sales-tax') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn">
                    <span class="icon blue"><i class="fas fa-percentage"></i></span>
                    Sales Tax Report
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 4 — PARTS (FINANCE)
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-parts" class="tab-panel">

        <div class="section-label">Parts Finance Reports</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.kpi-sale') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn">
                    <span class="icon teal"><i class="fas fa-file-invoice-dollar"></i></span>
                    Parts Purchase [Greater]
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.kpi-purch') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn">
                    <span class="icon teal"><i class="fas fa-file-invoice"></i></span>
                    Parts Purchase [Less]
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.kpi-profit') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn">
                    <span class="icon green"><i class="fas fa-coins"></i></span>
                    KPRA Excel Report
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.cate-wise') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn">
                    <span class="icon green"><i class="fas fa-check-double"></i></span>
                    Service &amp; Parts Diff
                </button>
            </form>

            <form method="POST" action="{{ route('accountant.cancel-part') }}" target="_blank">
                @csrf
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn">
                    <span class="icon amber"><i class="fas fa-ban"></i></span>
                    Workshop Parts Cancel
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 5 — EXPORTS
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-exports" class="tab-panel">

        <div class="section-label">Excel / Data Exports</div>
        <div class="report-grid">
            <form method="POST" action="{{ route('cashier.msi-report') }}" target="_blank">
                @csrf
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn">
                    <span class="icon blue"><i class="fas fa-file-excel"></i></span>
                    MSI Report
                    <small class="text-gray-400 text-xs font-normal">.xml export</small>
                </button>
            </form>

            <form method="POST" action="{{ route('cashier.pm-export') }}" target="_blank">
                @csrf
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn">
                    <span class="icon amber"><i class="fas fa-file-excel"></i></span>
                    PM Export
                    <small class="text-gray-400 text-xs font-normal">Excel download</small>
                </button>
            </form>

            <form method="POST" action="{{ route('cashier.pm-export') }}" target="_blank">
                @csrf
                <input type="hidden" name="daterange" class="dr-val">
                <input type="hidden" name="PMGRBP" value="1">
                <button type="submit" class="report-btn">
                    <span class="icon purple"><i class="fas fa-file-excel"></i></span>
                    PMGR+BP Export
                    <small class="text-gray-400 text-xs font-normal">Excel download</small>
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    // Init daterangepicker
    $('#fin_daterange').daterangepicker({ locale: { format: 'MM/DD/YYYY' } });

    // Tab switching
    function switchTab(name, btn) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + name).classList.add('active');
        btn.classList.add('active');
    }

    // Inject date into every hidden input before form submits
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function() {
            var dr = document.getElementById('fin_daterange').value;
            form.querySelectorAll('.dr-val').forEach(function(inp) {
                inp.value = dr;
            });
        });
    });
</script>
@endpush
