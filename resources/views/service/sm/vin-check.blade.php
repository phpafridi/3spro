@extends('layouts.master')
@section('title', 'SM - VIN Check')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; VIN Check</h3></div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h2>Search VIN</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="GET" action="{{ route('sm.vin-check') }}" class="form-inline">
                        <input type="text" name="vin" class="form-control" value="{{ $vin ?? '' }}"
                               placeholder="Enter VIN / Frame No..." required style="width:250px">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Check</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if($result)
    <div class="row">
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title"><h2>Results for "{{ $vin }}"</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    @if($result->isEmpty())
                        <div class="alert alert-warning">VIN not found in check list.</div>
                    @else
                        <table class="table table-bordered">
                            <thead><tr><th>VIN</th><th>Details</th><th>Added By</th><th>Date</th></tr></thead>
                            <tbody>
                                @foreach($result as $r)
                                <tr class="specailred">
                                    <td>{{ $r->VIN }}</td>
                                    <td>{{ $r->details ?? '' }}</td>
                                    <td>{{ $r->added_by ?? '' }}</td>
                                    <td>{{ $r->added_on ?? '' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@push('styles')
<style>.specailred { background-color: aquamarine; }</style>
@endpush
@endsection
