@extends('layouts.master')
@section('title', 'BP - In Progress Jobs')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<form method="POST" action="{{ route('bp-jc.job-done') }}" id="jobDoneForm">
    @csrf
    <input type="hidden" name="Labor_id" id="done_labor_id">
</form>
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">In Progress Jobs
            <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-700 text-sm rounded-full">{{ $inprogressJobs->count() }}</span>
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Team</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bay</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SA</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assign Time</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($inprogressJobs as $i => $job)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $job->RO_no }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Labor }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $job->team }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $job->bay }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $job->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $job->SA }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $job->Assign_time }}</td>
                    <td class="px-4 py-3">
                        <button class="done-btn px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors" data-id="{{ $job->Labor_id }}">
                            <i class="fa fa-check mr-1"></i> Job Done
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-6 py-8 text-center text-gray-400">No jobs in progress.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
document.querySelectorAll('.done-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (!confirm('Mark this job as Done?')) return;
        document.getElementById('done_labor_id').value = this.dataset.id;
        document.getElementById('jobDoneForm').submit();
    });
});
</script>
@endpush
@endsection
