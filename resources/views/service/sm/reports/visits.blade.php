@extends('layouts.master')
@section('title','Customer Visits')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Customer Visits (Top 500)</h2>
<div class="mb-4 flex gap-3 no-print">
    <a href="{{ route('sm.reports') }}" style="padding:7px 14px;background:#991b1b;color:#fff;text-decoration:none;border-radius:4px;font-size:13px;font-weight:600;">← Reports</a>
    <button onclick="window.print()" style="padding:7px 14px;background:#374151;color:#fff;border:none;border-radius:4px;font-size:13px;cursor:pointer;">Print</button>
</div>
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div style="background:#dc2626;padding:10px 16px;"><h3 style="font-weight:600;color:#fff;font-size:13px;">Vehicle Visit Frequency</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:#f9fafb;"><tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Frame#</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reg#</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mobile</th>
                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Visits</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service Natures</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 text-xs font-mono">{{ $r->Frame_no }}</td>
                    <td style="padding:6px 12px;font-weight:500;color:#dc2626;">{{ $r->Registration }}</td>
                    <td class="px-3 py-2 text-xs">{{ $r->Variant }}</td>
                    <td class="px-3 py-2">{{ $r->Customer_name }}</td>
                    <td class="px-3 py-2 text-xs">{{ $r->mobile }}</td>
                    <td class="px-3 py-2 text-right font-bold">{{ $r->total_visits }}</td>
                    <td class="px-3 py-2 text-xs text-gray-400">{{ $r->natures }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
