@extends('layouts.master')
@section('title', 'FFS Units')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">FFS Units</h2>
<p class="text-sm text-gray-500 mb-4">First Free Service units by year</p>
@include('service.sm.reports._filter', ['showYear'=>true, 'showDates'=>false])
@if(isset($rows) && count($rows))
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead style="background:#dc2626;color:#fff;">
                <tr>
                    <th class="px-2 py-2 text-left">Year</th>
                    <th class="px-2 py-2">Jan</th><th class="px-2 py-2">Feb</th><th class="px-2 py-2">Mar</th><th class="px-2 py-2">Apr</th><th class="px-2 py-2">May</th><th class="px-2 py-2">Jun</th><th class="px-2 py-2">Jul</th><th class="px-2 py-2">Aug</th><th class="px-2 py-2">Sep</th><th class="px-2 py-2">Oct</th><th class="px-2 py-2">Nov</th><th class="px-2 py-2">Dec</th><th class="px-2 py-2">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($rows as $r)
                <tr class="hover:bg-red-50">
                    <td style="padding:4px 8px;font-weight:700;color:#dc2626;">{{$r->yr}}</td>
                    <td class="px-2 py-1.5 text-center">{{$r->Jan}}</td><td class="px-2 py-1.5 text-center">{{$r->Feb}}</td><td class="px-2 py-1.5 text-center">{{$r->Mar}}</td><td class="px-2 py-1.5 text-center">{{$r->Apr}}</td><td class="px-2 py-1.5 text-center">{{$r->May}}</td><td class="px-2 py-1.5 text-center">{{$r->Jun}}</td><td class="px-2 py-1.5 text-center">{{$r->Jul}}</td><td class="px-2 py-1.5 text-center">{{$r->Aug}}</td><td class="px-2 py-1.5 text-center">{{$r->Sep}}</td><td class="px-2 py-1.5 text-center">{{$r->Oct}}</td><td class="px-2 py-1.5 text-center">{{$r->Nov}}</td><td class="px-2 py-1.5 text-center">{{$r->Dec}}</td><td class="px-2 py-1.5 text-center">{{$r->Total}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-white rounded p-8 text-center text-gray-400">No data found</div>
@endif
@endsection
