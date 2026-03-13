@extends('layouts.master')
@section('title', 'BP - Pending Sublets')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Body &amp; Paint &mdash; Pending Sublets</h3></div>
    </div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Pending Sublets <span class="badge">{{ $sublets->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered" id="subletTable">
                        <thead>
                            <tr>
                                <th>#</th><th>RO No</th><th>Sublet</th><th>Qty</th>
                                <th>Registration</th><th>Variant</th><th>SA</th>
                                <th>Entry Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sublets as $i => $s)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td><strong>{{ $s->RO_no }}</strong></td>
                                <td>{{ $s->Sublet }}</td>
                                <td>{{ $s->qty }}</td>
                                <td>{{ $s->Registration }}</td>
                                <td>{{ $s->Variant }}</td>
                                <td>{{ $s->SA }}</td>
                                <td>{{ $s->entry_datetime }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center">No pending sublets.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function() { $('#subletTable').DataTable(); });</script>
@endpush
@endsection
