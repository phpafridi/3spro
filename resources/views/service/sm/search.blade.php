@extends('layouts.master')
@section('title', 'SM - Search / Print')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Search &amp; Print</h3></div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="x_panel">
                <div class="x_title"><h2>Search Engine</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.search') }}" class="form-horizontal">
                        @csrf
                        <div class="form-group">
                            <label class="col-md-3 control-label">Search Type</label>
                            <div class="col-md-7">
                                <select name="field" class="form-control" required>
                                    <option value="jobcard-instail">Jobcard (Instail / Open)</option>
                                    <option value="jobcard-closed">Jobcard (Closed)</option>
                                    <option value="Invoice">Invoice</option>
                                    <option value="SalesTax">Sales Tax Invoice</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">RO / Invoice No</label>
                            <div class="col-md-7">
                                <input type="text" name="search" class="form-control" required
                                       placeholder="Enter RO or Invoice number...">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-7">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-print"></i> Search &amp; Open Print
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
