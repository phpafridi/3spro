@extends('parts.layout')
@section('title', 'Lost Sale Report')
@section('content')
@include('partials.company-header')
<h2 class="text-xl font-bold text-gray-800 mb-4">Lost Sale — {{ $from }} to {{ $to }}</h2>
@include('parts.entry.reports._filter', ['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-red-600 p-3 flex justify-between">
        <h3 class="font-semibold text-white">Parts Not Available for Appointments</h3>
        <span class="text-white text-sm">Total Lost: Rs {{ number_format($totalLost,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Customer</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Variant</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Parts</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Parts Cost</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">CRO</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($lost as $i => $l)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $l->CustomerName }}</td>
                    <td class="px-3 py-2 text-xs">{{ $l->Variant }}</td>
                    <td class="px-3 py-2 text-xs text-gray-600">{{ $l->parts }}</td>
                    <td class="px-3 py-2 text-right font-bold text-red-600">{{ number_format($l->Parts_cost,0) }}</td>
                    <td class="px-3 py-2 text-xs">{{ $l->CRO }}</td>
                    <td class="px-3 py-2 text-xs text-gray-400">{{ $l->appt_datetime }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No lost sales found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
