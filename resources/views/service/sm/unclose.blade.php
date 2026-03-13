@extends('layouts.master')
@section('title', 'SM - Reopen Jobcard')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Reopen / Unclose Jobcard</h3></div>
    </div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="x_panel">
                <div class="x_title"><h2>Reopen a Closed Jobcard</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        This will reopen a closed jobcard. The original invoice total will be logged. Requires SM password.
                    </div>
                    <form method="POST" action="{{ route('sm.unclose.process') }}" class="form-horizontal">
                        @csrf
                        <div class="form-group">
                            <label class="col-md-4 control-label">RO No <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="text" name="jobc_id" class="form-control" required placeholder="e.g. 12345">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Reason <span class="required">*</span></label>
                            <div class="col-md-6">
                                <textarea name="reason" class="form-control" rows="3" required placeholder="Reason for reopening..."></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">SM Password <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="password" name="passwrd" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-6">
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fa fa-unlock"></i> Reopen Jobcard
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
