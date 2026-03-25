@extends('parts.layout')
@section('title', 'Unclosed Requisitions - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 text-red-600">Unclosed Requisitions</h2>
    <p class="text-sm text-gray-500 mt-1">Workshop labor orders that are still open</p>
</div>
@if(session('success'))<div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">{{ session('success') }}</div>@endif
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gradient-to-r from-red-50 to-orange-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Labor ID</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">RO No</th>
             
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            
        @forelse($unclosed as $u)
        <tr class="hover:bg-red-50/30">
            <td class="px-4 py-3 font-medium text-gray-800">{{ $u->Labor_id }}</td>
            <td class="px-4 py-3 text-gray-700">{{ $u->RO_no }}</td>
            
            <td class="px-4 py-3"><span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">{{ $u->status }}</span></td>
            <td class="px-4 py-3 text-center">
                <form action="{{ route('parts.unclosed-req.close') }}" method="POST" onsubmit="return confirm('Close this requisition?')">
                    @csrf
                    <input type="hidden" name="Labor_id" value="{{ $u->Labor_id }}">
                    <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition-colors">Close</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">No unclosed requisitions</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
