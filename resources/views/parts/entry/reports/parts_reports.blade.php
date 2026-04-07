@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Parts Reports')

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

    /* Tab content panels */
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* Section header */
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
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 8px;
        margin-bottom: 24px;
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
    }
    .report-btn:hover {
        border-color: #94a3b8;
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
        transform: translateY(-1px);
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

    /* Sub-buttons inside a report-btn card (for Credit/Cash variants) */
    .variant-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        padding: 12px 14px;
        transition: border-color .18s;
    }
    .variant-card:hover { border-color: #94a3b8; }
    .variant-card .card-title {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 8px;
    }
    .variant-btns { display: flex; flex-wrap: wrap; gap: 6px; }
    .variant-btn {
        padding: 5px 11px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: opacity .15s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .variant-btn:hover { opacity: .85; }
    .variant-btn.blue   { background:#2563eb; color:#fff; }
    .variant-btn.slate  { background:#64748b; color:#fff; }
    .variant-btn.green  { background:#16a34a; color:#fff; }
    .variant-btn.amber  { background:#d97706; color:#fff; }
    .variant-btn.red    { background:#dc2626; color:#fff; }
    .variant-btn.teal   { background:#0d9488; color:#fff; }
    .variant-btn.purple { background:#9333ea; color:#fff; }

    /* Date input */
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
            <i class="fas fa-wpforms text-red-500"></i> Parts Department Reports
        </h2>
    </div>

    {{-- Date Range --}}
    <div class="date-bar">
        <i class="fas fa-calendar-alt"></i>
        <label>Date Range</label>
        <input type="text" id="parts_daterange"
               value="{{ date('m/d/Y') }} - {{ date('m/d/Y') }}">
    </div>

    {{-- Tab bar --}}
    <div class="tab-bar">
        <button class="tab-btn active" onclick="switchTab('business', this)">
            <i class="fas fa-chart-bar"></i> Business Summary
        </button>
        <button class="tab-btn" onclick="switchTab('purchase', this)">
            <i class="fas fa-shopping-cart"></i> Purchase
        </button>
        <button class="tab-btn" onclick="switchTab('sales', this)">
            <i class="fas fa-tags"></i> Sales
        </button>
        <button class="tab-btn" onclick="switchTab('vendors', this)">
            <i class="fas fa-truck"></i> Vendors
        </button>
        <button class="tab-btn" onclick="switchTab('workshop', this)">
            <i class="fas fa-wrench"></i> Workshop
        </button>
        <button class="tab-btn" onclick="switchTab('inventory', this)">
            <i class="fas fa-boxes"></i> Inventory
        </button>
        <button class="tab-btn" onclick="switchTab('kpi', this)">
            <i class="fas fa-tachometer-alt"></i> KPI
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 1 — BUSINESS SUMMARY
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-business" class="tab-panel active">

        <div class="section-label">Summaries</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.cate-wise') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon green"><i class="fas fa-layer-group"></i></span>
                    Category-Wise
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.imc-local') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon blue"><i class="fas fa-exchange-alt"></i></span>
                    OEM vs Local
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.part-wise') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon green"><i class="fas fa-list"></i></span>
                    Part Type
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.revenue') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon green"><i class="fas fa-chart-line"></i></span>
                    Sale Revenue
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.daily-sale') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon green"><i class="fas fa-calendar-day"></i></span>
                    Daily Stock Activity
                </button>
            </form>
        </div>

        <div class="section-label">Closing</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.parts-closing') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon slate"><i class="fas fa-lock"></i></span>
                    Parts Closing
                </button>
            </form>
        </div>

        <div class="section-label">Finance (Parts)</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.kpi-sale') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon teal"><i class="fas fa-file-invoice"></i></span>
                    Parts Purchase [Greater]
                </button>
            </form>
            <form method="GET" action="{{ route('parts.reports.kpi-purch') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon teal"><i class="fas fa-file-invoice-dollar"></i></span>
                    Parts Purchase [Less]
                </button>
            </form>
        </div>

        <div class="section-label">Moving Stock</div>
        <div class="variant-card" style="max-width:520px">
            <div class="card-title"><i class="fas fa-tachometer-alt me-1"></i> Stock Movement Categories</div>
            <div class="variant-btns">
                @foreach([
                    ['Very Fast', 'green',  'very_fast'],
                    ['Fast',      'blue',   'fast'],
                    ['Medium',    'amber',  'medium'],
                    ['Slow',      'slate',  'slow'],
                    ['Very Slow', 'purple', 'very_slow'],
                ] as [$label, $color, $speed])
                <form method="GET" action="{{ route('parts.reports.moving-stock') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="speed" value="{{ $speed }}">
                    <button type="submit" class="variant-btn {{ $color }}">{{ $label }}</button>
                </form>
                @endforeach

                <form method="GET" action="{{ route('parts.reports.dead-stock') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <button type="submit" class="variant-btn red">
                        <i class="fas fa-skull-crossbones"></i> Dead Stock
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 2 — PURCHASE
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-purchase" class="tab-panel">

        <div class="section-label">Stock Purchase</div>
        <div class="variant-card mb-6" style="max-width:520px">
            <div class="card-title"><i class="fas fa-shopping-cart me-1"></i> Purchase Reports</div>
            <div class="variant-btns">
                <form method="GET" action="{{ route('parts.reports.purchase') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <button type="submit" class="variant-btn blue">All Purchases</button>
                </form>

                <form method="GET" action="{{ route('parts.reports.purch-cre-cash') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="report_type" value="Credit">
                    <button type="submit" class="variant-btn slate">Credit</button>
                </form>

                <form method="GET" action="{{ route('parts.reports.purch-cre-cash') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="report_type" value="Cash">
                    <button type="submit" class="variant-btn green">Cash</button>
                </form>

                <form method="GET" action="{{ route('parts.reports.purchase') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="source" value="local">
                    <button type="submit" class="variant-btn teal">Local Purchases</button>
                </form>

                <form method="GET" action="{{ route('parts.reports.purchase') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="source" value="imc">
                    <button type="submit" class="variant-btn amber">OEM Purchases</button>
                </form>

                <form method="GET" action="{{ route('parts.reports.purchase') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="source" value="imc_fin">
                    <button type="submit" class="variant-btn purple">OEM (Fin)</button>
                </form>
            </div>
        </div>

        <div class="section-label">Purchase Returns</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.purchase-return') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon red"><i class="fas fa-undo"></i></span>
                    Purchase Returns
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.purch-profit') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon green"><i class="fas fa-coins"></i></span>
                    Purchase Profit
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 3 — SALES
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-sales" class="tab-panel">

        <div class="section-label">Counter Sales</div>
        <div class="variant-card mb-6" style="max-width:500px">
            <div class="card-title"><i class="fas fa-store me-1"></i> Counter Sale</div>
            <div class="variant-btns">
                <form method="GET" action="{{ route('parts.reports.sale') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="report" value="Cash">
                    <input type="hidden" name="naturetype" value="Counter Sale">
                    <button type="submit" class="variant-btn green">Cash</button>
                </form>

                <form method="GET" action="{{ route('parts.reports.sale') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="report" value="Credit">
                    <input type="hidden" name="naturetype" value="Counter Sale">
                    <button type="submit" class="variant-btn blue">Credit</button>
                </form>

                <form method="GET" action="{{ route('parts.reports.counter-return') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="naturetype" value="Counter Sale">
                    <button type="submit" class="variant-btn red">Returned</button>
                </form>
            </div>
        </div>

        <div class="section-label">Jobber Sales</div>
        <div class="variant-card mb-6" style="max-width:500px">
            <div class="card-title"><i class="fas fa-user-tie me-1"></i> Jobber</div>
            <div class="variant-btns">
                <form method="GET" action="{{ route('parts.reports.sale') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="report" value="Cash">
                    <input type="hidden" name="naturetype" value="Jobber">
                    <button type="submit" class="variant-btn green">Cash</button>
                </form>

                <form method="GET" action="{{ route('parts.reports.sale') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="report" value="Credit">
                    <input type="hidden" name="naturetype" value="Jobber">
                    <button type="submit" class="variant-btn blue">Credit</button>
                </form>

                <form method="GET" action="{{ route('parts.reports.counter-return') }}" target="_blank">
                    <input type="hidden" name="daterange" class="dr-val">
                    <input type="hidden" name="naturetype" value="Jobber">
                    <button type="submit" class="variant-btn red">Returned</button>
                </form>
            </div>
        </div>

        <div class="section-label">Sale History & Others</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.sale-history') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon blue"><i class="fas fa-history"></i></span>
                    Sale History
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.lost-sale') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon red"><i class="fas fa-times-circle"></i></span>
                    Lost Sale
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.kpi-sale') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon teal"><i class="fas fa-chart-bar"></i></span>
                    KPI Sale Report
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 4 — VENDORS
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-vendors" class="tab-panel">

        <div class="section-label">Vendor Business</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.purchase') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon slate"><i class="fas fa-shopping-cart"></i></span>
                    Purchase
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.purchase-return') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon red"><i class="fas fa-undo"></i></span>
                    Purchase Returns
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.sale') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon slate"><i class="fas fa-tags"></i></span>
                    Sale
                </button>
            </form>

            <a href="{{ route('parts.reports.vendor-ledger') }}" target="_blank" class="report-btn">
                <span class="icon blue"><i class="fas fa-book"></i></span>
                Vendor Ledger
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 5 — WORKSHOP
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-workshop" class="tab-panel">

        <div class="section-label">Workshop Business</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.workshop') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <input type="hidden" name="type" value="parts">
                <button type="submit" class="report-btn w-full">
                    <span class="icon slate"><i class="fas fa-cogs"></i></span>
                    Workshop Parts
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.workshop') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <input type="hidden" name="type" value="consumable">
                <button type="submit" class="report-btn w-full">
                    <span class="icon slate"><i class="fas fa-flask"></i></span>
                    Workshop Consumable
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.workshop-return') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon red"><i class="fas fa-undo"></i></span>
                    Parts/Cons Returned
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.workshop-business') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon blue"><i class="fas fa-industry"></i></span>
                    OEM Workshop Business
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.workshop-discount') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon amber"><i class="fas fa-percent"></i></span>
                    Workshop Discount
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 6 — INVENTORY
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-inventory" class="tab-panel">

        <div class="section-label">Stock Reports</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.stock') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <input type="hidden" name="Stock" value="complete">
                <button type="submit" class="report-btn w-full">
                    <span class="icon slate"><i class="fas fa-boxes"></i></span>
                    Complete Stock
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.stock') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <input type="hidden" name="source" value="imc">
                <button type="submit" class="report-btn w-full">
                    <span class="icon blue"><i class="fas fa-box"></i></span>
                    OEM Inventory
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.stock') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <input type="hidden" name="source" value="local">
                <button type="submit" class="report-btn w-full">
                    <span class="icon green"><i class="fas fa-box-open"></i></span>
                    Local Inventory
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.stock-cate') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon purple"><i class="fas fa-layer-group"></i></span>
                    Cate-Wise Inventory
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.stock-history') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon red"><i class="fas fa-history"></i></span>
                    Stock History
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.part-stock') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon teal"><i class="fas fa-search"></i></span>
                    Part Stock Search
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.reorder') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon amber"><i class="fas fa-bell"></i></span>
                    Reorder Report
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.non-moving') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon red"><i class="fas fa-pause-circle"></i></span>
                    Non-Moving
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 7 — KPI
    ═══════════════════════════════════════════════════════ --}}
    <div id="tab-kpi" class="tab-panel">

        <div class="section-label">KPI Reports</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.kpi-sale') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon blue"><i class="fas fa-chart-line"></i></span>
                    KPI Sale
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.kpi-purch') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon blue"><i class="fas fa-chart-bar"></i></span>
                    KPI Purchase
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.kpi-profit') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon green"><i class="fas fa-coins"></i></span>
                    KPI Profit
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.kpi-stock') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon amber"><i class="fas fa-warehouse"></i></span>
                    KPI Stock
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.kpi-workshop') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon slate"><i class="fas fa-wrench"></i></span>
                    KPI Workshop
                </button>
            </form>

            <a href="{{ route('parts.kpi-report') }}" target="_blank" class="report-btn">
                <span class="icon teal"><i class="fas fa-tachometer-alt"></i></span>
                KPI Dashboard
            </a>

            <a href="{{ route('parts.dpok-report') }}" target="_blank" class="report-btn">
                <span class="icon purple"><i class="fas fa-chart-pie"></i></span>
                DPOK Report
            </a>

            <form method="GET" action="{{ route('parts.reports.mad') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon red"><i class="fas fa-exclamation-triangle"></i></span>
                    MAD Report
                </button>
            </form>
        </div>

        <div class="section-label mt-4">PM / Appointments</div>
        <div class="report-grid">
            <form method="GET" action="{{ route('parts.reports.pmgr') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon blue"><i class="fas fa-file-alt"></i></span>
                    PMGR Report
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.appt-pmgr') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon green"><i class="fas fa-calendar-check"></i></span>
                    Appointment PMGR
                </button>
            </form>

            <form method="GET" action="{{ route('parts.reports.mrs-sheet') }}" target="_blank">
                <input type="hidden" name="daterange" class="dr-val">
                <button type="submit" class="report-btn w-full">
                    <span class="icon amber"><i class="fas fa-table"></i></span>
                    MRS Sheet
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
    $('#parts_daterange').daterangepicker({ locale: { format: 'MM/DD/YYYY' } });

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
            var dr = document.getElementById('parts_daterange').value;
            form.querySelectorAll('.dr-val').forEach(function(inp) {
                inp.value = dr;
            });
        });
    });
</script>
@endpush
