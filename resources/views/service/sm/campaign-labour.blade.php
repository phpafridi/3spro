@extends('layouts.master')
@section('title', 'SM - Campaign Labour')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left"><h3>Campaign Labour &mdash; {{ $campaign->campaign_name }}</h3></div>
        <div class="title_right">
            <a href="{{ route('sm.campaigns') }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title"><h2>Add Labour</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.campaign-labour.store', $campId) }}" class="form-horizontal">
                        @csrf
                        <div class="form-group">
                            <label class="col-md-4 control-label">Labour</label>
                            <div class="col-md-8">
                                <select name="labours" class="form-control select2" required>
                                    <option value="">-- Select --</option>
                                    @foreach($laborList as $l)
                                        <option value="{{ $l->Labor }}">{{ $l->Labor }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Cost</label>
                            <div class="col-md-8"><input type="number" name="labourcost" class="form-control" step="0.01" min="0" value="0"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-8">
                                <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title"><h2>Assigned Labour <span class="badge">{{ $labours->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered table-condensed">
                        <thead><tr><th>#</th><th>Labour</th><th>Cost</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($labours as $l)
                            <tr>
                                <td>{{ $l->id }}</td>
                                <td>{{ $l->labour_des }}</td>
                                <td>{{ number_format($l->labour_cost,2) }}</td>
                                <td>
                                    <button class="btn btn-xs btn-danger del-labour" data-id="{{ $l->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">No labour assigned.</td></tr>
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
    $('.select2').select2();
    $('.del-labour').on('click', function(){
        if(!confirm('Delete this labour?')) return;
        $.post('{{ route("sm.campaign-labour.delete") }}', {
            _token:'{{ csrf_token() }}', id: $(this).data('id')
        }, function(r){ if(r.status==='ok') location.reload(); });
    });
});
</script>
@endpush
@endsection
