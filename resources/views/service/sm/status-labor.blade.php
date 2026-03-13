@extends('layouts.master')
@section('title', 'SM - Labor Status')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Labor Status (In Workshop)</h3></div>
        <div class="title_right">
            <a href="{{ route('sm.index') }}" class="btn btn-default pull-right"><i class="fa fa-home"></i> Dashboard</a>
        </div>
    </div>
    <div class="clearfix"></div>

    @if($jobcards->isEmpty())
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> No jobcards currently in workshop.</div>
    @else
    @foreach($jobcards as $jc)
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        RO# <strong>{{ $jc->Jobc_id }}</strong>
                        &nbsp;&mdash;&nbsp; SA: {{ $jc->SA }}
                        &nbsp;&mdash;&nbsp; {{ $jc->Open_date_time }}
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if(isset($laborData[$jc->Jobc_id]) && count($laborData[$jc->Jobc_id]))
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>#</th><th>Labor</th><th>Type</th><th>Cost</th>
                                <th>Status</th><th>Team</th><th>Bay</th><th>Entry</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laborData[$jc->Jobc_id] as $l)
                            <tr>
                                <td>{{ $l->Labor_id }}</td>
                                <td>{{ $l->Labor }}</td>
                                <td>{{ $l->type }}</td>
                                <td>{{ number_format($l->cost, 0) }}</td>
                                <td>
                                    @if(!$l->status)<span class="label label-default">Pending</span>
                                    @elseif($l->status=='Job Assign')<span class="label label-info">Assigned</span>
                                    @elseif($l->status=='Jobclose')<span class="label label-success">Done</span>
                                    @elseif($l->status=='Job Stopage')<span class="label label-danger">Stopped</span>
                                    @elseif($l->status=='Job Not Done')<span class="label label-warning">Not Done</span>
                                    @else<span class="label label-default">{{ $l->status }}</span>@endif
                                </td>
                                <td>{{ $l->team }}</td>
                                <td>{{ $l->bay }}</td>
                                <td>{{ $l->entry_time }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        <p class="text-muted">No labor added yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
