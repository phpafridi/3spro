@extends('layouts.master')
@section('title', 'VIN Check — Pending')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">VIN Check — Pending Actions</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase">RO#</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase">Frame#</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase">Full VIN</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase">List Name</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase">Date</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($records as $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm font-bold">
                        <a href="{{ route('jobcard.invoice') }}?job_id={{ $r->jobcard }}"
                           class="text-blue-600 hover:underline" target="_blank">{{ $r->jobcard }}</a>
                    </td>
                    <td class="px-3 py-2 text-sm font-mono">{{ $r->frameno }}</td>
                    <td class="px-3 py-2 text-sm font-mono">{{ $r->full_vin }}</td>
                    <td class="px-3 py-2 text-sm">
                        <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs font-semibold">{{ $r->list_name }}</span>
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $r->inserteddate ?? '—' }}</td>
                    <td class="px-3 py-2">
                        <div class="flex flex-wrap gap-1">
                            {{-- Vehicle History --}}
                            @if($r->vehicle)
                            <form method="POST" action="{{ route('jobcard.history') }}">
                                @csrf
                                <input type="hidden" name="veh_id" value="{{ $r->vehicle->Vehicle_id }}">
                                <button type="submit"
                                        class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                    Veh History
                                </button>
                            </form>
                            {{-- Open RO --}}
                            <a href="{{ route('jobcard.create', ['vehicle_id' => $r->vehicle->Vehicle_id, 'customer_id' => $r->cust_id]) }}"
                               class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded">
                                Open RO
                            </a>
                            @endif
                            {{-- Mark Done --}}
                            <form method="POST" action="{{ route('jobcard.vin-check') }}"
                                  onsubmit="return confirm('Mark this VIN check as done?')">
                                @csrf
                                <input type="hidden" name="done" value="1">
                                <input type="hidden" name="framno" value="{{ $r->frameno }}">
                                <button type="submit"
                                        class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded">
                                    Done
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        <i class="fa fa-check-circle text-4xl block mb-2 text-green-400"></i>
                        No pending VIN checks.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
