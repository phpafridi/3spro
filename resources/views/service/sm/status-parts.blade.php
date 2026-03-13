@extends('layouts.master')
@section('title', 'SM - Parts Status')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Parts Status (In Workshop)</h3></div>
        <div class="title_right"><a href="{{ route('sm.index') }}" class="btn btn-default pull-right"><i class="fa fa-home"></i> Dashboard</a></div>
    </div>
    <div class="clearfix"></div>
    @if($jobcards->isEmpty())
        <div class="alert alert-success">No jobcards in workshop.</div>
    @else
    @foreach($jobcards as $jc)
    @if(isset($partsData[$jc->Jobc_id]) && count($partsData[$jc->Jobc_id]))
    <div class="row"><div class="col-md-12">
        <div class="x_panel">
            <div class="x_title"><h2>RO# <strong>{{ $jc->Jobc_id }}</strong> &mdash; SA: {{ $jc->SA }}</h2><div class="clearfix"></div></div>
            <div class="x_content">
                <table class="table table-condensed table-bordered">
                    <thead><tr><th>Part</th><th>Qty</th><th>Unit Price</th><th>Total</th><th>Status</th><th>Entry</th></tr></thead>
                    <tbody>
                        @foreach($partsData[$jc->Jobc_id] as $p)
                        <tr>
                            <td>{{ $p->part_description }}</td><td>{{ $p->qty }}</td>
                            <td>{{ number_format($p->unitprice,2) }}</td>
                            <td>{{ number_format($p->total,2) }}</td>
                            <td>{{ $p->status=='0'?'Pending':'Issued' }}</td>
                            <td>{{ $p->entry_datetime }}</td>
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
