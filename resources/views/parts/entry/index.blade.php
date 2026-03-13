{{-- resources/views/parts/entry/index.blade.php --}}
@extends('parts.layout')

@section('title', 'Workshop Requisitions - Parts')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Workshop Requisitions</h2>
    <p class="text-sm text-gray-500 mt-1">Pending parts requested from workshop</p>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">JC #</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Issued/Req</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit Price</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reg No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($workshopParts as $part)
                <tr class="hover:bg-indigo-50/30 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $part->RO_no }}</td>
                    <td class="px-4 py-3">
                        <p class="text-gray-800">{{ $part->Description }}</p>
                        <p class="text-xs text-gray-400">{{ $part->part_number }}</p>
                    </td>
                    <td class="px-4 py-3">{{ $part->issued_qty ?? 0 }}/{{ $part->req_qty ?? $part->quantity }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ number_format($part->unitprice ?? 0, 2) }}</td>
                    <td class="px-4 py-3">
                        <p class="text-red-600 font-medium">{{ $part->customer_name }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $part->Veh_reg_no ?? '-' }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        {{ $part->entry_datetime ? \Carbon\Carbon::parse($part->entry_datetime)->diffForHumans() : '-' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2 flex-wrap">
                            <form action="{{ route('parts.index') }}" method="POST">
                                @csrf
                                <input type="hidden" name="issue_part_id" value="{{ $part->parts_sale_id }}">
                                <button type="submit"
                                    class="px-3 py-1 bg-indigo-600 text-white text-xs rounded-lg hover:bg-indigo-700 transition-colors">
                                    Issue Part
                                </button>
                            </form>
                            <form action="{{ route('parts.workshop-return.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="not_available_id" value="{{ $part->parts_sale_id }}">
                                <button type="submit"
                                    class="px-3 py-1 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600 transition-colors">
                                    N/A
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                        <i class="fa fa-inbox text-3xl mb-2 block"></i>
                        No pending workshop requisitions
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
