@extends('layouts.master')
@section('title','SA Parts Usage')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">SA Parts Usage — {{ $from }} to {{ $to }}</h2>

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
            <label class="block text-xs font-medium text-gray-600 mb-1">SA</label>
            <select name="sa" class="border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="">All SAs</option>
                @foreach($saList as $s)
                <option value="{{ $s }}" {{ $sa===$s?'selected':'' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" style="padding:7px 18px;background:#dc2626;color:#fff;border:none;border-radius:4px;font-size:13px;font-weight:600;cursor:pointer;">Generate</button>
        <a href="{{ route('sm.reports') }}" style="padding:7px 14px;background:#991b1b;color:#fff;text-decoration:none;border-radius:4px;font-size:13px;font-weight:600;">← Reports</a>
        <button type="button" onclick="window.print()" style="padding:7px 14px;background:#374151;color:#fff;border:none;border-radius:4px;font-size:13px;cursor:pointer;">Print</button>
    </form>
</div>

<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div style="background:#dc2626;padding:10px 16px;"><h3 style="font-weight:600;color:#fff;font-size:13px;">Parts by Subcategory</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:#f9fafb;"><tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Qty Sold</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($query as $i => $r)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->subcategory }}</td>
                    <td style="padding:6px 12px;text-align:right;font-weight:700;color:#dc2626;">{{ number_format($r->t_sale,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('styles')<style>@media print{.no-print{display:none!important}}</style>@endpush
@endsection
