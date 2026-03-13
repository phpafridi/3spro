@extends('layouts.master')
@section('title', 'BP - Parts Pending Issue')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Body &amp; Paint &mdash; Parts Pending Issue</h3></div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Pending Parts <span class="badge">{{ $pendingParts->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered" id="partTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>RO No</th>
                                <th>Part Description</th>
                                <th>Qty</th>
                                <th>Registration</th>
                                <th>SA</th>
                                <th>Entry Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingParts as $i => $p)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td><strong>{{ $p->RO_no }}</strong></td>
                                <td>{{ $p->part_description }}</td>
                                <td>{{ $p->qty }}</td>
                                <td>{{ $p->Registration }}</td>
                                <td>{{ $p->SA }}</td>
                                <td>{{ $p->entry_datetime }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center">No pending parts.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function() { $('#partTable').DataTable({ order: [[6,'asc']] }); });</script>
@endpush
@endsection
