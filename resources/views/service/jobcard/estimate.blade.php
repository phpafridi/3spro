@extends('layouts.master')

@section('title', 'Create Estimate')

@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Workshop &mdash; Create Estimate</h3>
        </div>
        <div class="title_right">
            <a href="{{ route('jobcard.unclosed-estimates') }}" class="btn btn-default pull-right">
                <i class="fa fa-list"></i> Unclosed Estimates
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="x_panel">
                <div class="x_title"><h2>New Estimate</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('jobcard.estimate.store') }}" class="form-horizontal">
                        @csrf

                        <div class="form-group">
                            <label class="col-md-3 control-label">Estimate Type <span class="required">*</span></label>
                            <div class="col-md-5">
                                <select name="estimate_type" id="est_type" class="form-control" required onchange="toggleInsurance(this)">
                                    <option value="Self">Self</option>
                                    <option value="Insurance">Insurance</option>
                                    <option value="Fleet">Fleet</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Payment Mode</label>
                            <div class="col-md-5">
                                <select name="payment_mode" class="form-control">
                                    <option>Cash</option>
                                    <option>Cheque</option>
                                    <option>Credit</option>
                                    <option>Online</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Customer Type</label>
                            <div class="col-md-5">
                                <select name="cust_type" class="form-control">
                                    <option>Individuals</option>
                                    <option>Govt</option>
                                    <option>Corporate</option>
                                    <option>Force</option>
                                    <option>Banks</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Customer <span class="required">*</span></label>
                            <div class="col-md-5">
                                <select name="cust_id" class="form-control select2" required>
                                    <option value="">-- Select Customer --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->Customer_id }}">{{ $c->Customer_name }} - {{ $c->mobile }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Vehicle ID <span class="required">*</span></label>
                            <div class="col-md-5">
                                <input type="number" name="veh_id" class="form-control" required
                                       placeholder="Enter vehicle ID">
                            </div>
                        </div>

                        <div id="insurance_section" style="display:none;">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Insurance Company</label>
                                <div class="col-md-5">
                                    <select name="insur_company" class="form-control select2">
                                        <option value="">-- Select --</option>
                                        @foreach($insurCompanies as $ic)
                                            <option value="{{ $ic->company_name }}">{{ $ic->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Surveyor Name</label>
                                <div class="col-md-5">
                                    <input type="text" name="surv_name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Surveyor Type</label>
                                <div class="col-md-5">
                                    <select name="surv_type" class="form-control">
                                        <option>In-House</option>
                                        <option>External</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Surveyor Contact</label>
                                <div class="col-md-5">
                                    <input type="text" name="sur_cont" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Est. Delivery Date</label>
                            <div class="col-md-5">
                                <input type="date" name="est_delivery" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-5">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fa fa-save"></i> Create Estimate
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function toggleInsurance(sel) {
    if (sel.value === 'Insurance') {
        $('#insurance_section').show();
    } else {
        $('#insurance_section').hide();
    }
}
$(document).ready(function() { $('.select2').select2(); });
</script>
@endpush
@endsection
