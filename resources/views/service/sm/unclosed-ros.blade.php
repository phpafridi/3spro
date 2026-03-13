{{-- resources/views/service/sm/unclosed-ros.blade.php --}}
@extends('layouts.master')
@section('title', 'SM - All Unclosed ROs')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; All Unclosed ROs</h3></div>
        <div class="title_right">
            <a href="{{ route('sm.index') }}" class="btn btn-default pull-right"><i class="fa fa-home"></i> Dashboard</a>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Unclosed Job Cards <span class="badge">{{ $jobs->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered table-condensed" id="roTable">
                        <thead>
                            <tr>
                                <th>RO No</th><th>Registration</th><th>Variant</th><th>Customer</th>
                                <th>Mobile</th><th>SA</th><th>RO Type</th><th>Open Date</th><th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $j)
                            @php $hrs = \Carbon\Carbon::parse($j->Open_date_time)->diffInHours(now()); @endphp
                            <tr class="{{ $hrs>48?'danger':($hrs>24?'warning':'') }}">
                                <td><strong>{{ $j->Jobc_id }}</strong></td>
                                <td>{{ $j->Registration }}</td><td>{{ $j->Variant }}</td>
                                <td>{{ $j->Customer_name }}</td><td>{{ $j->mobile }}</td>
                                <td>{{ $j->SA }}</td><td>{{ $j->RO_type }}</td>
                                <td>{{ $j->Open_date_time }}</td>
                                <td>
                                    @if($j->status=='0')<span class="label label-warning">Open</span>
                                    @else<span class="label label-info">In Workshop</span>@endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center text-success">All jobcards are closed!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function(){ $('#roTable').DataTable({order:[[7,'asc']],pageLength:50}); });</script>
@endpush
@endsection
