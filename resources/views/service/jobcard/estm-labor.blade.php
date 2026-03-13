@extends('layouts.master')
@section('title', 'Estimate Labor - #' . $estmId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Estimate Labor &mdash; Est# {{ $estmId }}</h3></div>
        <div class="title_right">
            <a href="{{ route('jobcard.unclosed-estimates') }}" class="btn btn-default pull-right">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="row">
        <div class="col-md-5">
            <div class="x_panel">
                <div class="x_title"><h2>Add Labor</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('jobcard.estimate.labor.store') }}" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $estmId }}">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Labor <span class="required">*</span></label>
                            <div class="col-md-8">
                                <select name="jobrequest" class="form-control select2" required>
                                    <option value="">-- Select --</option>
                                    @foreach($laborList as $l)
                                        <option value="{{ $l->Labor }}">{{ $l->Labor }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Price</label>
                            <div class="col-md-8">
                                <input type="number" name="price" class="form-control" step="0.01" min="0" value="0">
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
        <div class="col-md-7">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Current Labor <span class="badge">{{ $labors->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead><tr><th>#</th><th>Labor</th><th>Cost</th></tr></thead>
                        <tbody>
                            @forelse($labors as $l)
                            <tr>
                                <td>{{ $l->est_lab_id }}</td>
                                <td>{{ $l->Labor }}</td>
                                <td>{{ number_format($l->cost, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">No labor yet.</td></tr>
                            @endforelse
                        </tbody>
                        @if($labors->count())
                        <tfoot>
                            <tr class="active">
                                <td colspan="2"><strong>Total</strong></td>
                                <td><strong>{{ number_format($labors->sum('cost'), 2) }}</strong></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
            <div class="x_panel">
                <div class="x_title"><h2>Jump To</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <a href="{{ route('jobcard.estimate.part', $estmId) }}" class="btn btn-warning btn-sm">Parts</a>
                    <a href="{{ route('jobcard.estimate.consumable', $estmId) }}" class="btn btn-default btn-sm">Consumables</a>
                    <a href="{{ route('jobcard.estimate.sublet', $estmId) }}" class="btn btn-default btn-sm">Sublets</a>
                    <a href="{{ route('jobcard.estimate.ro', $estmId) }}" class="btn btn-info btn-sm">View Full RO</a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function() { $('.select2').select2(); });</script>
@endpush
@endsection
