@extends('layouts.master')

@section('title', 'Open New RO')

@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3>Open New RO:</h3>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-sm-9">
                    <div class="input-group">
                        <form action="" method="POST" onsubmit="return appcheck();">
                            @csrf
                            <input type="text" class="form-control" id="checkreg" name="Registration"
                                   style="text-transform:uppercase" pattern=".{3,12}"
                                   placeholder="By Registration">
                            <span class="input-group-btn">
                                <input type="submit" name="checkreg" class="btn btn-danger" value="Registration check!">
                            </span>
                        </form>
                    </div>
                </div>
            </div>

            <div class="x_content">
                <div class="col-sm-9">
                    <div class="input-group">
                        <form action="" method="post" onsubmit="return appcheck();">
                            @csrf
                            <input type="text" class="form-control" name="fram" id="checkreg"
                                   pattern=".{4,15}" style="text-transform:uppercase"
                                   placeholder="By Chasis Number">
                            <span class="input-group-btn">
                                <input type="submit" class="btn btn-danger" name="form_submit" value="Fram No. Check!">
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
