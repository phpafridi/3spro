@extends('layouts.master')
@section('title','FFS Rate')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">FFS Rate — {{ $from }} to {{ $to }}</h2>
@include('service.sm.reports._filter',['showDates'=>true])
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px;">
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:20px;text-align:center;">
        <div class="text-xs text-gray-500 uppercase mb-2">FFS Count (this period)</div>
        <div style="font-size:34px;font-weight:700;color:#dc2626;">{{ $ffs->FFS ?? 0 }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:20px;text-align:center;">
        <div class="text-xs text-gray-500 uppercase mb-2">PDS Count (3 months prior)</div>
        <div class="text-4xl font-bold text-gray-700">{{ $pds3 }}</div>
    </div>
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:6px;padding:20px;text-align:center;">
        <div style="font-size:11px;color:#dc2626;text-transform:uppercase;margin-bottom:8px;font-weight:600;">FFS Rate</div>
        <div style="font-size:40px;font-weight:700;color:#dc2626;">{{ $ffsRate }}%</div>
    </div>
</div>
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:16px;font-size:13px;color:#4b5563;">
    <strong>Formula:</strong> FFS Rate = (FFS units in period / PDS units in prior 3 months) × 100
</div>
@endsection
