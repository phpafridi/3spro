@extends('layouts.master')
@section('title', 'SM - Labor List')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Master Data &mdash; Labor List</h3></div></div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title"><h2>Add Labor</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.master.labor.store') }}" class="form-horizontal">
                        @csrf
                        <div class="form-group"><label class="col-md-3 control-label">Labor <span class="required">*</span></label>
                            <div class="col-md-8"><input type="text" name="Labor" class="form-control" required></div></div>
                        @foreach(['Cate1','Cate2','Cate3','Cate4','Cate5'] as $c)
                        <div class="form-group"><label class="col-md-3 control-label">{{ $c }}</label>
                            <div class="col-md-8"><input type="text" name="{{ $c }}" class="form-control"></div></div>
                        @endforeach
                        <div class="form-group"><div class="col-md-offset-3 col-md-8">
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Add</button></div></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title"><h2>Labor List <span class="badge">{{ $laborList->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered table-condensed" id="laborTable">
                        <thead><tr><th>ID</th><th>Labor</th><th>Cate1</th><th>Cate2</th><th>Cate3</th><th>Cate4</th><th>Cate5</th><th>Del</th></tr></thead>
                        <tbody>
                            @forelse($laborList as $l)
                            <tr>
                                <td>{{ $l->Labor_ID }}</td>
                                <td>{{ $l->Labor }}</td>
                                <td>{{ $l->Cate1 }}</td><td>{{ $l->Cate2 }}</td>
                                <td>{{ $l->Cate3 }}</td><td>{{ $l->Cate4 }}</td><td>{{ $l->Cate5 }}</td>
                                <td>
                                    <button class="btn btn-xs btn-danger del-labor" data-id="{{ $l->Labor_ID }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center text-muted">No labor in list.</td></tr>
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
    $('#laborTable').DataTable();
    $(document).on('click','.del-labor',function(){
        if(!confirm('Delete this labor?')) return;
        var id=$(this).data('id'), row=$(this).closest('tr');
        $.post('{{ route("sm.master.labor.delete") }}',{_token:'{{ csrf_token() }}',id:id},function(r){
            if(r.status==='ok') row.fadeOut();
        });
    });
});
</script>
@endpush
@endsection
