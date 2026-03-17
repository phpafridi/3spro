@extends('layouts.master')
@section('title', 'Sales - Reports')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Reports &amp; Scrolls</h2>
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['NVS Report',         'files/NVS.php',              'fa-car',          'blue'],
            ['TUS Report',         'files/TUS.php',              'fa-users',        'green'],
            ['CPU Report',         'files/cpus.php',             'fa-desktop',      'purple'],
            ['FFS Units',          'files/FFS_units.php',        'fa-chart-bar',    'yellow'],
            ['AC Report',          'AC.php',                     'fa-user-check',   'red'],
            ['VIN Report',         'VIN.php',                    'fa-id-card',      'indigo'],
            ['Visit Report',       'files/visits.php',           'fa-calendar',     'pink'],
            ['PSFU Graph',         'files/psfu_graph.php',       'fa-chart-line',   'teal'],
            ['OTD Report',         'files/OTD.php',              'fa-clock',        'orange'],
            ['NVD',                'files/NVD.php',              'fa-file-alt',     'gray'],
            ['MSI Report',         'files/MSI.php',              'fa-table',        'blue'],
            ['Labor Report',       'files/report_labor.php',     'fa-wrench',       'green'],
        ] as [$label, $file, $icon, $color])
        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-{{ $color }}-100 rounded-lg flex items-center justify-center">
                    <i class="fa {{ $icon }} text-{{ $color }}-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-800">{{ $label }}</span>
            </div>
            <p class="text-xs text-gray-400">Legacy report — opens in new tab</p>
        </div>
        @endforeach
    </div>
    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <p class="text-sm text-yellow-700"><i class="fa fa-info-circle mr-2"></i>These reports point to legacy PHP files. They will be converted to native Laravel reports in a future sprint.</p>
    </div>
</div>
@endsection
