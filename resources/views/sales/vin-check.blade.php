@extends('layouts.master')
@section('title', 'Sales - VIN Check')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif

<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">VIN Check — Pending
            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-sm rounded-full">{{ $pending->count() }}</span>
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Frame No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Secondary Info</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pending as $i => $v)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 text-sm font-mono font-bold text-gray-900">{{ $v->frameno }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $v->secondary_info ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $v->uploaded_date }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('sales.vin-check') }}" class="inline">
                            @csrf
                            <input type="hidden" name="frameno" value="{{ $v->frameno }}">
                            <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded transition-colors">
                                <i class="fa fa-check mr-1"></i> Mark Done
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-green-600">
                    <i class="fa fa-check-circle mr-1"></i>All VINs checked!
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
