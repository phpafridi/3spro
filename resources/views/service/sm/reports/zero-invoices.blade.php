@extends('layouts.master')
@section('title','Zero / Low Invoices')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Zero / Low Invoices — {{$from}} to {{$to}}</h2>
@include('service.sm.reports._filter',['showDates'=>true])
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead style="background:#dc2626;color:#fff;"><tr><th class="px-3 py-2 text-left text-xs font-medium uppercase">Inv#</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">RO#</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Type</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Customer</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">SA</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Nature</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Labor</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Parts</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Sub</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Cons</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Total</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Tax</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Date</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rows as $r)
            <tr class="hover:bg-red-50"><td class="px-2 py-1.5 text-xs">{{$r->Invoice_id}}</td><td style="padding:4px 8px;font-weight:700;color:#dc2626;">{{$r->Jobc_id}}</td><td class="px-2 py-1.5 text-xs">{{$r->type}}</td><td class="px-2 py-1.5 text-xs">{{$r->Customer_name}}</td><td class="px-2 py-1.5 text-xs">{{$r->SA}}</td><td class="px-2 py-1.5 text-xs">{{$r->serv_nature}}</td><td class="px-2 py-1.5 text-right">{{number_format($r->Lnet,0)}}</td><td class="px-2 py-1.5 text-right">{{number_format($r->Pnet,0)}}</td><td class="px-2 py-1.5 text-right">{{number_format($r->Snet,0)}}</td><td class="px-2 py-1.5 text-right">{{number_format($r->Cnet,0)}}</td><td style="padding:4px 8px;text-align:right;font-weight:700;color:#dc2626;">{{number_format($r->Total,0)}}</td><td class="px-2 py-1.5 text-right">{{number_format($r->tax,0)}}</td><td class="px-2 py-1.5 text-xs text-gray-400">{{$r->inv_date}}</td></tr>
            @empty
            <tr><td colspan="13" class="px-4 py-8 text-center text-gray-400">No data for selected period</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
