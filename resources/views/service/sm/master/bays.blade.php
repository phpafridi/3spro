@extends('layouts.master')
@section('title', 'SM - Bays Management')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Master Data &mdash; Bays Management</h3></div></div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title"><h2>Add Bay</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.master.bays.store') }}" class="form-horizontal">
                        @csrf
                        <div class="form-group"><label class="col-md-4 control-label">Bay Name <span class="required">*</span></label>
                            <div class="col-md-7"><input type="text" name="bay_name" class="form-control" required></div></div>
                        <div class="form-group"><label class="col-md-4 control-label">Category</label>
                            <div class="col-md-7">
                                <select name="category" class="form-control">
                                    <option>Workshop</option><option>Body &amp; Paint</option><option>PDI</option>
                                </select>
                            </div></div>
                        <div class="form-group"><label class="col-md-4 control-label">Type</label>
                            <div class="col-md-7"><input type="text" name="bay_type" class="form-control"></div></div>
                        <div class="form-group"><div class="col-md-offset-4 col-md-7">
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Add Bay</button></div></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title"><h2>Bays <span class="badge">{{ $bays->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered table-condensed" id="bayTable">
                        <thead><tr><th>#</th><th>Bay Name</th><th>Category</th><th>Type</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse($bays as $b)
                            <tr id="row-{{ $b->id }}">
                                <td>{{ $b->id }}</td>
                                <td><input type="text" class="form-control input-sm" value="{{ $b->bay_name }}" id="bn-{{ $b->id }}"></td>
                                <td>
                                    <select class="form-control input-sm" id="bc-{{ $b->id }}">
                                        @foreach(['Workshop','Body & Paint','PDI'] as $cat)
                                        <option value="{{ $cat }}" {{ $b->category==$cat?'selected':'' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" class="form-control input-sm" value="{{ $b->bay_type }}" id="bt-{{ $b->id }}"></td>
                                <td>
                                    <button class="btn btn-xs btn-warning upd-btn" data-id="{{ $b->id }}"><i class="fa fa-save"></i></button>
                                    <button class="btn btn-xs btn-danger del-btn" data-id="{{ $b->id }}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">No bays yet.</td></tr>
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
$(document).ready(function(){
    $('#bayTable').DataTable({columnDefs:[{orderable:false,targets:4}]});
    $(document).on('click','.upd-btn',function(){
        var id=$(this).data('id');
        $.post('{{ route("sm.master.bays.update") }}',{
            _token:'{{ csrf_token() }}',id:id,
            bay_name:$('#bn-'+id).val(),category:$('#bc-'+id).val(),bay_type:$('#bt-'+id).val()
        },function(){ location.reload(); });
    });
    $(document).on('click','.del-btn',function(){
        if(!confirm('Delete this bay?')) return;
        var id=$(this).data('id');
        $.post('{{ route("sm.master.bays.delete") }}',{_token:'{{ csrf_token() }}',id:id},function(r){
            if(r.status==='ok') $('#row-'+id).fadeOut();
        });
    });
});
</script>
@endpush
@endsection
