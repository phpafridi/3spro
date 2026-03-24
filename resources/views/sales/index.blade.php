@extends('layouts.master')
@section('title', 'Sales - Dashboard')
@section('sidebar-menu')
    @include('sales.partials.sidebar')
@endsection
@section('content')

{{-- Stats Row --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded shadow-sm p-5 border-l-4 border-red-500">
        <div class="text-3xl font-bold text-red-500">{{ $unclosedCount }}</div>
        <div class="text-sm text-gray-500 mt-1">Unclosed Jobcards</div>
    </div>
    <div class="bg-white rounded shadow-sm p-5 border-l-4 border-yellow-500">
        <div class="text-3xl font-bold text-yellow-500">{{ $pendingProblems }}</div>
        <div class="text-sm text-gray-500 mt-1">Problem Tray Pending</div>
    </div>
    <div class="bg-white rounded shadow-sm p-5 border-l-4 border-blue-500">
        <div class="text-3xl font-bold text-blue-500">{{ $pendingVin }}</div>
        <div class="text-sm text-gray-500 mt-1">VIN Checks Pending</div>
    </div>
    <div class="bg-white rounded shadow-sm p-5 border-l-4 border-green-500">
        @php
            $mechLabor = $todayRevenue->where('ro_type','Mechanical')->first();
            $bpLabor   = $todayRevenue->where('ro_type','Body / Paint')->first();
        @endphp
        <div class="text-3xl font-bold text-green-500">{{ number_format(($mechLabor->Labor ?? 0) + ($bpLabor->Labor ?? 0)) }}</div>
        <div class="text-sm text-gray-500 mt-1">Today's Labor Revenue</div>
    </div>
</div>

{{-- Today Revenue Breakdown --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-600 uppercase mb-3">Today — Mechanical</h3>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Labor</span>
            <span class="font-bold text-gray-800">{{ number_format($mechLabor->Labor ?? 0) }}</span>
        </div>
        <div class="flex justify-between text-sm mt-1">
            <span class="text-gray-500">Parts</span>
            <span class="font-bold text-gray-800">{{ number_format($mechLabor->Parts ?? 0) }}</span>
        </div>
    </div>
    <div class="bg-white rounded shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-600 uppercase mb-3">Today — Body &amp; Paint</h3>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Labor</span>
            <span class="font-bold text-gray-800">{{ number_format($bpLabor->Labor ?? 0) }}</span>
        </div>
        <div class="flex justify-between text-sm mt-1">
            <span class="text-gray-500">Parts</span>
            <span class="font-bold text-gray-800">{{ number_format($bpLabor->Parts ?? 0) }}</span>
        </div>
    </div>
</div>

{{-- Customer Ratings --}}
@if($ratings)
<div class="bg-white rounded shadow-sm p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer Ratings (Average)</h2>
    <div class="grid grid-cols-4 gap-4">
        @foreach(['management'=>'Management','services'=>'Services','prices'=>'Pricing','cleanance'=>'Cleanliness'] as $key=>$label)
        @php $val = round($ratings->$key ?? 0, 1); @endphp
        <div class="text-center">
            <div class="text-2xl font-bold {{ $val >= 4 ? 'text-green-600' : ($val >= 3 ? 'text-yellow-500' : 'text-red-500') }}">
                {{ $val }}/5
            </div>
            <div class="text-xs text-gray-500 mt-1">{{ $label }}</div>
            <div class="mt-2 h-2 bg-gray-100 rounded-full">
                <div class="h-2 rounded-full {{ $val >= 4 ? 'bg-green-500' : ($val >= 3 ? 'bg-yellow-400' : 'bg-red-400') }}"
                     style="width:{{ ($val/5)*100 }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Last 20 Days Chart --}}
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Last 20 Days — Business Summary</h2>
    @if($chartData->count())
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">ROs</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Parts</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($chartData as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $row->day }}</td>
                    <td class="px-4 py-2 text-sm text-right text-gray-700">{{ $row->ros }}</td>
                    <td class="px-4 py-2 text-sm text-right text-gray-700">{{ number_format($row->labor) }}</td>
                    <td class="px-4 py-2 text-sm text-right text-gray-700">{{ number_format($row->parts) }}</td>
                    <td class="px-4 py-2 text-sm text-right font-bold text-gray-900">{{ number_format($row->labor + $row->parts) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-gray-400 text-sm italic">No invoice data found for the last 20 days.</p>
    @endif
</div>
@endsection
