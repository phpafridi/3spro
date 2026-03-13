@extends('layouts.master')

@section('title', 'Add Labor - RO# ' . $jobId)

@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Add Labor / Job Request &mdash; RO# {{ $jobId }}</h3>
        </div>
        <div class="title_right">
            <a href="{{ route('jobcard.additional', $jobId) }}" class="btn btn-default pull-right">
                <i class="fa fa-arrow-left"></i> Back to Jobcard
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        {{-- ADD FORM --}}
        <div class="col-md-5">
            <div class="x_panel">
                <div class="x_title"><h2>Add New Labor</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('jobcard.additional.jobrequest.store') }}" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $jobId }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Labor / Job <span class="required">*</span></label>
                            <div class="col-md-8">
                                <select name="jobrequest" class="form-control select2" required>
                                    <option value="">-- Select Labor --</option>
                                    @foreach($laborList as $labor)
                                        <option value="{{ $labor->Labor }}">{{ $labor->Labor }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Type <span class="required">*</span></label>
                            <div class="col-md-8">
                                <select name="type" id="labor_type" class="form-control" required onchange="togglePrice(this)">
                                    <option value="Workshop">Workshop</option>
                                    <option value="Sublet">Sublet</option>
                                    <option value="Warranty">Warranty</option>
                                    <option value="Goodwill">Goodwill</option>
                                    <option value="Campaign">Campaign</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="price_row">
                            <label class="col-md-4 control-label">Price</label>
                            <div class="col-md-8">
                                <input type="number" name="price" id="price_input" class="form-control"
                                       step="0.01" min="0" value="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Reason</label>
                            <div class="col-md-8">
                                <input type="text" name="reason" class="form-control"
                                       placeholder="(for warranty/goodwill)">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-8">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fa fa-plus"></i> Add Labor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- EXISTING LABOR TABLE --}}
        <div class="col-md-7">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Current Labor List <span class="badge">{{ $labors->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr><th>Labor</th><th>Type</th><th>Cost</th><th>Status</th><th>Added</th></tr>
                        </thead>
                        <tbody>
                            @forelse($labors as $l)
                            <tr>
                                <td>{{ $l->Labor }}</td>
                                <td>{{ $l->type }}</td>
                                <td>{{ number_format($l->cost, 0) }}</td>
                                <td>{{ $l->status ?: 'Pending' }}</td>
                                <td>{{ $l->Additional ? '<span class="label label-info">Additional</span>' : '' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">No labor added yet.</td></tr>
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
function togglePrice(sel) {
    if (sel.value === 'Workshop') {
        $('#price_row').show();
    } else {
        $('#price_row').hide();
        $('#price_input').val(0);
    }
}
$(document).ready(function() {
    togglePrice(document.getElementById('labor_type'));
    $('.select2').select2();
});
</script>
@endpush
@endsection
