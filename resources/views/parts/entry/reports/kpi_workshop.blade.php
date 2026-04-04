@extends('parts.layout')
@section('title','KPI Workshop')
@section('content')
@include('partials.company-header')
<h2 class="text-xl font-bold text-gray-800 mb-4">Workshop OEM Business KPI</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="grid grid-cols-2 gap-3 mb-4">
    <div class="bg-white border border-gray-200 rounded p-3 text-center">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total ROs</p>
        <p class="text-2xl font-bold text-purple-600">{{ number_format($totalRos) }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded p-3 text-center">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Business</p>
        <p class="text-2xl font-bold text-green-600">Rs {{ number_format($totalSum,0) }}</p>
    </div>
</div>
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-purple-600 p-3"><h3 class="font-semibold text-white">Monthly OEM Workshop</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Period</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Total ROs</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Business</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-purple-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->period }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->total_ros) }}</td>
                    <td class="px-3 py-2 text-right font-semibold">Rs {{ number_format($r->total_sum,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
