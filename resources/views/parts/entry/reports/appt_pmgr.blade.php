@extends('parts.layout')
@section('title','Appointment PMGR')
@section('content')
@include('partials.company-header')
<h2 class="text-xl font-bold text-gray-800 mb-4">Appointment Parts & Consumables</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="grid grid-cols-2 gap-3 mb-4">
    <div class="bg-white border border-gray-200 rounded p-3 text-center">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Requests</p>
        <p class="text-2xl font-bold text-orange-600">{{ number_format($totalReqs) }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded p-3 text-center">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Business</p>
        <p class="text-2xl font-bold text-green-600">Rs {{ number_format($totalSum,0) }}</p>
    </div>
</div>
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-orange-500 p-3"><h3 class="font-semibold text-white">Appointments</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">CRO</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Customer</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Variant</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Parts</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Cost</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Status</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-orange-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->CRO }}</td>
                    <td class="px-3 py-2">{{ $r->CustomerName }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->Variant }}</td>
                    <td class="px-3 py-2">{{ $r->parts }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->Parts_cost,0) }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-0.5 rounded text-xs {{ $r->parts_status == 1 ? 'bg-green-100 text-green-700' : ($r->parts_status == 3 ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ $r->parts_status == 1 ? 'Available' : ($r->parts_status == 3 ? 'Not Available' : 'Pending') }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->appt_datetime }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
