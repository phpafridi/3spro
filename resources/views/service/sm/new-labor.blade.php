@extends('layouts.master')
@section('title', 'SM - New Labor Request')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Request New Labor</h2>

    <form method="POST" action="{{ route('sm.new-labor.store') }}">
        @csrf

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Labor <span class="text-red-500">*</span></label>
                <input type="text" name="labor" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Category 1</label>
                    <input type="text" name="cate1"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Category 2</label>
                    <input type="text" name="cate2"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Category 3</label>
                    <input type="text" name="cate3"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Category 4</label>
                    <input type="text" name="cate4"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Category 5</label>
                    <input type="text" name="cate5"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Remarks</label>
                <textarea name="remarks" rows="3"
                          class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500"></textarea>
            </div>

            <button type="submit"
                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">
                Submit Request
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
        <h2 class="text-lg font-medium text-gray-800">Previous Requests</h2>
        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $requests->count() }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-2 px-3 text-xs font-medium text-gray-500">Labor</th>
                    <th class="text-left py-2 px-3 text-xs font-medium text-gray-500">Categories</th>
                    <th class="text-left py-2 px-3 text-xs font-medium text-gray-500">Remarks</th>
                    <th class="text-left py-2 px-3 text-xs font-medium text-gray-500">By</th>
                    <th class="text-left py-2 px-3 text-xs font-medium text-gray-500">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $r)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-2 px-3 font-medium text-gray-800">{{ $r->labor }}</td>
                    <td class="py-2 px-3">
                        <div class="flex flex-wrap gap-1">
                            @if($r->cate1)<span class="text-gray-600 text-xs">{{ $r->cate1 }}</span>@endif
                            @if($r->cate2)<span class="text-gray-600 text-xs">{{ $r->cate2 }}</span>@endif
                            @if($r->cate3)<span class="text-gray-600 text-xs">{{ $r->cate3 }}</span>@endif
                            @if($r->cate4)<span class="text-gray-600 text-xs">{{ $r->cate4 }}</span>@endif
                            @if($r->cate5)<span class="text-gray-600 text-xs">{{ $r->cate5 }}</span>@endif
                        </div>
                    </td>
                    <td class="py-2 px-3 text-gray-600">{{ $r->remarks ?: '-' }}</td>
                    <td class="py-2 px-3 text-gray-600">{{ $r->who_req }}</td>
                    <td class="py-2 px-3 text-gray-400">{{ $r->when_req }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-400">No requests yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
