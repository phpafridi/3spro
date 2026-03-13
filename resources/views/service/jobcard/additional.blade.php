@extends('layouts.master')

@section('title', 'Additional Jobs')

@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="col-md-12 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Additional Jobs:</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div align="center" width="70%">
                            Search <input type="text" id="search" placeholder="Type to search">
                        </div>
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>Jobcard#</th>
                                    <th>Reg#</th>
                                    <th>Customer Name</th>
                                    <th>Mobile</th>
                                    <th>Open DateTime</th>
                                    <th>MIS Catg</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobs ?? [] as $job)
                                <tr>
                                    <td><table><tr>
                                        <th><a href="#" target="_blank">{{ $job->Jobc_id }}</a></th>
                                        <td style="color:red;">{{ $job->Veh_reg_no }}</td>
                                        <td>{{ $job->Customer_name }}</td>
                                        <td>{{ $job->mobile }}</td>
                                        <td>{{ \Carbon\Carbon::parse($job->Open_date_time)->format('d/m/Y g:i A') }}</td>
                                        <td>{{ $job->MSI_cat }}</td>
                                        <td></td>
                                    </tr></table></td>
                                </tr>
                                <tr align="center">
                                    <td align="center">
                                        <form action="{{ route('jobcard.additional.jobrequest', $job->Jobc_id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $job->Jobc_id }}" name="job_id">
                                            <input type="hidden" value="{{ $job->Variant }}" name="variant">
                                            <button type="submit" class="btn btn-round btn-warning">JobRequest</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('jobcard.additional.part', $job->Jobc_id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $job->Jobc_id }}" name="job_id">
                                            <input type="hidden" value="{{ $job->Variant }}" name="variant">
                                            <button type="submit" class="btn btn-round btn-info">Add Part</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('jobcard.additional.sublet', $job->Jobc_id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $job->Jobc_id }}" name="job_id">
                                            <input type="hidden" value="{{ $job->Variant }}" name="variant">
                                            <button type="submit" class="btn btn-round btn-primary">Add Sublet</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('jobcard.additional.consumable', $job->Jobc_id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $job->Jobc_id }}" name="job_id">
                                            <input type="hidden" value="{{ $job->Variant }}" name="variant">
                                            <button type="submit" class="btn btn-round btn-warning">Add Consumble</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('jobcard.invoice', $job->Jobc_id) }}" method="POST" target="_blank">
                                            @csrf
                                            <input type="hidden" value="{{ $job->Jobc_id }}" name="job_id">
                                            <button type="submit" class="btn btn-round btn-success">Calculation</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center">No additional jobs found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$("#search").keyup(function(){
    _this = this;
    $.each($("#table tbody tr"), function() {
        if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
           $(this).hide();
        else
           $(this).show();
    });
});
</script>
@endpush
@endsection
