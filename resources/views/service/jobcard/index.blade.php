@extends('layouts.master')

@section('title', 'JobCards - Unclosed')

@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3>Open Repair Order:</h3>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Open Repair Order:</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr style="background-color:red;">
                                <th>Jobcard#</th>
                                <th>Vehicle</th>
                                <th>Registration</th>
                                <th>Customer Name</th>
                                <th>Open DateTime</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unclosedJobs ?? [] as $job)
                            <tr>
                                <th>{{ $job->Jobc_id }}</th>
                                <td>{{ $job->Variant }}</td>
                                <td style="color:red;">{{ $job->Veh_reg_no }}</td>
                                <td>{{ $job->Customer_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($job->Open_date_time)->format('d/m/Y g:i A') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <table>
                                        <tr>
                                            <td>
                                                <form action="{{ route('jobcard.jobrequest') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" value="{{ $job->Jobc_id }}" name="job_id">
                                                    <input type="hidden" value="{{ $job->Variant }}" name="variant">
                                                    <button type="submit" class="btn btn-round btn-primary">JobRequest</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('jobcard.part-add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" value="{{ $job->Jobc_id }}" name="job_id">
                                                    <input type="hidden" value="{{ $job->Variant }}" name="variant">
                                                    <button type="submit" class="btn btn-round btn-info">Spare Parts</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('jobcard.sublet') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" value="{{ $job->Jobc_id }}" name="job_id">
                                                    <input type="hidden" value="{{ $job->Variant }}" name="variant">
                                                    <button type="submit" class="btn btn-round btn-warning">Sublet</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('jobcard.consumable') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" value="{{ $job->Jobc_id }}" name="job_id">
                                                    <input type="hidden" value="{{ $job->Variant }}" name="variant">
                                                    <button type="submit" class="btn btn-round btn-warning">Consumble</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('jobcard.start-working') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="change_status">
                                                    <input type="hidden" value="{{ $job->Jobc_id }}" name="job_id">
                                                    <input type="hidden" value="{{ $job->comp_appointed }}" name="comp_appointed">
                                                    <button type="submit" class="btn btn-round btn-danger">Start Working</button>
                                                </form>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No open jobcards found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
