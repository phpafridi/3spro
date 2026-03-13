@extends('layouts.master')
@section('title', 'SM - Campaigns')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Service Campaigns</h3></div>
    </div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <div class="row">
        <div class="col-md-5">
            <div class="x_panel">
                <div class="x_title"><h2>Add New Campaign</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.campaigns.store') }}" class="form-horizontal">
                        @csrf
                        <div class="form-group">
                            <label class="col-md-4 control-label">Campaign Name <span class="required">*</span></label>
                            <div class="col-md-7"><input type="text" name="campaign_name" class="form-control" required></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nature</label>
                            <div class="col-md-7">
                                <select name="nature" class="form-control">
                                    <option>Safety Recall</option><option>Service Campaign</option>
                                    <option>Customer Satisfaction Program</option><option>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Valid From</label>
                            <div class="col-md-7"><input type="date" name="cfrom" class="form-control"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Valid To</label>
                            <div class="col-md-7"><input type="date" name="cto" class="form-control"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-7">
                                <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Add Campaign</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="x_panel">
                <div class="x_title"><h2>Campaigns <span class="badge">{{ $campaigns->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-striped table-bordered table-condensed" id="campTable">
                        <thead><tr><th>#</th><th>Name</th><th>Nature</th><th>From</th><th>To</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse($campaigns as $c)
                            <tr>
                                <td>{{ $c->campaign_id }}</td>
                                <td><a href="{{ route('sm.campaign-labour', $c->campaign_id) }}">{{ $c->campaign_name }}</a></td>
                                <td>{{ $c->nature }}</td>
                                <td>{{ $c->c_from }}</td>
                                <td>{{ $c->c_to }}</td>
                                <td>
                                    @if($c->status=='Active')
                                        <span class="label label-success">Active</span>
                                    @else
                                        <span class="label label-default">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('sm.campaign-labour', $c->campaign_id) }}" class="btn btn-xs btn-info"><i class="fa fa-wrench"></i> Labour</a>
                                    <form method="POST" action="{{ route('sm.campaigns.toggle') }}" class="d-inline" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $c->campaign_id }}">
                                        <input type="hidden" name="status" value="{{ $c->status=='Active'?'Inactive':'Active' }}">
                                        <button type="submit" class="btn btn-xs {{ $c->status=='Active'?'btn-warning':'btn-success' }}">
                                            {{ $c->status=='Active'?'Deactivate':'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center">No campaigns yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function(){ $('#campTable').DataTable({order:[[0,'desc']]}); });</script>
@endpush
@endsection
