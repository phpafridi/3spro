@extends('layouts.master')

@section('title', 'Body & Paint - Pending Jobs')

@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Body &amp; Paint &mdash; Pending Job Requests</h3>
        </div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        {{-- NAVIGATION TABS --}}
        <div class="col-md-12" style="margin-bottom:10px;">
            <a href="{{ route('bp-jc.index') }}" class="btn btn-primary">
                <i class="fa fa-clock-o"></i> Pending
            </a>
            <a href="{{ route('bp-jc.inprogress') }}" class="btn btn-default">
                <i class="fa fa-spinner"></i> In Progress
            </a>
            <a href="{{ route('bp-jc.sublet') }}" class="btn btn-default">
                <i class="fa fa-external-link"></i> Sublets
            </a>
            <a href="{{ route('bp-jc.unclosed') }}" class="btn btn-default">
                <i class="fa fa-list"></i> Unclosed JC
            </a>
            <a href="{{ route('bp-jc.search') }}" class="btn btn-default">
                <i class="fa fa-search"></i> Search
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Pending Jobs <span class="badge badge-warning">{{ $pendingJobs->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-hover table-bordered" id="pendingTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>RO No</th>
                                <th>Labor / Job</th>
                                <th>Registration</th>
                                <th>Variant</th>
                                <th>SA</th>
                                <th>Entry Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingJobs as $i => $job)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $job->RO_no }}</strong></td>
                                <td>{{ $job->Labor }}</td>
                                <td>{{ $job->Registration }}</td>
                                <td>{{ $job->Variant }}</td>
                                <td>{{ $job->SA }}</td>
                                <td>{{ $job->entry_time }}</td>
                                <td>
                                    <a href="{{ route('bp-jc.assign', $job->Labor_id) }}"
                                       class="btn btn-xs btn-success">
                                        <i class="fa fa-check"></i> Assign Job
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <span class="text-success"><i class="fa fa-check-circle"></i> No pending jobs.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    $('#pendingTable').DataTable({ order: [[6,'asc']], pageLength: 25 });
});
</script>
@endpush
@endsection
