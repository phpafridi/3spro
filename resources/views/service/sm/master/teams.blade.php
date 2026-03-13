@extends('layouts.master')
@section('title', 'SM - Tech Teams')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title"><div class="title_left"><h3>Master Data &mdash; Tech Teams</h3></div></div>
    <div class="clearfix"></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title"><h2>Add Team</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('sm.master.teams.store') }}" class="form-horizontal">
                        @csrf
                        <div class="form-group"><label class="col-md-4 control-label">Team Name <span class="required">*</span></label>
                            <div class="col-md-7"><input type="text" name="team_name" class="form-control" required></div></div>
                        <div class="form-group"><label class="col-md-4 control-label">Members</label>
                            <div class="col-md-7"><input type="text" name="members" class="form-control" placeholder="Comma-separated names"></div></div>
                        <div class="form-group"><label class="col-md-4 control-label">Category</label>
                            <div class="col-md-7">
                                <select name="category" class="form-control">
                                    <option>Workshop</option><option>Body &amp; Paint</option>
                                </select>
                            </div></div>
                        <div class="form-group"><div class="col-md-offset-4 col-md-7">
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Add Team</button></div></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title"><h2>Teams <span class="badge">{{ $teams->count() }}</span></h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered table-condensed" id="teamTable">
                        <thead><tr><th>ID</th><th>Team Name</th><th>Members</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse($teams as $t)
                            <tr>
                                <td>{{ $t->team_id }}</td>
                                <td>{{ $t->team_name }}</td>
                                <td>{{ $t->members }}</td>
                                <td>{{ $t->category }}</td>
                                <td>{{ $t->status }}</td>
                                <td>
                                    <button class="btn btn-xs btn-danger del-team" data-id="{{ $t->team_id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No teams.</td></tr>
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
    $('#teamTable').DataTable();
    $(document).on('click','.del-team',function(){
        if(!confirm('Delete team?')) return;
        var id=$(this).data('id'), row=$(this).closest('tr');
        $.post('{{ route("sm.master.teams.delete") }}',{_token:'{{ csrf_token() }}',id:id},function(r){
            if(r.status==='ok') row.fadeOut();
        });
    });
});
</script>
@endpush
@endsection
