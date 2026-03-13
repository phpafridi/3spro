@extends('layouts.master')
@section('title', 'SM - Variant Codes')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Master Data &mdash; Variant Codes</h3></div></div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title"><h2>Add Variant</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.master.variants.store') }}" class="form-horizontal">
                        @csrf
                        @foreach(['Variant'=>'Variant *','Model'=>'Model','Make'=>'Make','Fram'=>'Vehicle Type','Engine'=>'Engine','Category'=>'Category'] as $k=>$label)
                        <div class="form-group">
                            <label class="col-md-4 control-label">{{ $label }}</label>
                            <div class="col-md-7"><input type="text" name="{{ $k }}" class="form-control" {{ $k==='Variant'?'required':'' }}></div>
                        </div>
                        @endforeach
                        <div class="form-group"><div class="col-md-offset-4 col-md-7">
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Add Variant</button></div></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title"><h2>Variants <span class="badge">{{ $variants->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered table-condensed" id="varTable">
                        <thead><tr><th>ID</th><th>Variant</th><th>Model</th><th>Make</th><th>Type</th><th>Engine</th><th>Category</th><th>Del</th></tr></thead>
                        <tbody>
                            @forelse($variants as $v)
                            <tr>
                                <td>{{ $v->variant_id }}</td><td>{{ $v->Variant }}</td>
                                <td>{{ $v->Model }}</td><td>{{ $v->Make }}</td>
                                <td>{{ $v->Fram }}</td><td>{{ $v->Engine }}</td>
                                <td>{{ $v->Category }}</td>
                                <td>
                                    <button class="btn btn-xs btn-danger del-var" data-id="{{ $v->variant_id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center text-muted">No variants.</td></tr>
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
    $('#varTable').DataTable();
    $(document).on('click','.del-var',function(){
        if(!confirm('Delete variant?')) return;
        var id=$(this).data('id'), row=$(this).closest('tr');
        $.post('{{ route("sm.master.variants.delete") }}',{_token:'{{ csrf_token() }}',id:id},function(r){
            if(r.status==='ok') row.fadeOut();
        });
    });
});
</script>
@endpush
@endsection
