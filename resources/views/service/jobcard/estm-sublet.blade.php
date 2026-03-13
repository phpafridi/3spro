@extends('layouts.master')
@section('title', 'Estimate Sublets - #' . $estmId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Estimate Sublets &mdash; Est# {{ $estmId }}</h3></div>
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
                <div class="x_title"><h2>Add Sublet</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('jobcard.estimate.sublet.store') }}" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $estmId }}">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Description <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="sublet" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Type</label>
                            <div class="col-md-8">
                                <select name="type" class="form-control">
                                    <option>Workshop</option><option>Sublet</option>
                                    <option>Warranty</option><option>Goodwill</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Qty</label>
                            <div class="col-md-8">
                                <input type="number" name="qty" id="qty" class="form-control"
                                       min="1" value="1" onchange="calcTotal()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Unit Price</label>
                            <div class="col-md-8">
                                <input type="number" name="unitprice" id="up" class="form-control"
                                       step="0.01" min="0" value="0" onchange="calcTotal()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Total</label>
                            <div class="col-md-8">
                                <input type="number" name="totalprice" id="total" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-8">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fa fa-plus"></i> Add Sublet
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
                    <h2>Current Sublets <span class="badge">{{ $sublets->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead><tr><th>Sublet</th><th>Type</th><th>Qty</th><th>Unit</th><th>Total</th></tr></thead>
                        <tbody>
                            @forelse($sublets as $s)
                            <tr>
                                <td>{{ $s->Sublet }}</td>
                                <td>{{ $s->type }}</td>
                                <td>{{ $s->qty }}</td>
                                <td>{{ number_format($s->unitprice,2) }}</td>
                                <td>{{ number_format($s->total,2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">No sublets yet.</td></tr>
                            @endforelse
                        </tbody>
                        @if($sublets->count())
                        <tfoot>
                            <tr class="active">
                                <td colspan="4"><strong>Total</strong></td>
                                <td><strong>{{ number_format($sublets->sum('total'), 2) }}</strong></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
            <div class="x_panel">
                <div class="x_title"><h2>Jump To</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <a href="{{ route('jobcard.estimate.labor', $estmId) }}" class="btn btn-primary btn-sm">Labor</a>
                    <a href="{{ route('jobcard.estimate.part', $estmId) }}" class="btn btn-warning btn-sm">Parts</a>
                    <a href="{{ route('jobcard.estimate.consumable', $estmId) }}" class="btn btn-default btn-sm">Consumables</a>
                    <a href="{{ route('jobcard.estimate.ro', $estmId) }}" class="btn btn-info btn-sm">View Full RO</a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function calcTotal() {
    $('#total').val((parseFloat($('#qty').val()||0) * parseFloat($('#up').val()||0)).toFixed(2));
}
</script>
@endpush
@endsection
