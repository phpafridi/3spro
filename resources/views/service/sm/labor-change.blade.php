@extends('layouts.master')
@section('title', 'SM - Labor Change')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Labor Price Change <small>(Increase only)</small></h3></div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title"><h2>Search RO</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="GET" action="{{ route('sm.labor-change') }}" class="form-inline">
                        <input type="text" name="jobc_id" class="form-control" value="{{ $jobId ?? '' }}" placeholder="RO No..." required>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($labors->isNotEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Labor for RO# {{ $jobId }} <span class="badge">{{ $labors->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <thead><tr><th>#</th><th>Labor</th><th>Type</th><th>Current Cost</th><th>New Cost</th><th>Action</th></tr></thead>
                        <tbody>
                            @foreach($labors as $l)
                            <tr>
                                <td>{{ $l->Labor_id }}</td>
                                <td>{{ $l->Labor }}</td>
                                <td>{{ $l->type }}</td>
                                <td><strong>{{ number_format($l->cost,0) }}</strong></td>
                                <td>
                                    <form method="POST" action="{{ route('sm.labor-change.update') }}" class="form-inline">
                                        @csrf
                                        <input type="hidden" name="Labor_id" value="{{ $l->Labor_id }}">
                                        <input type="hidden" name="orgcost"  value="{{ $l->cost }}">
                                        <input type="hidden" name="ro_no"    value="{{ $jobId }}">
                                        <input type="number" name="cost" class="form-control input-sm" style="width:100px"
                                               min="{{ $l->cost + 1 }}" value="{{ $l->cost }}" step="1" required>
                                </td>
                                <td>
                                        <button type="submit" class="btn btn-xs btn-warning">
                                            <i class="fa fa-save"></i> Update
                                        </button>
                                    </form>
                                </td>
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
