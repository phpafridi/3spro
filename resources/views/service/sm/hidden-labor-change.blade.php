@extends('layouts.master')
@section('title', 'SM - Advanced Labor/Sublet Change')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>@endif
<div class="bg-white rounded shadow-sm p-6 mb-4">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Advanced Change <span class="text-sm font-normal text-gray-400">(Labor + Sublet + Delete)</span></h2>
    <form method="GET" action="{{ route('sm.hidden-labor-change') }}" class="flex gap-2">
        <input type="text" name="jobc_id" value="{{ $jobId ?? '' }}" required placeholder="RO No..."
               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
            <i class="fa fa-search mr-1"></i> Search
        </button>
    </form>
</div>

@if($labors->isNotEmpty())
<div class="bg-white rounded shadow-sm p-6 mb-4">
    <h3 class="font-semibold text-gray-700 mb-3">Labor — RO# {{ $jobId }}</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Current Cost</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">New Cost</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Update</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Delete</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($labors as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->Labor_id }}</td>
                    <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $l->Labor }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->type }}</td>
                    <td class="px-4 py-2 text-sm font-bold text-gray-800">{{ number_format($l->cost,0) }}</td>
                    <td class="px-4 py-2">
                        <form method="POST" action="{{ route('sm.hidden-labor-change.update') }}" class="flex gap-2 items-center">
                            @csrf
                            <input type="hidden" name="Labor_id" value="{{ $l->Labor_id }}">
                            <input type="hidden" name="orgcost" value="{{ $l->cost }}">
                            <input type="hidden" name="ro_no" value="{{ $jobId }}">
                            <input type="number" name="cost" value="{{ $l->cost }}" step="1" required
                                   class="w-24 border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </td>
                    <td class="px-4 py-2">
                            <button type="submit" class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">
                                <i class="fa fa-save"></i>
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-2">
                        <form method="POST" action="{{ route('sm.hidden-labor-change.update') }}" onsubmit="return confirm('Delete this labor?')">
                            @csrf
                            <input type="hidden" name="deleted_Labor_id" value="{{ $l->Labor_id }}">
                            <input type="hidden" name="ro_no" value="{{ $jobId }}">
                            <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($sublets->isNotEmpty())
<div class="bg-white rounded shadow-sm p-6 mb-4">
    <h3 class="font-semibold text-gray-700 mb-3">Sublets — RO# {{ $jobId }}</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sublet</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Current Total</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">New Total</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Update</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($sublets as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $s->sublet_id }}</td>
                    <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $s->Sublet }}</td>
                    <td class="px-4 py-2 text-sm font-bold text-gray-800">{{ number_format($s->total,2) }}</td>
                    <td class="px-4 py-2">
                        <form method="POST" action="{{ route('sm.hidden-labor-change.update') }}" class="flex gap-2 items-center">
                            @csrf
                            <input type="hidden" name="sublet_id" value="{{ $s->sublet_id }}">
                            <input type="hidden" name="orgtotal" value="{{ $s->total }}">
                            <input type="hidden" name="ro_no" value="{{ $jobId }}">
                            <input type="number" name="total" value="{{ $s->total }}" step="0.01" required
                                   class="w-28 border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </td>
                    <td class="px-4 py-2">
                            <button type="submit" class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">
                                <i class="fa fa-save"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if(($jobId ?? false) && $labors->isEmpty() && $sublets->isEmpty())
<div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded">No labor or sublets found for RO# {{ $jobId }}.</div>
@endif
@endsection
