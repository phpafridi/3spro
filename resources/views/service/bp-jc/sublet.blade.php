@extends('layouts.master')
@section('title', 'BP - Pending Sublets')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Pending Sublets
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $sublets->count() }}</span>
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sublet</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SA</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entry Time</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sublets as $i => $s)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $s->RO_no }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $s->Sublet }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $s->qty }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $s->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $s->Variant }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $s->SA }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $s->entry_datetime }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400"><i class="fa fa-check-circle mr-1 text-green-400"></i>No pending sublets.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
