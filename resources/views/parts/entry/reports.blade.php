@extends('parts.layout')
@section('title', 'Reports - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Parts Reports</h2>
    <p class="text-sm text-gray-500 mt-1">Select a report to generate</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @php
    $reports = [
        ['title'=>'Daily Sale','icon'=>'fa-usd','color'=>'from-green-500 to-emerald-600','file'=>'daily_sale'],
        ['title'=>'Stock Report','icon'=>'fa-cubes','color'=>'from-blue-500 to-indigo-600','file'=>'stock'],
        ['title'=>'Purchase Report','icon'=>'fa-cart-arrow-down','color'=>'from-purple-500 to-pink-600','file'=>'purchase'],
        ['title'=>'Sale History','icon'=>'fa-history','color'=>'from-orange-500 to-amber-600','file'=>'salehistory'],
        ['title'=>'Dead Stock','icon'=>'fa-exclamation-triangle','color'=>'from-red-500 to-rose-600','file'=>'dead_stock'],
        ['title'=>'Moving Stock','icon'=>'fa-truck','color'=>'from-teal-500 to-cyan-600','file'=>'moving_stock'],
        ['title'=>'Non Moving','icon'=>'fa-ban','color'=>'from-gray-500 to-slate-600','file'=>'non_moving'],
        ['title'=>'Fill Rate','icon'=>'fa-bar-chart','color'=>'from-violet-500 to-purple-600','file'=>'fillrate'],
        ['title'=>'Lost Sale','icon'=>'fa-times-circle','color'=>'from-pink-500 to-rose-600','file'=>'lostsale'],
        ['title'=>'Revenue','icon'=>'fa-line-chart','color'=>'from-indigo-500 to-blue-600','file'=>'revenue'],
        ['title'=>'Reorder','icon'=>'fa-refresh','color'=>'from-amber-500 to-yellow-600','file'=>'reorder'],
        ['title'=>'Part Wise','icon'=>'fa-list','color'=>'from-cyan-500 to-blue-600','file'=>'part_wise'],
    ];
    @endphp
    @foreach($reports as $r)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="bg-gradient-to-r {{ $r['color'] }} p-4 text-white">
            <i class="fa {{ $r['icon'] }} text-2xl"></i>
        </div>
        <div class="p-4">
            <h3 class="font-semibold text-gray-800">{{ $r['title'] }}</h3>
            <a href="#" class="mt-2 inline-block text-xs text-indigo-600 hover:text-indigo-800">View Report &rarr;</a>
        </div>
    </div>
    @endforeach
</div>
@endsection
