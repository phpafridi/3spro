@extends('layouts.master')
@section('title', 'BP - Customers / Job Done')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Body &amp; Paint &mdash; Open Jobcards (Job Done)</h3></div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Open Jobcards <span class="badge">{{ $jobs->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered" id="custTable">
                        <thead>
                            <tr>
                                <th>RO No</th><th>Registration</th><th>Variant</th>
                                <th>Customer</th><th>Mobile</th><th>SA</th>
                                <th>Open Date</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $j)
                            <tr>
                                <td><strong>{{ $j->Jobc_id }}</strong></td>
                                <td>{{ $j->Registration }}</td>
                                <td>{{ $j->Variant }}</td>
                                <td>{{ $j->Customer_name }}</td>
                                <td>{{ $j->mobile }}</td>
                                <td>{{ $j->SA }}</td>
                                <td>{{ $j->Open_date_time }}</td>
                                <td>
                                    <a href="{{ route('bp-jc.additional', $j->Jobc_id) }}"
                                       class="btn btn-xs btn-primary">
                                        <i class="fa fa-edit"></i> Manage
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center">No open jobcards.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function() { $('#custTable').DataTable({ order: [[6,'desc']] }); });</script>
@endpush
@endsection
