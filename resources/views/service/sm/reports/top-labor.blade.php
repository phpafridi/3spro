@extends('layouts.master')
@section('title','Top Labor Items')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Top Labor Items — {{$from}} to {{$to}}</h2>
@include('service.sm.reports._filter',['showDates'=>true])
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead style="background:#dc2626;color:#fff;"><tr><th class="px-3 py-2 text-left text-xs font-medium uppercase">Labor</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Category</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Count</th><th class="px-3 py-2 text-left text-xs font-medium uppercase">Cost</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rows as $r)
            <tr class="hover:bg-red-50"><td class="px-3 py-2 font-medium">{{$r->Labor}}</td><td class="px-3 py-2 text-xs text-gray-500">{{$r->Cate1}}</td><td class="px-3 py-2 text-right font-bold">{{$r->counter}}</td><td class="px-3 py-2 text-right">{{number_format($r->Labor_cost,0)}}</td></tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No data for selected period</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
