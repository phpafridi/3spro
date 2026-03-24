@extends('layouts.master')
@section('title','Warranty Report')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Warranty Report — {{$from}} to {{$to}}</h2>
@include('service.sm.reports._filter',['showDates'=>true])
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead style="background:#dc2626;color:#fff;"><tr><th class="px-3 py-2 text-left text-xs font-medium uppercase">RO#</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Customer</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">SA</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">WC No</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Status</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Frame#</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Reg#</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Total</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Claim Date</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rows as $r)
            <tr class="hover:bg-red-50"><td style="padding:6px 12px;font-weight:700;color:#dc2626;">{{$r->Jobc_id}}</td><td class="px-3 py-2">{{$r->Customer_name}}</td><td class="px-3 py-2">{{$r->SA}}</td><td class="px-3 py-2">{{$r->wc_no}}</td><td class="px-3 py-2"><span class="px-2 py-0.5 rounded text-xs {{ $r->w_status=="Approved"?"bg-green-100 text-green-700":($r->w_status=="Denied"?"bg-red-100 text-red-700":"bg-yellow-100 text-yellow-700") }}">{{$r->w_status}}</span></td><td class="px-3 py-2 text-xs font-mono">{{$r->Frame_no}}</td><td class="px-3 py-2">{{$r->Registration}}</td><td class="px-3 py-2 font-bold">{{number_format($r->Total,0)}}</td><td class="px-3 py-2 text-xs text-gray-400">{{$r->claim_date}}</td></tr>
            @empty
            <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">No data for selected period</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
