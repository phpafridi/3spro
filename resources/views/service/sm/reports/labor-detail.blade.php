@extends('layouts.master')
@section('title','Labor Detail')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Labor Detail — {{ $from }} to {{ $to }}</h2>

<div class="no-print" style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:14px;margin-bottom:16px;">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">From</label>
            <input type="date" name="from" value="{{ $from }}" class="border border-gray-300 rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">To</label>
            <input type="date" name="to" value="{{ $to }}" class="border border-gray-300 rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">RO Type</label>
            <select name="ro_type" class="border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="">All</option>
                @foreach(['Mechanical','Body / Paint','Warranty'] as $t)
                <option value="{{ $t }}" {{ $roType===$t?'selected':'' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" style="padding:7px 18px;background:#dc2626;color:#fff;border:none;border-radius:4px;font-size:13px;font-weight:600;cursor:pointer;">Generate</button>
        <a href="{{ route('sm.reports') }}" style="padding:7px 14px;background:#991b1b;color:#fff;text-decoration:none;border-radius:4px;font-size:13px;font-weight:600;">← Reports</a>
        <button type="button" onclick="window.print()" style="padding:7px 14px;background:#374151;color:#fff;border:none;border-radius:4px;font-size:13px;cursor:pointer;">Print</button>
    </form>
</div>

@php
$totals = ['Lnet'=>0,'Pnet'=>0,'Snet'=>0,'Cnet'=>0,'Total'=>0];
foreach($rows as $r) foreach(['Lnet','Pnet','Snet','Cnet','Total'] as $k) $totals[$k] += $r->$k ?? 0;
@endphp

<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div style="background:#dc2626;padding:10px 16px;display:flex;justify-content:space-between;">
        <span style="color:#fff;font-size:13px;font-weight:600;">{{ count($rows) }} Records</span>
        <span style="color:#fff;font-size:13px;">Total: Rs {{ number_format($totals['Total'],0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead style="background:#f3f4f6;"><tr>
                <th class="px-2 py-2 text-left">Inv#</th><th class="px-2 py-2 text-left">RO#</th>
                <th class="px-2 py-2 text-left">Customer</th><th class="px-2 py-2 text-left">Reg#</th>
                <th class="px-2 py-2 text-left">SA</th><th class="px-2 py-2 text-left">Type</th>
                <th class="px-2 py-2 text-left">Nature</th><th class="px-2 py-2 text-left">Open</th>
                <th class="px-2 py-2 text-left">Close</th><th class="px-2 py-2 text-left">Duration</th>
                <th class="px-2 py-2 text-right">Labor</th><th class="px-2 py-2 text-right">Parts</th>
                <th class="px-2 py-2 text-right">Sub</th><th class="px-2 py-2 text-right">Cons</th>
                <th class="px-2 py-2 text-right font-bold">Total</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $r)
                <tr class="hover:bg-red-50">
                    <td class="px-2 py-1.5 text-gray-400">{{ $r->Invoice_id }}</td>
                    <td style="padding:4px 8px;font-weight:700;color:#dc2626;">{{ $r->ro }}</td>
                    <td class="px-2 py-1.5">{{ $r->Customer_name }}</td>
                    <td class="px-2 py-1.5">{{ $r->Veh_reg_no }}</td>
                    <td class="px-2 py-1.5">{{ $r->SA }}</td>
                    <td class="px-2 py-1.5">{{ $r->RO_type }}</td>
                    <td class="px-2 py-1.5">{{ $r->serv_nature }}</td>
                    <td class="px-2 py-1.5 text-gray-400">{{ $r->open_time }}</td>
                    <td class="px-2 py-1.5 text-gray-400">{{ $r->close_time }}</td>
                    <td class="px-2 py-1.5 font-mono">{{ $r->JobcardTime }}</td>
                    <td class="px-2 py-1.5 text-right">{{ number_format($r->Lnet,0) }}</td>
                    <td class="px-2 py-1.5 text-right">{{ number_format($r->Pnet,0) }}</td>
                    <td class="px-2 py-1.5 text-right">{{ number_format($r->Snet,0) }}</td>
                    <td class="px-2 py-1.5 text-right">{{ number_format($r->Cnet,0) }}</td>
                    <td style="padding:4px 8px;text-align:right;font-weight:700;color:#dc2626;">{{ number_format($r->Total,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="15" class="px-4 py-8 text-center text-gray-400">No data</td></tr>
                @endforelse
                @if(count($rows))
                <tr style="background:#dc2626;color:#fff;font-weight:700;">
                    <td colspan="10" class="px-2 py-2 text-right">Totals:</td>
                    <td class="px-2 py-2 text-right">{{ number_format($totals['Lnet'],0) }}</td>
                    <td class="px-2 py-2 text-right">{{ number_format($totals['Pnet'],0) }}</td>
                    <td class="px-2 py-2 text-right">{{ number_format($totals['Snet'],0) }}</td>
                    <td class="px-2 py-2 text-right">{{ number_format($totals['Cnet'],0) }}</td>
                    <td class="px-2 py-2 text-right">{{ number_format($totals['Total'],0) }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@push('styles')<style>@media print{.no-print{display:none!important}}</style>@endpush
@endsection
