@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Labor List - Manual')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-edit text-indigo-500 mr-2"></i> Labor List (Manual Edit)
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-indigo-600 to-purple-600">
                <tr>
                    @foreach(['#','Labor','Cat1','Cat2','Cat3','Cat4','Cat5'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($labors as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-medium">{{ $l->Labor }}</td>
                    <td class="px-4 py-3">{{ number_format($l->Cate1) }}</td>
                    <td class="px-4 py-3">{{ number_format($l->Cate2) }}</td>
                    <td class="px-4 py-3">{{ number_format($l->Cate3) }}</td>
                    <td class="px-4 py-3">{{ number_format($l->Cate4) }}</td>
                    <td class="px-4 py-3">{{ number_format($l->Cate5) }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No labor entries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
