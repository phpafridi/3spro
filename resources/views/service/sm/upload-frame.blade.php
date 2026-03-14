@extends('layouts.master')
@section('title', 'SM - Upload Frame Info')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    <div class="md:col-span-2 bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Add Frame No</h2>
        <form method="POST" action="{{ route('sm.upload-frame.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Frame No <span class="text-red-500">*</span></label>
                <input type="text" name="frame_no" required style="text-transform:uppercase"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-plus mr-2"></i> Add
            </button>
        </form>
    </div>
    <div class="md:col-span-3 bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Frame List
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $frames->count() }}</span>
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Frame No</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Added By</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Added On</th>
                </tr></thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($frames as $i => $f)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-400">{{ $i+1 }}</td>
                        <td class="px-4 py-2 text-sm font-mono font-medium text-gray-800">{{ $f->frame_no }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $f->added_by }}</td>
                        <td class="px-4 py-2 text-sm text-gray-400">{{ $f->added_on }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400 text-sm italic">No frames yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
