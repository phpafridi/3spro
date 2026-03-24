@extends('layouts.master')
@section('title','Sublet Profit')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Sublet Profit — {{$from}} to {{$to}}</h2>
@include('service.sm.reports._filter',['showDates'=>true])
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead style="background:#dc2626;color:#fff;"><tr><th class="px-3 py-2 text-left text-xs font-medium uppercase">Sublet ID</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">RO#</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Description</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Total Charged</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Vendor</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Vendor Price</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Logistics</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Profit</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Date</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rows as $r)
            <tr class="hover:bg-red-50"><td class="px-3 py-2 text-xs text-gray-500">{{$r->sublet_id}}</td><td style="padding:6px 12px;font-weight:700;color:#dc2626;">{{$r->RO_no}}</td><td class="px-3 py-2">{{$r->Sublet}}</td><td class="px-3 py-2 text-right">{{number_format($r->total,0)}}</td><td class="px-3 py-2 text-xs">{{$r->Vendor}}</td><td class="px-3 py-2 text-right">{{number_format($r->Vendor_price,0)}}</td><td class="px-3 py-2 text-right">{{number_format($r->logistics,0)}}</td><td style="padding:6px 12px;text-align:right;font-weight:700;color:{{ ($r->total-$r->Vendor_price-$r->logistics)>=0 ? '#16a34a' : '#ef4444' }}"">{{number_format($r->total-$r->Vendor_price-$r->logistics,0)}}</td><td class="px-3 py-2 text-xs text-gray-400">{{$r->close_date}}</td></tr>
            @empty
            <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">No data for selected period</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
