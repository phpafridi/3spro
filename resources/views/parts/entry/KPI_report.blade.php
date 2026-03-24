@extends('parts.layout')
@section('title', 'KPI Reports - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">KPI Reports</h2>
    <p class="text-sm text-gray-500 mt-1">Key Performance Indicator reports</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @php
    $kpis = [
        ['title'=>'KPI Sale','color'=>'from-green-500 to-emerald-600','icon'=>'fa-usd'],
        ['title'=>'KPI Purchase','color'=>'from-blue-500 to-indigo-600','icon'=>'fa-cart-arrow-down'],
        ['title'=>'KPI Stock','color'=>'from-red-500 to-pink-600','icon'=>'fa-cubes'],
        ['title'=>'KPI Profit','color'=>'from-orange-500 to-amber-600','icon'=>'fa-line-chart'],
        ['title'=>'KPI Workshop','color'=>'from-teal-500 to-cyan-600','icon'=>'fa-wrench'],
        ['title'=>'MAD Report','color'=>'from-red-500 to-rose-600','icon'=>'fa-bar-chart'],
    ];
    @endphp
    @foreach($kpis as $k)
    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer">
        <div class="bg-gradient-to-r {{ $k['color'] }} p-4 text-white">
            <i class="fa {{ $k['icon'] }} text-2xl"></i>
        </div>
        <div class="p-4">
            <h3 class="font-semibold text-gray-800">{{ $k['title'] }}</h3>
        </div>
    </div>
    @endforeach
</div>
@endsection
