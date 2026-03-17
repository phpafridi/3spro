@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Labor Requests')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-clipboard-list text-indigo-500 mr-2"></i> Labor Requests
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-indigo-600 to-purple-600">
                <tr>
                    @foreach(['#','Labor','Cat1','Cat2','Cat3','Cat4','Cat5','Remarks','Requested By','Requested On','Action'] as $h)
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($requests as $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-3">{{ $loop->iteration }}</td>
                    <td class="px-3 py-3 font-medium">{{ $r->labor }}</td>
                    <td class="px-3 py-3">{{ $r->cate1 }}</td>
                    <td class="px-3 py-3">{{ $r->cate2 }}</td>
                    <td class="px-3 py-3">{{ $r->cate3 }}</td>
                    <td class="px-3 py-3">{{ $r->cate4 }}</td>
                    <td class="px-3 py-3">{{ $r->cate5 }}</td>
                    <td class="px-3 py-3">{{ $r->remarks }}</td>
                    <td class="px-3 py-3">{{ $r->who_req }}</td>
                    <td class="px-3 py-3 text-xs text-gray-500">{{ $r->when_req }}</td>
                    <td class="px-3 py-3 flex gap-2">
                        <form method="POST" action="{{ route('accountant.labor-request.process') }}">
                            @csrf
                            <input type="hidden" name="req_id" value="{{ $r->req_id }}">
                            <input type="hidden" name="addto_list" value="1">
                            <button class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-lg">Accept</button>
                        </form>
                        <form method="POST" action="{{ route('accountant.labor-request.process') }}">
                            @csrf
                            <input type="hidden" name="req_id" value="{{ $r->req_id }}">
                            <input type="hidden" name="rejected" value="1">
                            <button class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg">Reject</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" class="px-4 py-8 text-center text-gray-400">No pending requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
