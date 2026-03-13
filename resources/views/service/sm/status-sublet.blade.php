@extends('layouts.master')
@section('title', 'SM - Sublet Status')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Sublet Status (In Workshop)</h3></div>
        <div class="title_right"><a href="{{ route('sm.index') }}" class="btn btn-default pull-right"><i class="fa fa-home"></i> Dashboard</a></div>
    </div>
    <div class="clearfix"></div>
    @if($jobcards->isEmpty())
        <div class="alert alert-success">No jobcards in workshop.</div>
    @else
    @foreach($jobcards as $jc)
    @if(isset($subletData[$jc->Jobc_id]) && count($subletData[$jc->Jobc_id]))
    <div class="row"><div class="col-md-12">
        <div class="x_panel">
            <div class="x_title"><h2>RO# <strong>{{ $jc->Jobc_id }}</strong> &mdash; SA: {{ $jc->SA }}</h2><div class="clearfix"></div></div>
            <div class="x_content">
                <table class="table table-condensed table-bordered">
                    <thead><tr><th>Sublet</th><th>Type</th><th>Qty</th><th>Unit</th><th>Total</th><th>Vendor</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($subletData[$jc->Jobc_id] as $s)
                        <tr>
                            <td>{{ $s->Sublet }}</td><td>{{ $s->type }}</td>
                            <td>{{ $s->qty }}</td>
                            <td>{{ number_format($s->unitprice,2) }}</td>
                            <td>{{ number_format($s->total,2) }}</td>
                            <td>{{ $s->Vendor }}</td>
                            <td>{{ $s->status ?: 'Pending' }}</td>
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
