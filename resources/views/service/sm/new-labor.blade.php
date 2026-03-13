@extends('layouts.master')
@section('title', 'SM - New Labor Request')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Service Manager &mdash; New Labor Request</h3></div></div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-5">
            <div class="x_panel">
                <div class="x_title"><h2>Request New Labor</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.new-labor.store') }}" class="form-horizontal">
                        @csrf
                        <div class="form-group">
                            <label class="col-md-3 control-label">Labor <span class="required">*</span></label>
                            <div class="col-md-8"><input type="text" name="labor" class="form-control" required></div>
                        </div>
                        @foreach(['cate1'=>'Category 1','cate2'=>'Category 2','cate3'=>'Category 3','cate4'=>'Category 4','cate5'=>'Category 5'] as $k=>$label)
                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ $label }}</label>
                            <div class="col-md-8"><input type="text" name="{{ $k }}" class="form-control"></div>
                        </div>
                        @endforeach
                        <div class="form-group">
                            <label class="col-md-3 control-label">Remarks</label>
                            <div class="col-md-8"><textarea name="remarks" class="form-control" rows="2"></textarea></div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-8">
                                <button type="submit" class="btn btn-success btn-block"><i class="fa fa-send"></i> Submit Request</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="x_panel">
                <div class="x_title"><h2>Previous Requests <span class="badge">{{ $requests->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered" id="reqTable">
                        <thead><tr><th>Labor</th><th>Cate1</th><th>Cate2</th><th>Remarks</th><th>By</th><th>Date</th></tr></thead>
                        <tbody>
                            @forelse($requests as $r)
                            <tr>
                                <td>{{ $r->labor }}</td><td>{{ $r->cate1 }}</td><td>{{ $r->cate2 }}</td>
                                <td>{{ $r->remarks }}</td><td>{{ $r->who_req }}</td><td>{{ $r->when_req }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No requests yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function(){ $('#reqTable').DataTable({order:[[5,'desc']]}); });</script>
@endpush
@endsection
