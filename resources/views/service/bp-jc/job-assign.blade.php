@extends('layouts.master')

@section('title', 'BP - Assign Job')

@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Body &amp; Paint &mdash; Assign Job</h3>
        </div>
        <div class="title_right">
            <a href="{{ route('bp-jc.index') }}" class="btn btn-default pull-right">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4">
            {{-- JOB DETAILS --}}
            <div class="x_panel">
                <div class="x_title"><h2>Job Details</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-condensed">
                        <tr><th>Labor ID:</th><td>{{ $labor->Labor_id }}</td></tr>
                        <tr><th>RO No:</th><td><strong>{{ $labor->RO_no }}</strong></td></tr>
                        <tr><th>Labor:</th><td>{{ $labor->Labor }}</td></tr>
                        <tr><th>Type:</th><td>{{ $labor->type }}</td></tr>
                        <tr><th>Registration:</th><td>{{ $labor->Registration }}</td></tr>
                        <tr><th>Variant:</th><td>{{ $labor->Variant }}</td></tr>
                        <tr><th>SA:</th><td>{{ $labor->SA }}</td></tr>
                        <tr><th>Customer:</th><td>{{ $labor->Customer_name }}</td></tr>
                        <tr><th>Entry Time:</th><td>{{ $labor->entry_time }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- ASSIGN FORM --}}
            <div class="x_panel">
                <div class="x_title"><h2>Assign / Update Job Status</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <form method="POST" action="{{ route('bp-jc.assign.process') }}" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="labor_id" value="{{ $labor->Labor_id }}">

                        <div class="form-group">
                            <label class="col-md-3 control-label">Action <span class="required">*</span></label>
                            <div class="col-md-6">
                                <select name="category" id="cat_sel" class="form-control"
                                        onchange="toggleFields(this)" required>
                                    <option value="">-- Select Action --</option>
                                    <option value="Job Assign">Job Assign</option>
                                    <option value="Job Not Done">Job Not Done</option>
                                    <option value="Job Stopage">Job Stopage</option>
                                </select>
                            </div>
                        </div>

                        {{-- Fields for Job Assign --}}
                        <div id="assign_fields" style="display:none;">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Team</label>
                                <div class="col-md-6">
                                    <select name="team" id="team_sel" class="form-control"
                                            onchange="loadMembers(this.value)">
                                        <option value="">-- Select Team --</option>
                                        @foreach($teams as $t)
                                            <option value="{{ $t->team_name }}">{{ $t->team_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Members</label>
                                <div class="col-md-6">
                                    <div id="members_list" class="well well-sm" style="min-height:40px;">
                                        <em class="text-muted">Select a team to view members</em>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Bay</label>
                                <div class="col-md-6">
                                    <select name="bay" class="form-control">
                                        <option value="">-- Select Bay --</option>
                                        @foreach($bays as $b)
                                            <option value="{{ $b->bay_name }}">{{ $b->bay_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Estimated Time (hrs)</label>
                                <div class="col-md-6">
                                    <input type="number" name="estimatedtime" class="form-control"
                                           step="0.5" min="0.5" placeholder="e.g. 2.5">
                                </div>
                            </div>
                        </div>

                        {{-- Fields for Not Done / Stopage --}}
                        <div id="remark_fields" style="display:none;">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Remarks</label>
                                <div class="col-md-6">
                                    <textarea name="remarks" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div id="resume_fields" style="display:none;">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Resume Time</label>
                                <div class="col-md-6">
                                    <input type="datetime-local" name="resumetime" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-6">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fa fa-check"></i> Submit
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
function toggleFields(sel) {
    var val = sel.value;
    $('#assign_fields').hide();
    $('#remark_fields').hide();
    $('#resume_fields').hide();

    if (val === 'Job Assign')   { $('#assign_fields').show(); }
    if (val === 'Job Not Done') { $('#remark_fields').show(); }
    if (val === 'Job Stopage')  { $('#remark_fields').show(); $('#resume_fields').show(); }
}

function loadMembers(team) {
    if (!team) { $('#members_list').html('<em class="text-muted">Select a team</em>'); return; }
    $.post('{{ route("bp-jc.team-members") }}', {
        _token: '{{ csrf_token() }}', team: team
    }, function(res) {
        $('#members_list').html(res.members || '<em>No members listed</em>');
    });
}
</script>
@endpush
@endsection
