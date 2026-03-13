@extends('layouts.master')
@section('title', 'SM - Upload Frame Info')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Service Manager &mdash; Frame / VIN Upload</h3></div></div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title"><h2>Add Frame No</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.upload-frame.store') }}" class="form-horizontal">
                        @csrf
                        <div class="form-group">
                            <label class="col-md-4 control-label">Frame No <span class="required">*</span></label>
                            <div class="col-md-7"><input type="text" name="frame_no" class="form-control" required style="text-transform:uppercase"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-7">
                                <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title"><h2>Recent Frame List <span class="badge">{{ $frames->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered" id="frameTable">
                        <thead><tr><th>#</th><th>Frame No</th><th>Added By</th><th>Added On</th></tr></thead>
                        <tbody>
                            @forelse($frames as $i => $f)
                            <tr><td>{{ $i+1 }}</td><td>{{ $f->frame_no }}</td><td>{{ $f->added_by }}</td><td>{{ $f->added_on }}</td></tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">No frames yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function(){ $('#frameTable').DataTable({order:[[0,'desc']]}); });</script>
@endpush
@endsection
