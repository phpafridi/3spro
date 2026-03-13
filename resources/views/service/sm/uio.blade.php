@extends('layouts.master')
@section('title', 'SM - UIO Update')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Units In Operation (UIO)</h3></div>
    </div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h2>UIO by Year</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <thead><tr><th>Year</th><th>UIO</th><th>Last Updated</th><th>By</th><th>Action</th></tr></thead>
                        <tbody>
                            @foreach($uios as $u)
                            <tr>
                                <td><strong>{{ $u->UIO_Year }}</strong></td>
                                <td>{{ number_format($u->UIO) }}</td>
                                <td>{{ $u->datentime }}</td>
                                <td>{{ $u->user }}</td>
                                <td>
                                    <form method="POST" action="{{ route('sm.uio.update') }}" class="form-inline">
                                        @csrf
                                        <input type="hidden" name="year" value="{{ $u->UIO_Year }}">
                                        <input type="number" name="UIO" class="form-control input-sm" style="width:100px"
                                               value="{{ $u->UIO }}" required>
                                        <button type="submit" class="btn btn-xs btn-warning"><i class="fa fa-save"></i></button>
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
</div>
@endsection
