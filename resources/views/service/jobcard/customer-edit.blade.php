@extends('layouts.master')

@section('title', 'Edit Customer')

@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Edit Customer Details</h3>
        </div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Customer ID: {{ $customer->Customer_id }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="POST" action="{{ route('jobcard.customer.update') }}" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="cust_id"   value="{{ $customer->Customer_id }}">
                        @if($roNo)
                            <input type="hidden" name="ro_no" value="{{ $roNo }}">
                        @else
                            <input type="hidden" name="veh_idd" value="{{ $vehicleId }}">
                        @endif

                        <div class="form-group">
                            <label class="col-md-3 control-label">Customer Type</label>
                            <div class="col-md-5">
                                <select name="cust_type" class="form-control">
                                    @foreach(['Individuals','Govt','Force','Corporate','Banks','Investor','Others'] as $type)
                                        <option value="{{ $type }}" {{ $customer->cust_type==$type?'selected':'' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Full Name <span class="required">*</span></label>
                            <div class="col-md-5">
                                <input type="text" name="name" class="form-control" required
                                       value="{{ $customer->Customer_name }}" style="text-transform:uppercase">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Primary Mobile <span class="required">*</span></label>
                            <div class="col-md-5">
                                <input type="text" name="mobile" class="form-control"
                                       required value="{{ $customer->mobile }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Secondary Phone</label>
                            <div class="col-md-5">
                                <input type="text" name="off_phone" class="form-control"
                                       value="{{ $customer->off_phone }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">City</label>
                            <div class="col-md-3">
                                <select name="city" id="city" class="form-control" onchange="updateRegions()">
                                    @foreach(['Peshawar','Kohat','Islamabad','Charsadah','Mardan','DIK','Other'] as $city)
                                        <option value="{{ $city }}" {{ $customer->City==$city?'selected':'' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-md-1 control-label">Region</label>
                            <div class="col-md-3">
                                <select name="region" id="region" class="form-control">
                                    <option value="{{ $customer->Region }}" selected>{{ $customer->Region }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Postal Address</label>
                            <div class="col-md-5">
                                <input type="text" name="address" class="form-control"
                                       value="{{ $customer->Address }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">DOB</label>
                            <div class="col-md-3">
                                <input type="date" name="dob" class="form-control"
                                       value="{{ $customer->DOB }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">CNIC</label>
                            <div class="col-md-5">
                                <input type="text" name="cnic" class="form-control"
                                       value="{{ $customer->CNIC }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Email</label>
                            <div class="col-md-5">
                                <input type="email" name="email" class="form-control"
                                       value="{{ $customer->email }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">NTN</label>
                            <div class="col-md-5">
                                <input type="text" name="ntn" class="form-control"
                                       value="{{ $customer->NTN }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">STRN</label>
                            <div class="col-md-5">
                                <input type="text" name="strn" class="form-control"
                                       value="{{ $customer->STRN }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Supplier No.</label>
                            <div class="col-md-5">
                                <input type="text" name="supplier" class="form-control"
                                       value="{{ $customer->Supplier }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-5">
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fa fa-save"></i> Update Customer Details
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
var peshawarRegions = ['Hayatabad','Sadar','Ring Road','Kohat Road','Industrail Zone','Khyber Bazar','Warsak Road'];
function updateRegions() {
    var city   = document.getElementById('city').value;
    var sel    = document.getElementById('region');
    sel.options.length = 0;
    if (city === 'Peshawar') {
        peshawarRegions.forEach(function(r) {
            sel.options[sel.options.length] = new Option(r);
        });
    } else {
        sel.options[sel.options.length] = new Option('Other');
    }
}
</script>
@endpush
@endsection
