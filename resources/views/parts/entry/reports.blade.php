@extends('parts.layout')
@section('title', 'Parts Reports')
@section('content')

<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800">Parts Reports</h2>
    <p class="text-sm text-gray-500 mt-1">Select a report to generate</p>
</div>

@php
$reports = [
    ['title'=>'Daily Sale',    'icon'=>'fa-dollar',         'route'=>'parts.reports.daily-sale',  'desc'=>'Workshop + counter sales by date range'],
    ['title'=>'Stock Report',  'icon'=>'fa-cubes',          'route'=>'parts.reports.stock',        'desc'=>'Current stock with value'],
    ['title'=>'Purchase',      'icon'=>'fa-cart-arrow-down','route'=>'parts.reports.purchase',     'desc'=>'Purchase invoices by date / vendor'],
    ['title'=>'Sale Report',   'icon'=>'fa-money',          'route'=>'parts.reports.sale',         'desc'=>'Counter sale invoices by date'],
    ['title'=>'Sale History',  'icon'=>'fa-history',        'route'=>'parts.reports.sale-history', 'desc'=>'Part-wise total sales by date range'],
    ['title'=>'Dead Stock',    'icon'=>'fa-warning',        'route'=>'parts.reports.dead-stock',   'desc'=>'Stock not sold in N months'],
    ['title'=>'Non Moving',    'icon'=>'fa-ban',            'route'=>'parts.reports.non-moving',   'desc'=>'All unsold stock'],
    ['title'=>'Fill Rate',     'icon'=>'fa-bar-chart',      'route'=>'parts.reports.fill-rate',    'desc'=>'Appointment parts availability'],
    ['title'=>'Lost Sale',     'icon'=>'fa-times-circle',   'route'=>'parts.reports.lost-sale',    'desc'=>'Parts marked not available'],
    ['title'=>'Revenue',       'icon'=>'fa-line-chart',     'route'=>'parts.reports.revenue',      'desc'=>'Revenue vs cost breakdown'],
    ['title'=>'Reorder',       'icon'=>'fa-refresh',        'route'=>'parts.reports.reorder',      'desc'=>'Parts below reorder level'],
    ['title'=>'Part Wise',     'icon'=>'fa-list',           'route'=>'parts.reports.part-wise',    'desc'=>'Sales grouped by part number'],
];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @foreach($reports as $r)
    <a href="{{ route($r['route']) }}"
       class="bg-white rounded shadow-sm border border-gray-200 hover:border-red-400 hover:shadow-md transition-all group">
        <div class="bg-red-600 group-hover:bg-red-700 p-4 rounded-t flex items-center gap-3">
            <i class="fa {{ $r['icon'] }} text-xl text-white"></i>
            <span class="font-semibold text-white">{{ $r['title'] }}</span>
        </div>
        <div class="p-3">
            <p class="text-xs text-gray-500">{{ $r['desc'] }}</p>
        </div>
    </a>
    @endforeach
</div>
@endsection
