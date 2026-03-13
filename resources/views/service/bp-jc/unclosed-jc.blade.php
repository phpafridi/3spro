@extends('layouts.master')
@section('title', 'BP - Unclosed Jobcards')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Body &amp; Paint &mdash; Unclosed Jobcards</h3></div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Unclosed JC <span class="badge">{{ $unclosedJobs->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered" id="unclosedTable">
                        <thead>
                            <tr>
                                <th>RO No</th><th>Registration</th><th>Variant</th>
                                <th>Customer</th><th>Mobile</th><th>SA</th>
                                <th>RO Type</th><th>Status</th><th>Open Date</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unclosedJobs as $j)
                            <tr>
                                <td><strong>{{ $j->Jobc_id }}</strong></td>
                                <td>{{ $j->Registration }}</td>
                                <td>{{ $j->Variant }}</td>
                                <td>{{ $j->Customer_name }}</td>
                                <td>{{ $j->mobile }}</td>
                                <td>{{ $j->SA }}</td>
                                <td>{{ $j->RO_type }}</td>
                                <td>
                                    @if($j->status=='0')<span class="label label-warning">Open</span>
                                    @else<span class="label label-info">In Workshop</span>@endif
                                </td>
                                <td>{{ $j->Open_date_time }}</td>
                                <td>
                                    <a href="{{ route('bp-jc.additional', $j->Jobc_id) }}"
                                       class="btn btn-xs btn-primary">
                                        <i class="fa fa-edit"></i> Manage
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="10" class="text-center">No unclosed jobcards.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function() { $('#unclosedTable').DataTable({ order: [[8,'desc']] }); });</script>
@endpush
@endsection
