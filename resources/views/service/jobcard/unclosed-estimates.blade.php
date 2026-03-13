@extends('layouts.master')

@section('title', 'Unclosed Estimates')

@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Workshop &mdash; Unclosed Estimates</h3></div>
        <div class="title_right">
            <a href="{{ route('jobcard.estimate.create') }}" class="btn btn-success pull-right">
                <i class="fa fa-plus"></i> New Estimate
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Open Estimates</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-striped table-bordered" id="estTable">
                        <thead>
                            <tr>
                                <th>Est ID</th><th>Type</th><th>Customer</th><th>Registration</th>
                                <th>Variant</th><th>Payment</th><th>Created</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($estimates as $est)
                            <tr>
                                <td>{{ $est->est_id }}</td>
                                <td>{{ $est->estimate_type }}</td>
                                <td>{{ $est->Customer_name }}</td>
                                <td>{{ $est->Registration }}</td>
                                <td>{{ $est->Variant }}</td>
                                <td>{{ $est->payment_mode }}</td>
                                <td>{{ $est->entry_datetime }}</td>
                                <td>
                                    <a href="{{ route('jobcard.estimate.ro', $est->est_id) }}"
                                       class="btn btn-xs btn-primary">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('jobcard.estimate.labor', $est->est_id) }}"
                                       class="btn btn-xs btn-info">
                                        <i class="fa fa-wrench"></i> Labor
                                    </a>
                                    <a href="{{ route('jobcard.estimate.part', $est->est_id) }}"
                                       class="btn btn-xs btn-warning">
                                        <i class="fa fa-cogs"></i> Parts
                                    </a>
                                    <a href="{{ route('jobcard.estimate.consumable', $est->est_id) }}"
                                       class="btn btn-xs btn-default">
                                        <i class="fa fa-tint"></i> Cons.
                                    </a>
                                    <a href="{{ route('jobcard.estimate.sublet', $est->est_id) }}"
                                       class="btn btn-xs btn-default">
                                        <i class="fa fa-external-link"></i> Sublet
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center">No open estimates.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() { $('#estTable').DataTable({ order: [[6,'desc']] }); });
</script>
@endpush
@endsection
