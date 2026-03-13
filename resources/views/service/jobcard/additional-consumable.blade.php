@extends('layouts.master')

@section('title', 'Add Consumable - RO# ' . $jobId)

@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Add Consumable &mdash; RO# {{ $jobId }}</h3>
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
        <div class="col-md-5">
            <div class="x_panel">
                <div class="x_title"><h2>Add Consumable</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('jobcard.additional.consumable.store') }}" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $jobId }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Description <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="part_description" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Qty <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="number" name="qty" id="qty_input" class="form-control"
                                       min="1" value="1" required onchange="calcTotal()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Unit Price <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="number" name="unitprice" id="up_input" class="form-control"
                                       step="0.01" min="0.01" required onchange="calcTotal()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Total</label>
                            <div class="col-md-8">
                                <input type="number" name="totalprice" id="total_input"
                                       class="form-control" step="0.01" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-8">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fa fa-plus"></i> Add Consumable
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
                    <h2>Current Consumables <span class="badge">{{ $consumbles->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr><th>Description</th><th>Qty</th><th>Unit</th><th>Total</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($consumbles as $c)
                            <tr>
                                <td>{{ $c->cons_description }}</td>
                                <td>{{ $c->qty }}</td>
                                <td>{{ number_format($c->unitprice, 2) }}</td>
                                <td>{{ number_format($c->total, 2) }}</td>
                                <td>{{ $c->status == '0' ? 'Pending' : 'Issued' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">No consumables yet.</td></tr>
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
function calcTotal() {
    $('#total_input').val((parseFloat($('#qty_input').val()||0) * parseFloat($('#up_input').val()||0)).toFixed(2));
}
</script>
@endpush
@endsection
