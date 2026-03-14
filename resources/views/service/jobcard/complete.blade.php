@extends('layouts.master')
@section('title', 'Job Complete')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Job Completed</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jobcard#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parts</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sublet</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Consumble</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Close Jobcard</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobs ?? [] as $job)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $job->Jobc_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $job->Veh_reg_no }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Labor</span></td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Parts</span></td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Sublet</span></td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Consumble</span></td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('jobcard.complete.process') }}" onsubmit="return confirm('Close RO #{{ $job->Jobc_id }}?')">
                            @csrf
                            <input type="hidden" name="job_id" value="{{ $job->Jobc_id }}">
                            <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors">Jobcomplete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400"><i class="fa fa-check-circle text-3xl block mb-2 text-green-400"></i>No jobs pending completion.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
