@extends('parts.layout')
@section('title', 'Workshop Return - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Workshop Parts Return</h2>
</div>
@if(session('success'))<div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">{{ session('success') }}</div>@endif
<div class="mb-6">
<h3 class="font-semibold text-gray-700 mb-3">Parts Returns Pending</h3>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
<table class="w-full text-sm">
    <thead class="bg-indigo-50"><tr>
        <th class="px-4 py-3 text-left text-xs text-gray-600">Part #</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600">RO No</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600">Description</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600">Location</th>
        <th class="px-4 py-3 text-center text-xs text-gray-600">Action</th>
    </tr></thead>
    <tbody class="divide-y divide-gray-100">
    @forelse($returnParts as $p)
    <tr class="hover:bg-indigo-50/30">
        <td class="px-4 py-3 font-medium">{{ $p->part_number }}</td>
        <td class="px-4 py-3">{{ $p->RO_no }}</td>
        <td class="px-4 py-3">{{ $p->Description }}</td>
        <td class="px-4 py-3 text-gray-500 text-xs">{{ $p->Location }}</td>
        <td class="px-4 py-3 text-center">
            <form action="{{ route('parts.workshop-return.update') }}" method="POST">
                @csrf
                <input type="hidden" name="not_available_id" value="{{ $p->parts_sale_id }}">
                <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600">Mark N/A</button>
            </form>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No parts returns pending</td></tr>
    @endforelse
    </tbody>
</table>
</div>
</div>
<div>
<h3 class="font-semibold text-gray-700 mb-3">Consumable Returns Pending</h3>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
<table class="w-full text-sm">
    <thead class="bg-purple-50"><tr>
        <th class="px-4 py-3 text-left text-xs text-gray-600">ID</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600">RO No</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600">Customer</th>
        <th class="px-4 py-3 text-center text-xs text-gray-600">Action</th>
    </tr></thead>
    <tbody class="divide-y divide-gray-100">
    @forelse($returnConsumbles as $c)
    <tr class="hover:bg-purple-50/30">
        <td class="px-4 py-3 font-medium">{{ $c->cons_sale_id }}</td>
        <td class="px-4 py-3">{{ $c->RO_no }}</td>
        <td class="px-4 py-3">{{ $c->customer_name }}</td>
        <td class="px-4 py-3 text-center">
            <form action="{{ route('parts.workshop-return.update') }}" method="POST">
                @csrf
                <input type="hidden" name="not_available_cons" value="{{ $c->cons_sale_id }}">
                <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600">Mark N/A</button>
            </form>
        </td>
    </tr>
    @empty
    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No consumable returns pending</td></tr>
    @endforelse
    </tbody>
</table>
</div>
</div>
@endsection
