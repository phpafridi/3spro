@extends('layouts.master')
@section('title', 'SM - Unique VINs')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Unique VINs / Frame Numbers
        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $vins->count() }}</span>
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Frame No / VIN</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($vins as $i => $v)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 text-sm font-mono font-medium text-red-600">{{ $v->Frame_no }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
