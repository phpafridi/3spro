@extends('layouts.master')
@section('title', 'SM - Labor Change')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>@endif
<div class="bg-white rounded shadow-sm p-6 mb-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Labor Price Change <span class="text-sm font-normal text-gray-400">(Increase only)</span></h2>
    <form method="GET" action="{{ route('sm.labor-change') }}" class="flex gap-2">
        <input type="text" name="jobc_id" value="{{ $jobId ?? '' }}" required placeholder="RO No..."
               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
            <i class="fa fa-search mr-1"></i> Search
        </button>
    </form>
</div>
@if($labors->isNotEmpty())
<div class="bg-white rounded shadow-sm p-6">
    <h3 class="font-semibold text-gray-700 mb-4">Labor for RO# {{ $jobId }}
        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $labors->count() }}</span>
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Cost</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">New Cost</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($labors as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $l->Labor_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $l->Labor }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $l->type }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-800">{{ number_format($l->cost,0) }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('sm.labor-change.update') }}" class="flex gap-2 items-center">
                            @csrf
                            <input type="hidden" name="Labor_id" value="{{ $l->Labor_id }}">
                            <input type="hidden" name="orgcost" value="{{ $l->cost }}">
                            <input type="hidden" name="ro_no" value="{{ $jobId }}">
                            <input type="number" name="cost" min="{{ $l->cost + 1 }}" value="{{ $l->cost }}" step="1" required
                                   class="w-28 border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </td>
                    <td class="px-4 py-3">
                            <button type="submit" class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">
                                <i class="fa fa-save mr-1"></i> Update
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif($jobId ?? false)
<div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded">No labor found for RO# {{ $jobId }}.</div>
@endif
@endsection
