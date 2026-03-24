@extends('layouts.master')
@section('title','Parts Timings')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Parts Timings — {{$from}} to {{$to}}</h2>
@include('service.sm.reports._filter',['showDates'=>true])
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead style="background:#dc2626;color:#fff;"><tr><th class="px-3 py-2 text-left text-xs font-medium uppercase">RO#</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Customer</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Reg#</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">SA</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Part#</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Description</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Requested</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Issued</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">By</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Minutes</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rows as $r)
            <tr class="hover:bg-red-50"><td style="padding:4px 8px;font-weight:700;color:#dc2626;">{{$r->Jobc_id}}</td><td class="px-2 py-1.5 text-xs">{{$r->Customer_name}}</td><td class="px-2 py-1.5 text-xs">{{$r->Veh_reg_no}}</td><td class="px-2 py-1.5 text-xs">{{$r->SA}}</td><td class="px-2 py-1.5 text-xs font-mono">{{$r->part_number}}</td><td class="px-2 py-1.5 text-xs">{{$r->part_description}}</td><td class="px-2 py-1.5 text-xs text-gray-500">{{$r->requested}}</td><td class="px-2 py-1.5 text-xs text-gray-500">{{$r->issued}}</td><td class="px-2 py-1.5 text-xs">{{$r->issue_by}}</td><td style="padding:4px 8px;text-align:right;font-weight:700;color:{{ ($r->minutes??999)>60 ? '#ef4444' : '#16a34a' }}"">{{$r->minutes??'—'}}</td></tr>
            @empty
            <tr><td colspan="10" class="px-4 py-8 text-center text-gray-400">No data for selected period</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
