@extends('layouts.master')
@section('title', 'SM - Insurance Companies')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Service Manager &mdash; Sublet Insurance Companies</h3></div></div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title"><h2>Add Vendor</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.insurance.store') }}" class="form-horizontal">
                        @csrf
                        <div class="form-group"><label class="col-md-4 control-label">Company Name <span class="required">*</span></label>
                            <div class="col-md-7"><input type="text" name="jobber" class="form-control" required></div></div>
                        <div class="form-group"><label class="col-md-4 control-label">Contact Person</label>
                            <div class="col-md-7"><input type="text" name="contactperson" class="form-control"></div></div>
                        <div class="form-group"><label class="col-md-4 control-label">Contact</label>
                            <div class="col-md-7"><input type="text" name="contact" class="form-control"></div></div>
                        <div class="form-group"><label class="col-md-4 control-label">Address</label>
                            <div class="col-md-7"><input type="text" name="address" class="form-control"></div></div>
                        <div class="form-group"><label class="col-md-4 control-label">Contact</label>
                            <div class="col-md-7"><input type="text" name="ntn" class="form-control"></div></div>
                        <div class="form-group"><div class="col-md-offset-4 col-md-7">
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Add</button></div></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title"><h2>Insurance Companies <span class="badge">{{ $insurance->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered" id="vendTable">
                        <thead><tr><th>Name</th><th>Contact</th><th>Contact</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($insurance as $v)
                            <tr>
                                <td>{{ $v->company_name }}</td><td>{{ $v->contact }}</td>
                                <td>{{ $v->contact }}</td>
                                <td>@if($v->status=='Active')<span class="label label-success">Active</span>
                                    @else<span class="label label-default">Suspended</span>@endif</td>
                                <td>
                                    <form method="POST" action="{{ route('sm.insurance.toggle') }}" class="form-inline">
                                        @csrf
                                        <input type="hidden" name="c_id" value="{{ $v->c_id }}">
                                        <input type="hidden" name="action" value="{{ $v->status=='Active'?'suspend':'activate' }}">
                                        <button type="submit" class="btn btn-xs {{ $v->status=='Active'?'btn-warning':'btn-success' }}">
                                            {{ $v->status=='Active'?'Suspend':'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">No insurance.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function(){ $('#vendTable').DataTable(); });</script>
@endpush
@endsection
