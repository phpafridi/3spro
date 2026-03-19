@extends('layouts.master')
@section('title', 'Open Repair Orders')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">{{ session('error') }}</div>
@endif
@if($errors->any())
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
</div>
@endif

<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Open Repair Orders</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Jobcard #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Vehicle</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Open DateTime</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobs as $job)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $job->Jobc_id }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Variant }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $job->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ $job->Customer_name }}<br>
                        <span class="text-xs text-gray-500">{{ $job->mobile }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($job->Open_date_time)->format('d/m/Y g:i A') }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1 mb-2">
                            <a href="{{ route('jobcard.additional.jobrequest', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">JobRequest</a>
                            <a href="{{ route('jobcard.additional.part', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-cyan-600 hover:bg-cyan-700 text-white text-xs rounded">Spare Parts</a>
                            <a href="{{ route('jobcard.additional.sublet', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded">Sublet</a>
                            <a href="{{ route('jobcard.additional.consumable', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs rounded">Consumble</a>
                        </div>
                        {{-- Start Working — checks labor first, then sets status=1 --}}
                        <form method="POST" action="{{ route('jobcard.start-working') }}"
                              onsubmit="return confirm('Start working on RO #{{ $job->Jobc_id }}?')">
                            @csrf
                            <input type="hidden" name="job_id"         value="{{ $job->Jobc_id }}">
                            <input type="hidden" name="comp_appointed" value="{{ $job->comp_appointed }}">
                            <button type="submit"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded">
                                Start Working
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        <i class="fa fa-inbox text-3xl block mb-2"></i>
                        No open jobcards found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
