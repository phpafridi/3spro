@extends('parts.layout')
@section('title', 'Parts Reports')
@section('content')

<style>
.report-card {
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    transition: transform 0.15s, box-shadow 0.15s, border-color 0.15s;
    text-decoration: none;
    color: inherit;
}
.report-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.10);
    border-color: #ef4444;
    text-decoration: none;
}
.report-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    color: #fff;
}
.report-card-header .icon-wrap {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: rgba(255,255,255,0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.report-card-header .icon-wrap i { font-size: 15px; }
.report-card-header span {
    font-size: 13px;
    font-weight: 600;
    line-height: 1.3;
}
.report-card-body {
    padding: 9px 16px 13px;
    flex: 1;
}
.report-card-body p {
    font-size: 11.5px;
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}
.section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    margin-top: 26px;
}
.section-header:first-of-type { margin-top: 0; }
.section-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 5px 14px 5px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #fff;
    white-space: nowrap;
}
.section-badge i { font-size: 12px; }
.section-line { flex: 1; height: 1px; background: #e5e7eb; }
.section-count { font-size: 11px; color: #9ca3af; font-weight: 500; white-space: nowrap; }
</style>

{{-- Page heading --}}
<div class="mb-5 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Parts Reports</h2>
        <p class="text-sm text-gray-500 mt-0.5">Select any report to generate</p>
    </div>
</div>

@php
$sections = [

    ['label'=>'Sales', 'icon'=>'fa-dollar', 'color'=>'bg-red-600', 'reports'=>[
        ['title'=>'Daily Sale',      'icon'=>'fa-calendar',      'route'=>'parts.reports.daily-sale',   'desc'=>'Workshop + counter sales by date range'],
        ['title'=>'Sale Report',     'icon'=>'fa-money',         'route'=>'parts.reports.sale',          'desc'=>'Counter sale invoices by date'],
        ['title'=>'Sale History',    'icon'=>'fa-history',       'route'=>'parts.reports.sale-history',  'desc'=>'Part-wise total sales by date range'],
        ['title'=>'Revenue',         'icon'=>'fa-line-chart',    'route'=>'parts.reports.revenue',       'desc'=>'Revenue vs cost breakdown'],
        ['title'=>'Counter Return',  'icon'=>'fa-undo',          'route'=>'parts.reports.counter-return','desc'=>'Counter sale returns by date'],
        ['title'=>'Sale Search',     'icon'=>'fa-search',        'route'=>'parts.search',                'desc'=>'Search sale invoices by part / invoice #'],
    ]],

    ['label'=>'Purchase', 'icon'=>'fa-cart-arrow-down', 'color'=>'bg-blue-600', 'reports'=>[
        ['title'=>'Purchase',          'icon'=>'fa-cart-arrow-down','route'=>'parts.reports.purchase',         'desc'=>'Purchase invoices by date / vendor'],
        ['title'=>'Purchase Return',   'icon'=>'fa-undo',           'route'=>'parts.reports.purchase-return',  'desc'=>'Returned purchase items by date'],
        ['title'=>'Purch Credit/Cash', 'icon'=>'fa-credit-card',    'route'=>'parts.reports.purch-cre-cash',   'desc'=>'Credit vs cash purchase breakdown'],
        ['title'=>'Purchase Profit',   'icon'=>'fa-bar-chart',      'route'=>'parts.reports.purch-profit',     'desc'=>'GRN-wise purchase profit report'],
        ['title'=>'Vendor Payments',   'icon'=>'fa-bank',           'route'=>'parts.jobber-payment',           'desc'=>'Jobber / vendor payment details'],
    ]],

    ['label'=>'Stock', 'icon'=>'fa-cubes', 'color'=>'bg-red-600', 'reports'=>[
        ['title'=>'Stock Report',    'icon'=>'fa-cubes',      'route'=>'parts.reports.stock',         'desc'=>'Current stock with value'],
        ['title'=>'Dead Stock',      'icon'=>'fa-warning',    'route'=>'parts.reports.dead-stock',    'desc'=>'Stock not sold in N months'],
        ['title'=>'Non Moving',      'icon'=>'fa-ban',        'route'=>'parts.reports.non-moving',    'desc'=>'All unsold / non-moving stock'],
        ['title'=>'Reorder',         'icon'=>'fa-refresh',    'route'=>'parts.reports.reorder',       'desc'=>'Parts below reorder level'],
        ['title'=>'Part Wise Stock', 'icon'=>'fa-th-list',    'route'=>'parts.reports.part-stock',    'desc'=>'Stock details by part number'],
        ['title'=>'Stock Category',  'icon'=>'fa-tag',        'route'=>'parts.reports.stock-cate',    'desc'=>'Purchase history by category'],
        ['title'=>'Category Wise',   'icon'=>'fa-sitemap',    'route'=>'parts.reports.cate-wise',     'desc'=>'Parts business by category'],
        ['title'=>'Stock History',   'icon'=>'fa-archive',    'route'=>'parts.reports.stock-history', 'desc'=>'Historical inventory snapshot'],
        ['title'=>'Moving Stock',    'icon'=>'fa-arrows',     'route'=>'parts.reports.moving-stock',  'desc'=>'Monthly average demand by part'],
    ]],

    ['label'=>'Workshop', 'icon'=>'fa-wrench', 'color'=>'bg-orange-500', 'reports'=>[
        ['title'=>'Workshop Report',     'icon'=>'fa-wrench',       'route'=>'parts.reports.workshop',          'desc'=>'Total workshop ROs and business'],
        ['title'=>'Workshop Business',   'icon'=>'fa-briefcase',    'route'=>'parts.reports.workshop-business', 'desc'=>'Workshop business summary'],
        ['title'=>'Workshop Discount',   'icon'=>'fa-percent',      'route'=>'parts.reports.workshop-discount', 'desc'=>'Discount & tax breakdown'],
        ['title'=>'Workshop Return',     'icon'=>'fa-undo',         'route'=>'parts.reports.workshop-return',   'desc'=>'Parts returned from workshop'],
        ['title'=>'Parts WS Sales',      'icon'=>'fa-dollar',       'route'=>'parts.reports.parts-closing',     'desc'=>'Workshop parts sales business'],
        ['title'=>'Lost Sale',           'icon'=>'fa-times-circle', 'route'=>'parts.reports.lost-sale',         'desc'=>'Workshop parts marked not available'],
        ['title'=>'Fill Rate',           'icon'=>'fa-bar-chart',    'route'=>'parts.reports.fill-rate',         'desc'=>'Appointment parts availability %'],
        ['title'=>'PMGR Report',         'icon'=>'fa-file-text-o',  'route'=>'parts.reports.pmgr',              'desc'=>'Parts & consumables workshop report'],
        ['title'=>'Appt. PMGR',          'icon'=>'fa-calendar-o',   'route'=>'parts.reports.appt-pmgr',         'desc'=>'Appointment parts & consumables'],
        ['title'=>'Appointment Sheet',   'icon'=>'fa-table',        'route'=>'parts.reports.mrs-sheet',         'desc'=>'MRS appointment requisition sheet'],
    ]],

    ['label'=>'KPI Reports', 'icon'=>'fa-tachometer', 'color'=>'bg-purple-600', 'reports'=>[
        ['title'=>'KPI Overview',  'icon'=>'fa-tachometer',     'route'=>'parts.kpi-report',           'desc'=>'Key performance indicators overview'],
        ['title'=>'KPI Sale',      'icon'=>'fa-line-chart',     'route'=>'parts.reports.kpi-sale',     'desc'=>'OEM KPI sales report'],
        ['title'=>'KPI Purchase',  'icon'=>'fa-cart-arrow-down','route'=>'parts.reports.kpi-purch',    'desc'=>'OEM KPI purchase report'],
        ['title'=>'KPI Profit',    'icon'=>'fa-money',          'route'=>'parts.reports.kpi-profit',   'desc'=>'OEM KPI profit report'],
        ['title'=>'KPI Stock',     'icon'=>'fa-cubes',          'route'=>'parts.reports.kpi-stock',    'desc'=>'KPI stock status report'],
        ['title'=>'KPI Workshop',  'icon'=>'fa-wrench',         'route'=>'parts.reports.kpi-workshop', 'desc'=>'Workshop OEM business KPI'],
        ['title'=>'MAD Report',    'icon'=>'fa-area-chart',     'route'=>'parts.reports.mad',          'desc'=>'Monthly average demand (KPI)'],
        ['title'=>'OEM vs Local',   'icon'=>'fa-exchange',       'route'=>'parts.reports.imc-local',    'desc'=>'OEM genuine vs local parts summary'],
        ['title'=>'DPOK Report',   'icon'=>'fa-pie-chart',      'route'=>'parts.dpok-report',          'desc'=>'DPOK performance report'],
    ]],

    ['label'=>'Part Analysis', 'icon'=>'fa-list-alt', 'color'=>'bg-cyan-600', 'reports'=>[
        ['title'=>'Part Wise',         'icon'=>'fa-list',    'route'=>'parts.reports.part-wise',    'desc'=>'Sales grouped by part number'],
        ['title'=>'Part Sale History', 'icon'=>'fa-history', 'route'=>'parts.reports.sale-history', 'desc'=>'Historical sales of a specific part'],
    ]],

    ['label'=>'Incentives', 'icon'=>'fa-star', 'color'=>'bg-yellow-500', 'reports'=>[
        ['title'=>'Tech Incentive',  'icon'=>'fa-star',   'route'=>'parts.reports.incentive', 'desc'=>'Technician incentive report'],
        ['title'=>'Incentives Entry','icon'=>'fa-pencil', 'route'=>'parts.incentives',         'desc'=>'View / manage incentives entry'],
    ]],

];
@endphp

@foreach($sections as $section)
<div class="section-header">
    <span class="section-badge {{ $section['color'] }}">
        <i class="fa {{ $section['icon'] }}"></i>
        {{ $section['label'] }}
    </span>
    <div class="section-line"></div>
    <span class="section-count">{{ count($section['reports']) }} reports</span>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 mb-1">
    @foreach($section['reports'] as $r)
    @php
        try { $url = route($r['route']); } catch(\Exception $e) { $url = '#'; }
    @endphp
    <a href="{{ $url }}" class="report-card{{ $url === '#' ? ' opacity-60 pointer-events-none' : '' }}">
        <div class="report-card-header {{ $section['color'] }}">
            <div class="icon-wrap"><i class="fa {{ $r['icon'] }}"></i></div>
            <span>{{ $r['title'] }}</span>
        </div>
        <div class="report-card-body">
            <p>{{ $r['desc'] }}</p>
            @if($url === '#')
                <span class="inline-block mt-1 text-xs text-yellow-600 bg-yellow-50 px-1.5 py-0.5 rounded">Coming soon</span>
            @endif
        </div>
    </a>
    @endforeach
</div>
@endforeach

@endsection
