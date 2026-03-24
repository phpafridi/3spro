@extends('layouts.master')
@section('title', 'Sales - New Reports')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">New Reports</h2>
    <p class="text-gray-400 text-sm mb-6">Extended analytics and performance reports.</p>
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['NVS',                   'fa-car',           'blue'],
            ['TUS',                   'fa-users',         'green'],
            ['CPU',                   'fa-desktop',       'purple'],
            ['FFS Units',             'fa-chart-bar',     'yellow'],
            ['Visit - 3 Month',       'fa-calendar',      'pink'],
            ['Visit - 6 Month',       'fa-calendar-alt',  'red'],
            ['Visit - 12 Month',      'fa-calendar-check','indigo'],
            ['PSFU Graph',            'fa-chart-line',    'teal'],
            ['OTD',                   'fa-clock',         'orange'],
        ] as [$label, $icon, $color])
        <div class="border border-gray-200 rounded p-4 hover:border-blue-300 hover:shadow-sm transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-{{ $color }}-100 rounded flex items-center justify-center">
                    <i class="fa {{ $icon }} text-{{ $color }}-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-800">{{ $label }}</span>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
        <p class="text-sm text-blue-700"><i class="fa fa-info-circle mr-2"></i>Native Laravel reports coming soon. Legacy versions still available under Reports.</p>
    </div>
</div>
@endsection
