@extends('parts.layout')
@section('title', 'Fill Rate Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Fill Rate Report — {{ $from }} to {{ $to }}</h2>
@include('parts.entry.reports._filter', ['showDates'=>true])
<div class="grid grid-cols-3 gap-4 mb-5">
    <div class="bg-green-50 border border-green-200 rounded p-4 text-center">
        <div class="text-3xl font-bold text-green-600">{{ $fillRate }}%</div>
        <div class="text-sm text-gray-600 mt-1">Fill Rate</div>
    </div>
    <div class="bg-white border border-gray-200 rounded p-4 text-center">
        <div class="text-3xl font-bold text-green-600">{{ $stats->available ?? 0 }}</div>
        <div class="text-sm text-gray-600 mt-1">Available</div>
    </div>
    <div class="bg-red-50 border border-red-200 rounded p-4 text-center">
        <div class="text-3xl font-bold text-red-600">{{ $stats->not_available ?? 0 }}</div>
        <div class="text-sm text-gray-600 mt-1">Not Available</div>
    </div>
</div>
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-red-600 p-3"><h3 class="font-semibold text-white">Appointment Details</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Customer</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Variant</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Parts</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Cost</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Status</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">CRO</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($details as $i => $d)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $d->CustomerName }}</td>
                    <td class="px-3 py-2 text-xs">{{ $d->Variant }}</td>
                    <td class="px-3 py-2 text-xs text-gray-600">{{ $d->parts }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($d->Parts_cost,0) }}</td>
                    <td class="px-3 py-2">
                        @if($d->parts_status==2)
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs">Available</span>
                        @else
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs">Not Available</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-xs">{{ $d->CRO }}</td>
                    <td class="px-3 py-2 text-xs text-gray-400">{{ $d->appt_datetime }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
