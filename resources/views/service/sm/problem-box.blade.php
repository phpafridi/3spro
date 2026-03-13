@extends('layouts.master')
@section('title', 'SM - Problem Box')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Service Manager &mdash; Problem Box</h3></div></div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Stopped / Not Done Jobs <span class="badge badge-danger">{{ $problems->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if($problems->isEmpty())
                        <div class="alert alert-success"><i class="fa fa-check-circle"></i> No problems reported.</div>
                    @else
                    <table class="table table-bordered" id="probTable">
                        <thead><tr><th>RO No</th><th>Labor</th><th>Status</th><th>Registration</th><th>Variant</th><th>SA</th><th>Entry</th><th>Action</th></tr></thead>
                        <tbody>
                            @foreach($problems as $p)
                            <tr>
                                <td><strong>{{ $p->RO_no }}</strong></td>
                                <td>{{ $p->Labor }}</td>
                                <td>
                                    @if($p->status=='Job Stopage')
                                        <span class="label label-danger">Stopped</span>
                                    @else
                                        <span class="label label-warning">Not Done</span>
                                    @endif
                                </td>
                                <td>{{ $p->Registration }}</td>
                                <td>{{ $p->Variant }}</td>
                                <td>{{ $p->SA }}</td>
                                <td>{{ $p->entry_time }}</td>
                                <td>
                                    <form method="POST" action="{{ route('sm.problem-box') }}" class="form-inline">
                                        @csrf
                                        <input type="hidden" name="labor_id" value="{{ $p->Labor_id }}">
                                        <select name="action" class="form-control input-sm">
                                            <option value="Job Assign">Re-Assign</option>
                                            <option value="Jobclose">Close Job</option>
                                            <option value="Job Not Done">Not Done</option>
                                        </select>
                                        <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Apply</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function(){ $('#probTable').DataTable(); });</script>
@endpush
@endsection
