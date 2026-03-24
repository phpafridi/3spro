@extends('layouts.master')
@section('title','WYW')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">WYW — {{$year}}</h2>
<div class="no-print" style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:14px;margin-bottom:16px;">
    <form method="GET" style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Year</label>
            <select name="year" style="border:1px solid #d1d5db;border-radius:4px;padding:6px 10px;font-size:13px;">
                @for($y=now()->year;$y>=2018;$y--)<option value="{{$y}}" {{($year??now()->year)==$y?'selected':''}}>{{$y}}</option>@endfor
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Service Nature</label>
            <select name="ro_type" style="border:1px solid #d1d5db;border-radius:4px;padding:6px 10px;font-size:13px;">
                @foreach($roTypes as $t)<option value="{{$t}}" {{$roType===$t?'selected':''}}>{{$t}}</option>@endforeach
            </select>
        </div>
        <button type="submit" style="padding:7px 18px;background:#dc2626;color:#fff;border:none;border-radius:4px;font-size:13px;font-weight:600;cursor:pointer;">Generate</button>
        <a href="{{ route('sm.reports') }}" style="padding:7px 14px;background:#991b1b;color:#fff;text-decoration:none;border-radius:4px;font-size:13px;font-weight:600;">← Reports</a>
    </form>
</div>
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div class="overflow-x-auto"><table class="w-full text-xs">
        <thead style="background:#dc2626;color:#fff;"><tr>
            <th style="padding:8px;text-align:left;">Year</th>
            @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Total'] as $m)
            <th class="px-2 py-2 text-center">{{$m}}</th>
            @endforeach
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rows as $r)
            <tr class="hover:bg-red-50">
                <td style="padding:4px 8px;font-weight:700;color:#dc2626;">{{$r->yr}}</td>
                @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Total'] as $m)
                <td class="px-2 py-1.5 text-center">{{$r->$m}}</td>
                @endforeach
            </tr>
            @empty
            <tr><td colspan="14" class="px-4 py-8 text-center text-gray-400">No data</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
