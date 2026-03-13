@extends('layouts.master')
@section('title', 'Estimate RO - #' . $estimate->est_id)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Estimate RO &mdash; Est# {{ $estimate->est_id }}</h3></div>
        <div class="title_right">
            <a href="{{ route('jobcard.unclosed-estimates') }}" class="btn btn-default pull-right">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    {{-- ESTIMATE HEADER --}}
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Estimate Details</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-3"><strong>Estimate Type:</strong> {{ $estimate->estimate_type }}</div>
                        <div class="col-md-3"><strong>Customer:</strong> {{ $estimate->Customer_name }}</div>
                        <div class="col-md-3"><strong>Mobile:</strong> {{ $estimate->mobile }}</div>
                        <div class="col-md-3"><strong>Registration:</strong> {{ $estimate->Registration }}</div>
                    </div>
                    <div class="row" style="margin-top:8px;">
                        <div class="col-md-3"><strong>Variant:</strong> {{ $estimate->Variant }}</div>
                        <div class="col-md-3"><strong>Payment Mode:</strong> {{ $estimate->payment_mode }}</div>
                        <div class="col-md-3"><strong>Est. Delivery:</strong> {{ $estimate->est_delivery }}</div>
                        <div class="col-md-3"><strong>Created:</strong> {{ $estimate->entry_datetime }}</div>
                    </div>
                    @if($estimate->insur_company)
                    <div class="row" style="margin-top:8px;">
                        <div class="col-md-3"><strong>Insurance Co:</strong> {{ $estimate->insur_company }}</div>
                        <div class="col-md-3"><strong>Surveyor:</strong> {{ $estimate->surv_name }}</div>
                        <div class="col-md-3"><strong>Surv. Type:</strong> {{ $estimate->surv_type }}</div>
                        <div class="col-md-3"><strong>Surv. Contact:</strong> {{ $estimate->sur_cont }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT BUTTONS --}}
    <div class="row" style="margin-bottom:10px;">
        <div class="col-md-12">
            <a href="{{ route('jobcard.estimate.labor', $estimate->est_id) }}" class="btn btn-primary">
                <i class="fa fa-wrench"></i> Labor
            </a>
            <a href="{{ route('jobcard.estimate.part', $estimate->est_id) }}" class="btn btn-warning">
                <i class="fa fa-cogs"></i> Parts
            </a>
            <a href="{{ route('jobcard.estimate.consumable', $estimate->est_id) }}" class="btn btn-default">
                <i class="fa fa-tint"></i> Consumables
            </a>
            <a href="{{ route('jobcard.estimate.sublet', $estimate->est_id) }}" class="btn btn-default">
                <i class="fa fa-external-link"></i> Sublets
            </a>
        </div>
    </div>

    {{-- COST SUMMARY --}}
    @php
        $laborTotal    = $labors->sum('cost');
        $partsTotal    = $parts->sum('total');
        $consTotal     = $consumbles->sum('total');
        $subletTotal   = $sublets->sum('total');
        $grandTotal    = $laborTotal + $partsTotal + $consTotal + $subletTotal;
    @endphp

    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title"><h2>Cost Summary</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-condensed">
                        <tr><td>Labor</td><td class="text-right">{{ number_format($laborTotal, 2) }}</td></tr>
                        <tr><td>Parts</td><td class="text-right">{{ number_format($partsTotal, 2) }}</td></tr>
                        <tr><td>Consumables</td><td class="text-right">{{ number_format($consTotal, 2) }}</td></tr>
                        <tr><td>Sublets</td><td class="text-right">{{ number_format($subletTotal, 2) }}</td></tr>
                        <tr class="active">
                            <td><strong>Grand Total</strong></td>
                            <td class="text-right"><strong>{{ number_format($grandTotal, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- LABOR --}}
            <div class="x_panel">
                <div class="x_title">
                    <h2>Labor <span class="badge">{{ $labors->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead><tr><th>Labor</th><th class="text-right">Cost</th></tr></thead>
                        <tbody>
                            @forelse($labors as $l)
                            <tr>
                                <td>{{ $l->Labor }}</td>
                                <td class="text-right">{{ number_format($l->cost, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-muted text-center">None</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PARTS --}}
            <div class="x_panel">
                <div class="x_title">
                    <h2>Parts <span class="badge">{{ $parts->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead><tr><th>Description</th><th>Qty</th><th class="text-right">Total</th></tr></thead>
                        <tbody>
                            @forelse($parts as $p)
                            <tr>
                                <td>{{ $p->part_description }}</td>
                                <td>{{ $p->qty }}</td>
                                <td class="text-right">{{ number_format($p->total, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-muted text-center">None</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- CONSUMABLES --}}
            <div class="x_panel">
                <div class="x_title">
                    <h2>Consumables <span class="badge">{{ $consumbles->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead><tr><th>Description</th><th>Qty</th><th class="text-right">Total</th></tr></thead>
                        <tbody>
                            @forelse($consumbles as $c)
                            <tr>
                                <td>{{ $c->part_description }}</td>
                                <td>{{ $c->qty }}</td>
                                <td class="text-right">{{ number_format($c->total, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-muted text-center">None</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SUBLETS --}}
            <div class="x_panel">
                <div class="x_title">
                    <h2>Sublets <span class="badge">{{ $sublets->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-condensed table-bordered">
                        <thead><tr><th>Sublet</th><th>Type</th><th>Qty</th><th class="text-right">Total</th></tr></thead>
                        <tbody>
                            @forelse($sublets as $s)
                            <tr>
                                <td>{{ $s->Sublet }}</td>
                                <td>{{ $s->type }}</td>
                                <td>{{ $s->qty }}</td>
                                <td class="text-right">{{ number_format($s->total, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-muted text-center">None</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
