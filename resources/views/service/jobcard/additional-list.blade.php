@extends('layouts.master')
@section('title', 'Additional Jobs')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Additional Jobs</h2>
        <input type="text" id="search_input" placeholder="Search..."
               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="jobs_table">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Jobcard #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Reg #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Customer</th>
                    
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Open Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">MSI Cat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobs as $job)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $job->Jobc_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $job->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Customer_name }}</td>
                    
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($job->Open_date_time)->format('d/m/Y g:i A') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $job->MSI_cat }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            <a href="{{ route('jobcard.additional.jobrequest', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded">JobRequest</a>
                            <a href="{{ route('jobcard.additional.part', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-cyan-600 hover:bg-cyan-700 text-white text-xs rounded">Parts</a>
                            <a href="{{ route('jobcard.additional.sublet', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">Sublet</a>
                            <a href="{{ route('jobcard.additional.consumable', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs rounded">Consumble</a>
                            <a href="{{ route('jobcard.additional', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded">View RO</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                        <i class="fa fa-inbox text-3xl block mb-2"></i>
                        No jobs in workshop.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('search_input').addEventListener('keyup', function () {
    var val = this.value.toLowerCase();
    document.querySelectorAll('#jobs_table tbody tr').forEach(function (tr) {
        tr.style.display = tr.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>
@endpush
@endsection
