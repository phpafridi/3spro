@extends('parts.layout')
@section('title','Appointment Sheet')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">MRS Appointment Sheet</h2>
<form method="GET" class="flex gap-2 mb-4">
    <input type="date" name="date" value="{{ $date }}"
           class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-red-400">
    <button class="bg-red-600 text-white px-4 py-1.5 rounded text-sm hover:bg-red-700">View</button>
</form>
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-orange-500 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Appointments — {{ $date }}</h3>
        <span class="text-sm text-white">{{ $rows->count() }} appointments</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">CRO</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Customer</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Variant</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Parts</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Status</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Time</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-orange-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->CRO }}</td>
                    <td class="px-3 py-2">{{ $r->CustomerName }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->Variant }}</td>
                    <td class="px-3 py-2">{{ $r->parts }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-0.5 rounded text-xs {{ $r->parts_status == 1 ? 'bg-green-100 text-green-700' : ($r->parts_status == 3 ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ $r->parts_status == 1 ? 'Available' : ($r->parts_status == 3 ? 'Not Available' : 'Pending') }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->appt_datetime }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-3 py-6 text-center text-gray-400">No appointments for this date</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
