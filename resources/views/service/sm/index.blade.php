@extends('layouts.master')
@section('title', 'Service Manager Dashboard')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Dashboard</h3></div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    {{-- ALERT COUNTERS --}}
    <div class="row">
        <div class="col-md-3">
            <div class="x_panel" style="border-left:4px solid #e74c3c;">
                <div class="x_content text-center">
                    <h1 style="color:#e74c3c;"><strong>{{ $alertCount }}</strong></h1>
                    <p>Jobs Open &gt; 24 hrs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="x_panel" style="border-left:4px solid #f39c12;">
                <div class="x_content text-center">
                    <h1 style="color:#f39c12;"><strong>{{ $unclosedJobs->count() }}</strong></h1>
                    <p>Total Unclosed ROs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="x_panel" style="border-left:4px solid #3498db;">
                <div class="x_content text-center">
                    <h1 style="color:#3498db;"><strong>{{ $unclosedJobs->where('status','1')->count() }}</strong></h1>
                    <p>In Workshop</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="x_panel" style="border-left:4px solid #2ecc71;">
                <div class="x_content text-center">
                    <h1 style="color:#2ecc71;"><strong>{{ $unclosedJobs->where('status','0')->count() }}</strong></h1>
                    <p>Open / Pending</p>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK LINKS --}}
    <div class="row" style="margin-bottom:15px;">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Quick Actions</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <a href="{{ route('sm.unclosed-ros') }}"        class="btn btn-danger">   <i class="fa fa-list"></i>   Unclosed ROs</a>
                    <a href="{{ route('sm.status-labor') }}"        class="btn btn-info">     <i class="fa fa-wrench"></i>  Labor Status</a>
                    <a href="{{ route('sm.status-parts') }}"        class="btn btn-warning">  <i class="fa fa-cogs"></i>    Parts Status</a>
                    <a href="{{ route('sm.status-sublet') }}"       class="btn btn-default">  <i class="fa fa-external-link"></i> Sublet Status</a>
                    <a href="{{ route('sm.status-consumable') }}"   class="btn btn-default">  <i class="fa fa-tint"></i>    Consumable Status</a>
                    <a href="{{ route('sm.search') }}"              class="btn btn-primary">  <i class="fa fa-search"></i>  Search / Print</a>
                    <a href="{{ route('sm.reports') }}"             class="btn btn-success">  <i class="fa fa-bar-chart"></i> Reports</a>
                    <a href="{{ route('sm.problem-box') }}"         class="btn btn-danger">   <i class="fa fa-exclamation-triangle"></i> Problem Box</a>
                </div>
            </div>
        </div>
    </div>

    {{-- UNCLOSED ROs TABLE --}}
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Unclosed Job Cards <span class="badge">{{ $unclosedJobs->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered table-condensed" id="dashTable">
                        <thead>
                            <tr>
                                <th>RO No</th><th>Registration</th><th>Variant</th>
                                <th>Customer</th><th>SA</th><th>RO Type</th>
                                <th>Open Date</th><th>Hours Open</th><th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unclosedJobs as $j)
                            @php
                                $hoursOpen = \Carbon\Carbon::parse($j->Open_date_time)->diffInHours(now());
                                $rowClass  = $hoursOpen > 48 ? 'danger' : ($hoursOpen > 24 ? 'warning' : '');
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td><strong>{{ $j->Jobc_id }}</strong></td>
                                <td>{{ $j->Registration }}</td>
                                <td>{{ $j->Variant }}</td>
                                <td>{{ $j->Customer_name }}</td>
                                <td>{{ $j->SA }}</td>
                                <td>{{ $j->RO_type }}</td>
                                <td>{{ $j->Open_date_time }}</td>
                                <td>
                                    @if($hoursOpen > 24)
                                        <span class="label label-danger blink">{{ $hoursOpen }}h</span>
                                    @else
                                        {{ $hoursOpen }}h
                                    @endif
                                </td>
                                <td>
                                    @if($j->status=='0')<span class="label label-warning">Open</span>
                                    @else<span class="label label-info">In Workshop</span>@endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.blink { animation: blinker 1s linear infinite; }
@keyframes blinker { 50% { opacity: 0; } }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#dashTable').DataTable({ order: [[6,'asc']], pageLength: 25 });
});
</script>
@endpush
@endsection
