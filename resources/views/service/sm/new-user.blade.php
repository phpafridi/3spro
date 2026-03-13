@extends('layouts.master')
@section('title', 'SM - New User')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Service Manager &mdash; Create New User</h3></div></div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
    <div class="row">
        <div class="col-md-7 col-md-offset-2">
            <div class="x_panel">
                <div class="x_title"><h2>New User Registration</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.new-user.store') }}" enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        <div class="form-group"><label class="col-md-3 control-label">Full Name <span class="required">*</span></label>
                            <div class="col-md-7"><input type="text" name="name" class="form-control" required style="text-transform:uppercase"></div></div>
                        <div class="form-group"><label class="col-md-3 control-label">Login ID <span class="required">*</span></label>
                            <div class="col-md-7"><input type="text" name="login_id" class="form-control" required></div></div>
                        <div class="form-group"><label class="col-md-3 control-label">Password <span class="required">*</span></label>
                            <div class="col-md-7"><input type="password" name="password2" class="form-control" required minlength="6"></div></div>
                        <div class="form-group"><label class="col-md-3 control-label">Email</label>
                            <div class="col-md-7"><input type="email" name="email" class="form-control"></div></div>
                        <div class="form-group"><label class="col-md-3 control-label">Phone</label>
                            <div class="col-md-7"><input type="text" name="phone" class="form-control"></div></div>
                        <div class="form-group"><label class="col-md-3 control-label">Department - Position <span class="required">*</span></label>
                            <div class="col-md-7">
                                <select name="position" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="Service-SManager">Service - Service Manager</option>
                                    <option value="Service-SerAdvisor">Service - Service Advisor</option>
                                    <option value="Service-JobController">Service - Job Controller</option>
                                    <option value="Service-body_PaintJC">Service - Body &amp; Paint JC</option>
                                    <option value="Service-IMCc">Service - IMC Coordinator</option>
                                    <option value="Finance-Cashier">Finance - Cashier</option>
                                    <option value="Finance-FManager">Finance - Finance Manager</option>
                                    <option value="Parts-PManager">Parts - Parts Manager</option>
                                    <option value="Parts-DataOperator">Parts - Data Operator</option>
                                    <option value="IT-IT Manager">IT - IT Manager</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-md-3 control-label">Profile Photo</label>
                            <div class="col-md-7"><input type="file" name="fileup" class="form-control" accept=".jpg,.jpeg,.png"></div></div>
                        <div class="form-group"><div class="col-md-offset-3 col-md-7">
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-user-plus"></i> Create User</button></div></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
