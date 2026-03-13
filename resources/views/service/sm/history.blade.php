@extends('layouts.master')
@section('title', 'SM - Labor History')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Labor History</h3></div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Search by RO No</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="GET" action="{{ route('sm.history') }}" class="form-inline">
                        <input type="text" name="job_id" class="form-control" value="{{ $jobId ?? '' }}" placeholder="Enter RO No..." required>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Load History</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if($labors->isNotEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Labor History &mdash; RO# {{ $jobId }} <span class="badge">{{ $labors->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered table-condensed">
                        <thead><tr><th>#</th><th>Labor</th><th>Type</th><th>Cost</th><th>Status</th><th>Team</th><th>Bay</th><th>Entry</th><th>End</th></tr></thead>
                        <tbody>
                            @foreach($labors as $l)
                            <tr>
                                <td>{{ $l->Labor_id }}</td><td>{{ $l->Labor }}</td><td>{{ $l->type }}</td>
                                <td>{{ number_format($l->cost,0) }}</td><td>{{ $l->status ?: 'Pending' }}</td>
                                <td>{{ $l->team }}</td><td>{{ $l->bay }}</td>
                                <td>{{ $l->entry_time }}</td><td>{{ $l->end_time }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @elseif($jobId)
        <div class="alert alert-warning">No labor found for RO# {{ $jobId }}.</div>
    @endif
</div>
@endsection
