@extends('layouts.master')
@section('title', 'SM - Unique VINs')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Unique VINs / Frame Numbers</h3></div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title"><h2>Frame Numbers <span class="badge">{{ $vins->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered table-condensed" id="vinTable">
                        <thead><tr><th>#</th><th>Frame No / VIN</th></tr></thead>
                        <tbody>
                            @foreach($vins as $i => $v)
                            <tr><td>{{ $i+1 }}</td><td class="redi">{{ $v->Frame_no }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>$(document).ready(function(){ $('#vinTable').DataTable(); });</script>
@endpush
@endsection
