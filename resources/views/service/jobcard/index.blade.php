@extends('layouts.master')
@section('title', 'JobCards - Unclosed')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Open Repair Orders</h2>
        <a href="{{ route('jobcard.add-vehicle') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
            <i class="fa fa-plus mr-2"></i> Open New RO
        </a>
    </div>
    @if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>@endif
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Jobcard#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Vehicle</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Mobile</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Open Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($unclosedJobs ?? [] as $job)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $job->Jobc_id }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Variant }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $job->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $job->mobile }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($job->Open_date_time)->format('d/m/Y g:i A') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            <a href="{{ route('jobcard.additional.jobrequest', $job->Jobc_id) }}" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">JobRequest</a>
                            <a href="{{ route('jobcard.additional.part', $job->Jobc_id) }}" class="px-2 py-1 bg-cyan-600 hover:bg-cyan-700 text-white text-xs rounded transition-colors">Spare Parts</a>
                            <a href="{{ route('jobcard.additional.sublet', $job->Jobc_id) }}" class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">Sublet</a>
                            <a href="{{ route('jobcard.additional.consumable', $job->Jobc_id) }}" class="px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs rounded transition-colors">Consumble</a>
                            @if($job->status == 0)
                            <form method="POST" action="{{ route('jobcard.start-working') }}" class="inline"
                                  onsubmit="return confirm('Send RO #{{ $job->Jobc_id }} to workshop?')">
                                @csrf
                                <input type="hidden" name="job_id"         value="{{ $job->Jobc_id }}">
                                <input type="hidden" name="comp_appointed" value="{{ $job->comp_appointed }}">
                                <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors">Start Working</button>
                            </form>
                            @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded font-semibold">In Workshop</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400"><i class="fa fa-inbox text-3xl block mb-2"></i>No open jobcards found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
