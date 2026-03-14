@extends('layouts.master')
@section('title', 'BP - Assign Job')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>@endif
<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    {{-- Job Details --}}
    <div class="md:col-span-2 bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Job Details</h2>
            <a href="{{ route('bp-jc.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa fa-arrow-left mr-1"></i>Back</a>
        </div>
        <div class="space-y-2 text-sm">
            @foreach(['Labor ID'=>$labor->Labor_id,'RO No'=>$labor->RO_no,'Labor'=>$labor->Labor,'Type'=>$labor->type,'Registration'=>$labor->Registration,'Variant'=>$labor->Variant,'SA'=>$labor->SA,'Customer'=>$labor->Customer_name,'Entry Time'=>$labor->entry_time] as $label=>$val)
            <div class="flex">
                <span class="w-32 text-gray-500 shrink-0">{{ $label }}:</span>
                <span class="font-medium text-gray-800">{{ $val }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Assign Form --}}
    <div class="md:col-span-3 bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Assign / Update Job Status</h2>
        <form method="POST" action="{{ route('bp-jc.assign.process') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="labor_id" value="{{ $labor->Labor_id }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Action <span class="text-red-500">*</span></label>
                <select name="category" id="cat_sel" onchange="toggleFields(this)" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Action --</option>
                    <option value="Job Assign">Job Assign</option>
                    <option value="Job Not Done">Job Not Done</option>
                    <option value="Job Stopage">Job Stopage</option>
                </select>
            </div>

            <div id="assign_fields" class="hidden space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Team</label>
                    <select name="team" id="team_sel" onchange="loadMembers(this.value)"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Team --</option>
                        @foreach($teams as $t)
                        <option value="{{ $t->team_name }}">{{ $t->team_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Members</label>
                    <div id="members_list" class="border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-400 min-h-[40px] bg-gray-50">
                        Select a team to view members
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bay</label>
                    <select name="bay" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Bay --</option>
                        @foreach($bays as $b)
                        <option value="{{ $b->bay_name }}">{{ $b->bay_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Time (hrs)</label>
                    <input type="number" name="estimatedtime" step="0.5" min="0.5" placeholder="e.g. 2.5"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div id="remark_fields" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                <textarea name="remarks" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div id="resume_fields" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Resume Time</label>
                <input type="datetime-local" name="resumetime" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-check mr-2"></i> Submit
            </button>
        </form>
    </div>
</div>
@push('scripts')
<script>
function toggleFields(sel) {
    document.getElementById('assign_fields').classList.add('hidden');
    document.getElementById('remark_fields').classList.add('hidden');
    document.getElementById('resume_fields').classList.add('hidden');
    if (sel.value === 'Job Assign')   document.getElementById('assign_fields').classList.remove('hidden');
    if (sel.value === 'Job Not Done') document.getElementById('remark_fields').classList.remove('hidden');
    if (sel.value === 'Job Stopage')  { document.getElementById('remark_fields').classList.remove('hidden'); document.getElementById('resume_fields').classList.remove('hidden'); }
}
function loadMembers(team) {
    if (!team) { document.getElementById('members_list').textContent = 'Select a team'; return; }
    fetch('{{ route("bp-jc.team-members") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({team})
    }).then(r=>r.json()).then(res=>{ document.getElementById('members_list').innerHTML = res.members || 'No members'; });
}
</script>
@endpush
@endsection
