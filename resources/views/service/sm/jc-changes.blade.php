@extends('layouts.master')
@section('title', 'SM - JC Changes')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; JC Changes Viewer</h3></div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Search by RO No</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="GET" action="{{ route('sm.jc-changes') }}" class="form-inline">
                        <input type="text" name="jobc_id" class="form-control" value="{{ $jobId ?? '' }}" placeholder="RO No..." required>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> View Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if($changes->isNotEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Labor for RO# {{ $jobId }}</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered table-condensed">
                        <thead><tr><th>#</th><th>Labor</th><th>Type</th><th>Cost</th><th>Status</th><th>Additional</th><th>Entry</th></tr></thead>
                        <tbody>
                            @foreach($changes as $c)
                            <tr>
                                <td>{{ $c->Labor_id }}</td><td>{{ $c->Labor }}</td><td>{{ $c->type }}</td>
                                <td>{{ number_format($c->cost,0) }}</td><td>{{ $c->status ?: 'Pending' }}</td>
                                <td>{{ $c->Additional ? 'Yes' : 'No' }}</td><td>{{ $c->entry_time }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @elseif($jobId)
        <div class="alert alert-warning">No data for RO# {{ $jobId }}.</div>
    @endif
</div>
@endsection
