@extends('layouts.master')
@section('title','Campaign Report')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Campaign Report — {{ $from }} to {{ $to }}</h2>

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
            <label class="block text-xs font-medium text-gray-600 mb-1">Campaign</label>
            <select name="campaign" class="border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="">All Campaigns</option>
                @foreach($campaigns as $c)
                <option value="{{ $c }}" {{ $campaign===$c?'selected':'' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Cust Source</label>
            <select name="source" class="border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="">All Sources</option>
                @foreach($sources as $s)
                <option value="{{ $s }}" {{ $source===$s?'selected':'' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" style="padding:7px 18px;background:#dc2626;color:#fff;border:none;border-radius:4px;font-size:13px;font-weight:600;cursor:pointer;">Generate</button>
        <a href="{{ route('sm.reports') }}" style="padding:7px 14px;background:#991b1b;color:#fff;text-decoration:none;border-radius:4px;font-size:13px;font-weight:600;">← Reports</a>
        <button type="button" onclick="window.print()" style="padding:7px 14px;background:#374151;color:#fff;border:none;border-radius:4px;font-size:13px;cursor:pointer;">Print</button>
    </form>
</div>

<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:#dc2626;color:#fff;"><tr>
                <th class="px-3 py-2 text-left">RO Type</th>
                <th class="px-3 py-2 text-right">ROs</th>
                <th class="px-3 py-2 text-right">Labor</th>
                <th class="px-3 py-2 text-right">Parts</th>
                <th class="px-3 py-2 text-right">Sublet</th>
                <th class="px-3 py-2 text-right">Consumble</th>
                <th class="px-3 py-2 text-right">Total</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @php $totals=['ROs'=>0,'Labor'=>0,'Parts'=>0,'Sublet'=>0,'Consumble'=>0,'Total'=>0]; @endphp
                @forelse($rows as $r)
                @php foreach(['ROs','Labor','Parts','Sublet','Consumble','Total'] as $k) $totals[$k]+=$r->$k??0; @endphp
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 font-medium">{{ $r->RO_type }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->ROs }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->Labor,0) }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->Parts,0) }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->Sublet,0) }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($r->Consumble,0) }}</td>
                    <td style="padding:6px 12px;text-align:right;font-weight:700;color:#dc2626;">{{ number_format($r->Total,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No data</td></tr>
                @endforelse
                @if(count($rows))
                <tr style="background:#dc2626;font-weight:700;color:#fff;">
                    <td class="px-3 py-2">Total</td>
                    <td class="px-3 py-2 text-right">{{ $totals['ROs'] }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($totals['Labor'],0) }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($totals['Parts'],0) }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($totals['Sublet'],0) }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($totals['Consumble'],0) }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($totals['Total'],0) }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@push('styles')<style>@media print{.no-print{display:none!important}}</style>@endpush
@endsection
