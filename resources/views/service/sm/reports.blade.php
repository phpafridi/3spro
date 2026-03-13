@extends('layouts.master')
@section('title', 'SM - Reports')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Service Manager &mdash; Reports</h3></div></div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Report Filters</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="GET" action="{{ route('sm.reports') }}" class="form-inline">
                        <label>From:</label>
                        <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}" style="margin:0 5px">
                        <label>To:</label>
                        <input type="date" name="to_date" class="form-control" value="{{ $toDate }}" style="margin:0 5px">
                        <label>Report:</label>
                        <select name="tab" class="form-control" style="margin:0 5px">
                            <option value="summary"  {{ $tab=='summary'?'selected':'' }}>Summary by SA</option>
                            <option value="labor"    {{ $tab=='labor'?'selected':'' }}>Labor Analysis</option>
                            <option value="parts"    {{ $tab=='parts'?'selected':'' }}>Parts Usage</option>
                            <option value="sa"       {{ $tab=='sa'?'selected':'' }}>SA Performance</option>
                        </select>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-bar-chart"></i> Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($data->isNotEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ ucfirst($tab) }} Report &mdash; {{ $fromDate }} to {{ $toDate }}</h2>
                    <div class="x_content tools">
                        <a href="#" onclick="exportTable()"><i class="fa fa-download"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered table-condensed" id="reportTable">
                        <thead>
                            <tr>
                                @if($tab=='summary')
                                    <th>SA</th><th>Total ROs</th><th>Closed ROs</th>
                                @elseif($tab=='labor')
                                    <th>Labor</th><th>Type</th><th>Count</th><th>Total Revenue</th>
                                @elseif($tab=='parts')
                                    <th>Part Description</th><th>Total Qty</th><th>Total Value</th>
                                @elseif($tab=='sa')
                                    <th>SA</th><th>Total ROs</th><th>Regular</th><th>Campaign</th><th>Warranty</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                @if($tab=='summary')
                                    <td>{{ $row->SA }}</td><td>{{ $row->total }}</td><td>{{ $row->closed }}</td>
                                @elseif($tab=='labor')
                                    <td>{{ $row->Labor }}</td><td>{{ $row->type }}</td>
                                    <td>{{ $row->count }}</td><td>{{ number_format($row->total,0) }}</td>
                                @elseif($tab=='parts')
                                    <td>{{ $row->part_description }}</td>
                                    <td>{{ $row->total_qty }}</td><td>{{ number_format($row->total_value,0) }}</td>
                                @elseif($tab=='sa')
                                    <td>{{ $row->SA }}</td><td>{{ $row->total_ros }}</td>
                                    <td>{{ $row->regular }}</td><td>{{ $row->campaign }}</td><td>{{ $row->warranty }}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @elseif(request()->has('tab'))
        <div class="alert alert-info">No data found for the selected period.</div>
    @endif
</div>
@push('scripts')
<script>
$(document).ready(function(){
    $('#reportTable').DataTable({dom:'Bfrtip', buttons:['excel','pdf','print']});
});
function exportTable(){ $('#reportTable').tableExport({type:'excel'}); }
</script>
@endpush
@endsection
