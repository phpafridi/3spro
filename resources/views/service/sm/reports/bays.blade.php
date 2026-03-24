@extends('layouts.master')
@section('title','Bay Utilization')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Bay Utilization — {{ $from }} to {{ $to }}</h2>
@include('service.sm.reports._filter',['showDates'=>true])
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:16px;text-align:center;">
        <div class="text-xs text-gray-500 uppercase mb-1">Mechanical Bays</div>
        <div style="font-size:28px;font-weight:700;color:#dc2626;">{{ $mechBays }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:16px;text-align:center;">
        <div class="text-xs text-gray-500 uppercase mb-1">B&P Bays</div>
        <div style="font-size:28px;font-weight:700;color:#dc2626;">{{ $bpBays }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:16px;text-align:center;">
        <div class="text-xs text-gray-500 uppercase mb-1">Working Days</div>
        <div style="font-size:28px;font-weight:700;color:#374151;">{{ $workDays }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:16px;text-align:center;">
        <div class="text-xs text-gray-500 uppercase mb-1">Total Jobs Closed</div>
        <div style="font-size:28px;font-weight:700;color:#374151;">{{ $bayUtil }}</div>
    </div>
</div>
@if($workDays > 0 && $mechBays > 0)
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:16px;">
    <h3 class="font-semibold text-gray-700 mb-3">Utilization Calculation</h3>
    <div class="text-sm text-gray-600 space-y-1">
        <div>Mechanical Bay Utilization: <strong style="color:#dc2626;">{{ round($bayUtil / ($mechBays * $workDays) * 100, 1) }}%</strong>
            ({{ $bayUtil }} jobs / {{ $mechBays }} bays × {{ $workDays }} days)</div>
    </div>
</div>
@endif
@endsection
