@extends('layouts.master')
@section('title', 'Sales - Unique VINs')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-blue-500">
        <div class="text-2xl font-bold text-blue-500">{{ number_format($totalVehicles) }}</div>
        <div class="text-sm text-gray-500 mt-1">Total Vehicles</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-green-500">
        <div class="text-2xl font-bold text-green-500">{{ number_format($registeredCount) }}</div>
        <div class="text-sm text-gray-500 mt-1">Registered</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-{{ $regRate >= 80 ? 'green' : ($regRate >= 50 ? 'yellow' : 'red') }}-500">
        <div class="text-2xl font-bold text-gray-700">{{ $regRate }}%</div>
        <div class="text-sm text-gray-500 mt-1">Registration Rate</div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Vehicles by Make</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Make</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Share</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($vins as $i => $v)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $v->Make }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($v->total) }}</td>
                    <td class="px-4 py-3">
                        @php $pct = $totalVehicles > 0 ? round($v->total/$totalVehicles*100,1) : 0; @endphp
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-gray-100 rounded-full">
                                <div class="h-2 bg-blue-500 rounded-full" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-10">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
