@extends('parts.layout')
@section('title', 'Revenue Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Revenue Report — {{ $from }} to {{ $to }}</h2>
@include('parts.entry.reports._filter', ['showDates'=>true])
@php
$wpRev  = $wpRevenue->revenue ?? 0;
$wpCost = $wpRevenue->cost ?? 0;
$cnRev  = $wpConsRevenue->revenue ?? 0;
$cnCost = $wpConsRevenue->cost ?? 0;
$ctRev  = $counterRevenue->revenue ?? 0;
$total  = $wpRev + $cnRev + $ctRev;
$totalCost = $wpCost + $cnCost;
@endphp
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded shadow-sm border border-gray-200 p-4">
        <div class="text-xs text-gray-500 uppercase mb-1">Workshop Parts</div>
        <div class="text-2xl font-bold text-red-600">Rs {{ number_format($wpRev,0) }}</div>
        <div class="text-xs text-gray-400">Cost: Rs {{ number_format($wpCost,0) }}</div>
        <div class="text-xs font-medium {{ ($wpRev-$wpCost)>=0 ? 'text-green-600' : 'text-red-500' }}">Profit: Rs {{ number_format($wpRev-$wpCost,0) }}</div>
    </div>
    <div class="bg-white rounded shadow-sm border border-gray-200 p-4">
        <div class="text-xs text-gray-500 uppercase mb-1">Workshop Consumables</div>
        <div class="text-2xl font-bold text-red-600">Rs {{ number_format($cnRev,0) }}</div>
        <div class="text-xs text-gray-400">Cost: Rs {{ number_format($cnCost,0) }}</div>
        <div class="text-xs font-medium {{ ($cnRev-$cnCost)>=0 ? 'text-green-600' : 'text-red-500' }}">Profit: Rs {{ number_format($cnRev-$cnCost,0) }}</div>
    </div>
    <div class="bg-white rounded shadow-sm border border-gray-200 p-4">
        <div class="text-xs text-gray-500 uppercase mb-1">Counter Sale</div>
        <div class="text-2xl font-bold text-red-600">Rs {{ number_format($ctRev,0) }}</div>
    </div>
</div>
<div class="bg-red-600 rounded p-4 text-white flex justify-between items-center">
    <span class="font-bold text-lg">Total Revenue</span>
    <span class="font-bold text-2xl">Rs {{ number_format($total,0) }}</span>
</div>
@endsection
