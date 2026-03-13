@extends('layouts.master')
@section('title', 'SM - Active Customers')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Service Manager &mdash; Active Customers</h3></div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Customers <span class="badge">{{ $customers->total() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-striped table-bordered table-condensed" id="custTable">
                        <thead>
                            <tr><th>ID</th><th>Name</th><th>Mobile</th><th>City</th><th>Customer Type</th><th>Update Type</th></tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $c)
                            <tr>
                                <td>{{ $c->Customer_id }}</td>
                                <td>{{ $c->Customer_name }}</td>
                                <td>{{ $c->mobile }}</td>
                                <td>{{ $c->City }}</td>
                                <td>{{ $c->cust_type }}</td>
                                <td>
                                    <form class="form-inline update-type-form">
                                        @csrf
                                        <input type="hidden" name="cust_id" value="{{ $c->Customer_id }}">
                                        <select name="cust_type" class="form-control input-sm cust-type-sel" onchange="updateType(this, {{ $c->Customer_id }})">
                                            @foreach(['Individuals','Govt','Force','Corporate','Banks','Investor','Others'] as $t)
                                                <option value="{{ $t }}" {{ $c->cust_type==$t?'selected':'' }}>{{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function updateType(sel, custId) {
    $.post('{{ route("sm.ac.update-type") }}', {
        _token: '{{ csrf_token() }}', cust_id: custId, cust_type: sel.value
    }, function(r) {
        if(r.status==='ok') $(sel).closest('td').find('select').css('border-color','green');
    });
}
$(document).ready(function(){ $('#custTable').DataTable({paging:false}); });
</script>
@endpush
@endsection
