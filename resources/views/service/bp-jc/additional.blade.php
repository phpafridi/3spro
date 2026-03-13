@extends('layouts.master')
@section('title', 'BP - Jobcard RO# ' . $jobId)
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Body &amp; Paint &mdash; RO# {{ $jobId }}</h3>
        </div>
        <div class="title_right">
            <a href="{{ route('bp-jc.unclosed') }}" class="btn btn-default pull-right">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    {{-- RO INFO --}}
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Vehicle &amp; Customer Info</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-3"><strong>Registration:</strong> {{ $jobcard->Registration }}</div>
                        <div class="col-md-3"><strong>Variant:</strong> {{ $jobcard->Variant }}</div>
                        <div class="col-md-3"><strong>Customer:</strong> {{ $jobcard->Customer_name }}</div>
                        <div class="col-md-3"><strong>Mobile:</strong> {{ $jobcard->mobile }}</div>
                    </div>
                    <div class="row" style="margin-top:8px;">
                        <div class="col-md-3"><strong>SA:</strong> {{ $jobcard->SA }}</div>
                        <div class="col-md-3"><strong>RO Type:</strong> {{ $jobcard->RO_type }}</div>
                        <div class="col-md-3"><strong>Open Date:</strong> {{ $jobcard->Open_date_time }}</div>
                        <div class="col-md-3"><strong>Mileage:</strong> {{ $jobcard->Mileage }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK ADD BUTTONS --}}
    <div class="row" style="margin-bottom:10px;">
        <div class="col-md-12">
            <a href="{{ route('bp-jc.additional.jobrequest', $jobId) }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Labor
            </a>
            <a href="{{ route('bp-jc.additional.part', $jobId) }}" class="btn btn-info">
                <i class="fa fa-cogs"></i> Add Part
            </a>
            <a href="{{ route('bp-jc.additional.consumable', $jobId) }}" class="btn btn-warning">
                <i class="fa fa-tint"></i> Add Consumable
            </a>
            <a href="{{ route('bp-jc.additional.sublet', $jobId) }}" class="btn btn-default">
                <i class="fa fa-external-link"></i> Add Sublet
            </a>
        </div>
    </div>

    {{-- LABOR --}}
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Labor <span class="badge">{{ $labors->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead><tr><th>Labor</th><th>Type</th><th>Cost</th><th>Status</th><th>Team/Bay</th><th>Entry</th></tr></thead>
                        <tbody>
                            @forelse($labors as $l)
                            <tr>
                                <td>{{ $l->Labor }}</td>
                                <td>{{ $l->type }}</td>
                                <td>{{ number_format($l->cost,0) }}</td>
                                <td>
                                    @if(!$l->status)<span class="label label-default">Pending</span>
                                    @elseif($l->status=='Job Assign')<span class="label label-info">Assigned</span>
                                    @elseif($l->status=='Jobclose')<span class="label label-success">Done</span>
                                    @else<span class="label label-warning">{{ $l->status }}</span>@endif
                                </td>
                                <td>{{ $l->team }} / {{ $l->bay }}</td>
                                <td>{{ $l->entry_time }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No labor added.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- PARTS --}}
    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Parts <span class="badge">{{ $parts->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead><tr><th>Description</th><th>Qty</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($parts as $p)
                            <tr>
                                <td>{{ $p->part_description }}</td>
                                <td>{{ $p->qty }}</td>
                                <td>{{ number_format($p->total,2) }}</td>
                                <td>{{ $p->status=='0' ? 'Pending' : 'Issued' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">No parts.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- CONSUMABLES --}}
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Consumables <span class="badge">{{ $consumbles->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead><tr><th>Description</th><th>Qty</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($consumbles as $c)
                            <tr>
                                <td>{{ $c->cons_description }}</td>
                                <td>{{ $c->qty }}</td>
                                <td>{{ number_format($c->total,2) }}</td>
                                <td>{{ $c->status=='0' ? 'Pending' : 'Issued' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">No consumables.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- SUBLETS --}}
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Sublets <span class="badge">{{ $sublets->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead><tr><th>Sublet</th><th>Type</th><th>Qty</th><th>Unit</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($sublets as $s)
                            <tr>
                                <td>{{ $s->Sublet }}</td><td>{{ $s->type }}</td>
                                <td>{{ $s->qty }}</td>
                                <td>{{ number_format($s->unitprice,2) }}</td>
                                <td>{{ number_format($s->total,2) }}</td>
                                <td>{{ $s->status ?: 'Pending' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No sublets.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
