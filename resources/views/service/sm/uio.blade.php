@extends('layouts.master')
@section('title', 'SM - UIO Update')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<div class="bg-white rounded shadow-sm p-6 max-w-2xl">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Units In Operation (UIO)</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">UIO</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Updated</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">By</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($uios as $u)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-bold text-gray-800">{{ $u->UIO_Year }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($u->UIO) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $u->datentime }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $u->user }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('sm.uio.update') }}" class="flex gap-2 items-center">
                            @csrf
                            <input type="hidden" name="year" value="{{ $u->UIO_Year }}">
                            <input type="number" name="UIO" value="{{ $u->UIO }}" required
                                   class="w-28 border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
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
@endsection
