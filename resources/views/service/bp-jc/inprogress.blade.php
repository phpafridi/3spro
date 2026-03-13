{{-- resources/views/service/bp-jc/inprogress.blade.php --}}
@extends('layouts.master')
@section('title', 'BP - In Progress Jobs')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Body &amp; Paint &mdash; In Progress Jobs</h3></div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    {{-- Job Done Form --}}
    <form method="POST" action="{{ route('bp-jc.job-done') }}" id="jobDoneForm">
        @csrf
        <input type="hidden" name="Labor_id" id="done_labor_id">
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>In Progress <span class="badge">{{ $inprogressJobs->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered" id="ipTable">
                        <thead>
                            <tr>
                                <th>#</th><th>RO No</th><th>Labor</th><th>Team</th>
                                <th>Bay</th><th>Registration</th><th>SA</th>
                                <th>Assign Time</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inprogressJobs as $i => $job)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td><strong>{{ $job->RO_no }}</strong></td>
                                <td>{{ $job->Labor }}</td>
                                <td>{{ $job->team }}</td>
                                <td>{{ $job->bay }}</td>
                                <td>{{ $job->Registration }}</td>
                                <td>{{ $job->SA }}</td>
                                <td>{{ $job->Assign_time }}</td>
                                <td>
                                    <button class="btn btn-xs btn-success done-btn"
                                            data-id="{{ $job->Labor_id }}">
                                        <i class="fa fa-check"></i> Job Done
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center">No jobs in progress.</td></tr>
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
    $('#ipTable').DataTable({ order: [[7,'asc']] });
    $('.done-btn').on('click', function() {
        if (!confirm('Mark this job as Done?')) return;
        $('#done_labor_id').val($(this).data('id'));
        $('#jobDoneForm').submit();
    });
});
</script>
@endpush
@endsection
