@extends('layouts.master')
@section('title', 'BP - Search')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Body &amp; Paint &mdash; Search Jobcard</h3></div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Search</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="GET" action="{{ route('bp-jc.search') }}">
                        <div class="input-group" style="max-width:500px;">
                            <input type="text" name="q" class="form-control"
                                   value="{{ $query ?? '' }}"
                                   placeholder="Search by Registration, Customer, or RO No...">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($results) && $results->isNotEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Results <span class="badge">{{ $results->count() }}</span></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>RO No</th><th>Registration</th><th>Variant</th>
                                <th>Customer</th><th>Mobile</th><th>SA</th>
                                <th>Status</th><th>Open Date</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $r)
                            <tr>
                                <td><strong>{{ $r->Jobc_id }}</strong></td>
                                <td>{{ $r->Registration }}</td>
                                <td>{{ $r->Variant }}</td>
                                <td>{{ $r->Customer_name }}</td>
                                <td>{{ $r->mobile }}</td>
                                <td>{{ $r->SA }}</td>
                                <td>
                                    @if($r->status=='0')<span class="label label-warning">Open</span>
                                    @elseif($r->status=='1')<span class="label label-info">In Workshop</span>
                                    @else<span class="label label-success">Closed</span>@endif
                                </td>
                                <td>{{ $r->Open_date_time }}</td>
                                <td>
                                    <a href="{{ route('bp-jc.additional', $r->Jobc_id) }}"
                                       class="btn btn-xs btn-primary">
                                        <i class="fa fa-edit"></i> Manage
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @elseif(isset($query) && $query)
    <div class="alert alert-warning">No results found for "{{ $query }}".</div>
    @endif
</div>
@endsection
