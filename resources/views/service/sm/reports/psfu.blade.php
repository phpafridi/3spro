@extends('layouts.master')
@section('title','PSFU Graph')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">PSFU — {{$year}}</h2>
@include('service.sm.reports._filter',['showYear'=>true,'showDates'=>false])
<div class="space-y-5">
    @foreach([['Closed ROs',$closed,'#dc2626'],['PSFU Calls',$psfu,'#2563eb']] as [$lbl,$data,$bg])
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
        <div style="background:{{$bg}};padding:10px 16px;"><h3 style="font-weight:600;color:#fff;font-size:13px;">{{$lbl}}</h3></div>
        <div class="overflow-x-auto"><table class="w-full text-xs">
            <thead style="background:#f3f4f6;"><tr>
                <th class="px-2 py-2 text-left">Year</th>
                @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Total'] as $m)
                <th class="px-2 py-2 text-center">{{$m}}</th>
                @endforeach
            </tr></thead>
            <tbody>
                @foreach($data as $r)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td style="padding:4px 8px;font-weight:700;color:#dc2626;">{{$r->yr}}</td>
                    @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Total'] as $m)
                    <td class="px-2 py-1.5 text-center">{{$r->$m}}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table></div>
    </div>
    @endforeach
</div>
@endsection
