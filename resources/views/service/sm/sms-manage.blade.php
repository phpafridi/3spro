@extends('layouts.master')
@section('title', 'SM - SMS Templates')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Service Manager &mdash; SMS Template Management</h3></div></div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-10">
            <div class="x_panel">
                <div class="x_title"><h2>SMS Templates <span class="badge">{{ $smsList->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    @foreach($smsList as $sms)
                    <div class="x_panel" style="background:#f9f9f9;margin-bottom:15px;">
                        <div class="x_content">
                            <form method="POST" action="{{ route('sm.sms.update') }}" class="form-horizontal">
                                @csrf
                                <input type="hidden" name="edit_sms_id" value="{{ $sms->id }}">
                                <div class="form-group">
                                    <label class="col-md-2 control-label"><strong>#{{ $sms->id }}</strong> {{ $sms->type }}</label>
                                    <div class="col-md-7">
                                        <textarea name="message" class="form-control" rows="3">{{ $sms->sms_text }}</textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-save"></i> Update</button>
                                        <small class="text-muted">Last: {{ $sms->edit_on }} by {{ $sms->edit_by }}</small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
