@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Reopen Jobcard')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-undo text-indigo-500 mr-2"></i> Reopen JC Requests
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-indigo-600 to-purple-600">
                <tr>
                    @foreach(['#','JC ID','SM Reason','Requested By','SM Date','Action'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pending as $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-mono font-bold">{{ $r->jobc_id }}</td>
                    <td class="px-4 py-3">{{ $r->SM_reason }}</td>
                    <td class="px-4 py-3">{{ $r->SM }}</td>
                    <td class="px-4 py-3">{{ $r->sm_datetime }}</td>
                    <td class="px-4 py-3 flex gap-2">
                        {{-- Approve --}}
                        <form method="POST" action="{{ route('accountant.reopen-jc.process') }}">
                            @csrf
                            <input type="hidden" name="Jobc_id" value="{{ $r->jobc_id }}">
                            <input type="hidden" name="unjc_Id" value="{{ $r->unjc_Id }}">
                            <button class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-lg">Approve</button>
                        </form>
                        {{-- Reject --}}
                        <form method="POST" action="{{ route('accountant.reopen-jc.process') }}">
                            @csrf
                            <input type="hidden" name="unclose_id" value="{{ $r->unjc_Id }}">
                            <button class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg">Reject</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No pending reopen requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
