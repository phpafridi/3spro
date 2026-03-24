@extends('layouts.master')
@section('title', 'SM Reports')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')

<style>
.rpt-btn        { display:inline-block; padding:5px 12px; font-size:13px; font-weight:600; border-radius:4px; cursor:pointer; text-decoration:none; margin:2px; border:none; }
.rpt-green      { background:#16a34a; color:#fff; }
.rpt-green:hover{ background:#15803d; color:#fff; }
.rpt-blue       { background:#2563eb; color:#fff; }
.rpt-blue:hover { background:#1d4ed8; color:#fff; }
.rpt-red        { background:#dc2626; color:#fff; }
.rpt-red:hover  { background:#b91c1c; color:#fff; }
.rpt-dark       { background:#374151; color:#fff; }
.rpt-dark:hover { background:#1f2937; color:#fff; }
.rpt-teal       { background:#0d9488; color:#fff; }
.rpt-teal:hover { background:#0f766e; color:#fff; }
.rpt-na         { display:inline-block; padding:5px 12px; font-size:13px; border-radius:4px; margin:2px; background:#e5e7eb; color:#9ca3af; text-decoration:line-through; cursor:not-allowed; }
.tab-btn        { padding:8px 18px; font-size:13px; font-weight:600; cursor:pointer; border:1px solid #d1d5db; border-bottom:none; border-radius:4px 4px 0 0; margin-right:3px; background:#f3f4f6; color:#374151; }
.tab-btn.active { background:#dc2626; color:#fff; border-color:#dc2626; }
.tab-pane       { display:none; }
.tab-pane.active{ display:block; }
.section-label  { font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.05em; margin:12px 0 6px; }
</style>

<h2 style="font-size:20px;font-weight:700;margin-bottom:12px;">Reports &amp; Scrolls</h2>

{{-- Shared date range --}}
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:14px;margin-bottom:14px;">
    <label style="font-size:13px;font-weight:600;color:#374151;margin-right:10px;">Date Range</label>
    <input type="date" id="shared_from" value="{{ now()->toDateString() }}"
           style="border:1px solid #d1d5db;border-radius:4px;padding:6px 10px;font-size:13px;">
    <span style="margin:0 8px;color:#9ca3af;">—</span>
    <input type="date" id="shared_to" value="{{ now()->toDateString() }}"
           style="border:1px solid #d1d5db;border-radius:4px;padding:6px 10px;font-size:13px;">
</div>

{{-- Tabs --}}
<div style="border-bottom:2px solid #dc2626;">
    <button class="tab-btn active" onclick="showTab('service',this)">Service Reports</button>
    <button class="tab-btn"        onclick="showTab('cr',this)">CR Reports</button>
    <button class="tab-btn"        onclick="showTab('mrs',this)">MRS Reports</button>
    <button class="tab-btn"        onclick="showTab('genesis',this)">Genesis Reports</button>
    <button class="tab-btn"        onclick="showTab('new',this)">New Reports</button>
</div>

<div style="background:#fff;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 6px 6px;padding:18px;">

    {{-- ══ SERVICE REPORTS ══ --}}
    <div id="tab-service" class="tab-pane active">

        <div class="section-label">Invoice Report by Type</div>
        <div>
            @foreach(['CM','DM','DMC','COMP'=>'Complementry','GW'=>'GoodWill','JND','PDS','FFS','WC','CNI'] as $type => $label)
            @php $t = is_int($type) ? $label : $type; @endphp
            <a href="{{ route('sm.reports.invoice') }}" onclick="return openReport(this,'{{ $t }}')" class="rpt-btn rpt-green">{{ $label }}</a>
            @endforeach
            <a href="{{ route('sm.reports.invoice') }}" onclick="return openReport(this,'CBJ')" class="rpt-btn rpt-red">CBJ</a>
        </div>

        <div class="section-label">Business</div>
        <div>
            <a href="{{ route('sm.reports.summary') }}"       onclick="return openReport(this)"        class="rpt-btn rpt-green">Business Summary</a>
            <a href="{{ route('sm.reports.sales-tax') }}"     onclick="return openReport(this)"        class="rpt-btn rpt-green">Sales Tax Invoices</a>
            <a href="{{ route('sm.reports.top-labor') }}"     onclick="return openReport(this)"        class="rpt-btn rpt-green">Top Services</a>
            <a href="{{ route('sm.reports.sublet-profit') }}" onclick="return openReport(this)"        class="rpt-btn rpt-blue">Sublet Business</a>
            <a href="{{ route('sm.reports.warranty') }}"      onclick="return openReport(this)"        class="rpt-btn rpt-blue">Warranty Business</a>
        </div>

        <div class="section-label">SA &amp; Teams</div>
        <div>
            <a href="{{ route('sm.reports.sa') }}"            onclick="return openReport(this)"        class="rpt-btn rpt-blue">SA/JC Performance</a>
            <a href="{{ route('sm.reports.team') }}"          onclick="return openReport(this)"        class="rpt-btn rpt-blue">Teams Labor</a>
            <a href="{{ route('sm.reports.ffs-rate') }}"      onclick="return openReport(this)"        class="rpt-btn rpt-blue">CR Rate &amp; KYC</a>
            <a href="{{ route('sm.reports.zero-invoices') }}" onclick="return openReport(this)"        class="rpt-btn rpt-blue">Zero Invoices</a>
            <a href="{{ route('sm.reports.ratings') }}"       onclick="return openReport(this)"        class="rpt-btn rpt-blue">Customer Ratings</a>
        </div>

        <div class="section-label">Department Reports</div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;">
            <select id="cust_type_sel" style="border:1px solid #d1d5db;border-radius:4px;padding:5px 10px;font-size:13px;">
                <option value="">Overall</option>
                <option>Individuals</option><option>Govt</option>
                <option>Force</option><option>Corporate</option><option>Others</option>
            </select>
            <a href="{{ route('sm.reports.dept') }}" onclick="return openDeptReport(this,'Mechanical')"  class="rpt-btn rpt-green">Mechanical</a>
            <a href="{{ route('sm.reports.dept') }}" onclick="return openDeptReport(this,'Warranty')"    class="rpt-btn rpt-green">Warranty</a>
            <a href="{{ route('sm.reports.dept') }}" onclick="return openDeptReport(this,'Body / Paint')"class="rpt-btn rpt-green">Body / Paint</a>
            <a href="{{ route('sm.reports.dept') }}" onclick="return openDeptReport(this,'overall')"     class="rpt-btn rpt-green">Overall</a>
        </div>

        <div class="section-label">Campaign</div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;">
            <select id="campaign_source" style="border:1px solid #d1d5db;border-radius:4px;padding:5px 10px;font-size:13px;">
                <option value="">All Sources</option>
                <option>Appointed</option>
                @foreach($campaigns as $c)
                    @if($c->nature==='CustType')<option>{{ $c->campaign_name }}</option>@endif
                @endforeach
            </select>
            <select id="campaign_name" style="border:1px solid #d1d5db;border-radius:4px;padding:5px 10px;font-size:13px;">
                <option value="">All Campaigns</option>
                @foreach($campaigns as $c)
                    @if($c->nature==='Campaign')<option>{{ $c->campaign_name }}</option>@endif
                @endforeach
            </select>
            <a href="{{ route('sm.reports.campaign') }}" onclick="return openCampaignReport(this)" class="rpt-btn rpt-green">Campaign Report</a>
        </div>

        <div class="section-label">Unclose Report</div>
        <div>
            <a href="{{ route('sm.reports.zero-invoices') }}" onclick="return openReport(this)" class="rpt-btn rpt-blue">Unclose Business</a>
        </div>
    </div>

    {{-- ══ CR REPORTS ══ --}}
    <div id="tab-cr" class="tab-pane">
        <div class="section-label">CR Reports</div>
        <div>
            <a href="{{ route('sm.reports.psfu') }}"    onclick="return openReport(this)" class="rpt-btn rpt-dark">PSFU Report</a>
            <a href="{{ route('sm.reports.ratings') }}" onclick="return openReport(this)" class="rpt-btn rpt-dark">Customer Ratings</a>
            <a href="{{ route('sm.reports.visits') }}"  onclick="return openReport(this)" class="rpt-btn rpt-dark">New vs Old Customers</a>
            <span class="rpt-na">Jobcards Details</span>
        </div>
    </div>

    {{-- ══ MRS REPORTS ══ --}}
    <div id="tab-mrs" class="tab-pane">
        <div class="section-label">MRS Reports (cr/CRO module — not in SM zip)</div>
        <div>
            <span class="rpt-na">SMS Status</span>
            <span class="rpt-na">Pendency</span>
            <span class="rpt-na">Appointments Staffwise</span>
            <span class="rpt-na">FFS Calls</span>
            <span class="rpt-na">PM Calls</span>
            <span class="rpt-na">Dormant Calls</span>
            <span class="rpt-na">MRS Calls</span>
            <span class="rpt-na">Problem Tray</span>
            <span class="rpt-na">Action Trays</span>
        </div>
    </div>

    {{-- ══ GENESIS REPORTS ══ --}}
    <div id="tab-genesis" class="tab-pane">
        <div class="section-label">Genesis Reports</div>
        <div>
            <a href="{{ route('sm.reports.ffs-units') }}" onclick="return openReport(this)" class="rpt-btn rpt-teal">FIR Genesis Report</a>
            <a href="{{ route('sm.reports.cpus') }}"      onclick="return openReport(this)" class="rpt-btn rpt-teal">PM GR BP Genesis Report</a>
            <a href="{{ route('sm.reports.tus') }}"       onclick="return openReport(this)" class="rpt-btn rpt-teal">MSI Report</a>
            <a href="{{ route('sm.reports.nvs') }}"       onclick="return openReport(this)" class="rpt-btn rpt-teal">MSI Detail Report</a>
            <a href="{{ route('sm.reports.app-rate') }}"  onclick="return openReport(this)" class="rpt-btn rpt-teal">PM Genesis Excel</a>
        </div>
    </div>

    {{-- ══ NEW REPORTS ══ --}}
    <div id="tab-new" class="tab-pane">
        <div class="section-label">New Reports</div>
        <div>
            <a href="{{ route('sm.reports.labor-detail') }}"   onclick="return openReport(this)" class="rpt-btn rpt-blue">Timing Details</a>
            <a href="{{ route('sm.reports.parts-timings') }}"  onclick="return openReport(this)" class="rpt-btn rpt-blue">Parts Timings</a>
            <a href="{{ route('sm.reports.sa-parts') }}"       onclick="return openReport(this)" class="rpt-btn rpt-blue">SA Parts Targets</a>
            <a href="{{ route('sm.reports.visits') }}"         onclick="return openReport(this)" class="rpt-btn rpt-blue">Vehicles Visits</a>
            <a href="{{ route('sm.reports.zero-invoices') }}"  onclick="return openReport(this)" class="rpt-btn rpt-blue">Zero Invoices</a>
            <a href="{{ route('sm.reports.bays') }}"           onclick="return openReport(this)" class="rpt-btn rpt-blue">Bays Business</a>
            <a href="{{ route('sm.reports.otd') }}"            onclick="return openReport(this)" class="rpt-btn rpt-blue">OTD</a>
            <a href="{{ route('sm.reports.wyw') }}"            onclick="return openReport(this)" class="rpt-btn rpt-blue">WYW</a>
            <span class="rpt-na">JC Changes</span>
            <span class="rpt-na">MSI Changes</span>
            <span class="rpt-na">Loyalty Card</span>
            <span class="rpt-na">Labor Timings</span>
            <span class="rpt-na">Labor Analysis</span>
        </div>
    </div>

</div>{{-- end panel --}}

<script>
function showTab(name, btn) {
    document.querySelectorAll('.tab-pane').forEach(function(el){ el.classList.remove('active'); });
    document.querySelectorAll('.tab-btn').forEach(function(el){ el.classList.remove('active'); });
    document.getElementById('tab-' + name).classList.add('active');
    if(btn) btn.classList.add('active');
}

function getDateParams() {
    var from = document.getElementById('shared_from').value;
    var to   = document.getElementById('shared_to').value;
    return '?from=' + from + '&to=' + to;
}

function openReport(el, type) {
    var url = el.href.split('?')[0] + getDateParams();
    if (type) url += '&type=' + encodeURIComponent(type);
    window.open(url, '_blank');
    return false;
}

function openDeptReport(el, type) {
    var custType = document.getElementById('cust_type_sel').value;
    var url = el.href.split('?')[0] + getDateParams()
              + '&ro_type=' + encodeURIComponent(type)
              + (custType ? '&cust_type=' + encodeURIComponent(custType) : '');
    window.open(url, '_blank');
    return false;
}

function openCampaignReport(el) {
    var source   = document.getElementById('campaign_source').value;
    var campaign = document.getElementById('campaign_name').value;
    var url = el.href.split('?')[0] + getDateParams()
              + (source   ? '&source='   + encodeURIComponent(source)   : '')
              + (campaign ? '&campaign=' + encodeURIComponent(campaign) : '');
    window.open(url, '_blank');
    return false;
}
</script>
@endsection
