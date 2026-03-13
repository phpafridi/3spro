@extends('layouts.master')
@section('title', 'SM - Consumable Status')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Consumable Status (In Workshop)</h3></div>
        <div class="title_right"><a href="{{ route('sm.index') }}" class="btn btn-default pull-right"><i class="fa fa-home"></i> Dashboard</a></div>
    </div>
    <div class="clearfix"></div>
    @if($jobcards->isEmpty())
        <div class="alert alert-success">No jobcards in workshop.</div>
    @else
    @foreach($jobcards as $jc)
    @if(isset($consData[$jc->Jobc_id]) && count($consData[$jc->Jobc_id]))
    <div class="row"><div class="col-md-12">
        <div class="x_panel">
            <div class="x_title"><h2>RO# <strong>{{ $jc->Jobc_id }}</strong> &mdash; SA: {{ $jc->SA }}</h2><div class="clearfix"></div></div>
            <div class="x_content">
                <table class="table table-condensed table-bordered">
                    <thead><tr><th>Description</th><th>Qty</th><th>Unit</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($consData[$jc->Jobc_id] as $c)
                        <tr>
                            <td>{{ $c->cons_description }}</td><td>{{ $c->qty }}</td>
                            <td>{{ number_format($c->unitprice,2) }}</td>
                            <td>{{ number_format($c->total,2) }}</td>
                            <td>{{ $c->status=='0'?'Pending':'Issued' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div></div>
    @endif
    @endforeach
    @endif
</div>
@endsection
