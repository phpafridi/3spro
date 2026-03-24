@extends('layouts.master')
@section('title', ($module ?? 'Module') . ' — Coming Soon')
@section('content')
<div style="text-align:center;padding:80px 20px;">
    <div style="font-size:60px;margin-bottom:16px;">🚧</div>
    <h2 style="font-size:24px;font-weight:700;color:#374151;margin-bottom:8px;">{{ $module ?? 'Module' }}</h2>
    <p style="color:#6b7280;font-size:15px;">This module has not been converted to Laravel yet.</p>
    <a href="{{ url()->previous() }}" style="display:inline-block;margin-top:20px;padding:8px 20px;background:#dc2626;color:#fff;border-radius:4px;text-decoration:none;font-size:14px;">← Go Back</a>
</div>
@endsection
