@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Followup History')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-history text-indigo-500 mr-2"></i> Followup History: {{ $id }}
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-indigo-600 to-purple-600">
                <tr>
                    @foreach(['#','Date','Contact Type','Remarks'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($followups as $f)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $f->Datetime }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs bg-sky-100 text-sky-700">{{ $f->Contact_type }}</span>
                    </td>
                    <td class="px-4 py-3">{{ $f->Remarks }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No followup history.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        <a href="{{ route('recovery.followup', ['id'=>$id]) }}"
           class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm">+ Add Followup</a>
    </div>
</div>
@endsection
