@extends('layouts.master')
@section('title', 'Status — Labor')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Labor Status</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">RO#</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Registration</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Labor</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Bay</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Team</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Cost</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Status</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Added</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                
                @forelse($labors as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm font-bold">#{{ $l->Jobc_id }}</td>
                    <td class="px-3 py-2 text-sm text-red-600">{{ $l->Registration }}</td>
                    <td class="px-3 py-2 text-sm">{{ $l->Labor }}</td>
                    <td class="px-3 py-2 text-sm text-red-600">{{ $l->bay }}</td>
                    <td class="px-3 py-2 text-sm text-red-600">{{ $l->team }}</td>
                    
                    <td class="px-3 py-2 text-sm">{{ number_format($l->cost, 0) }}</td>
                    <td class="px-3 py-2 text-sm">
                        @if($l->status == 'Jobclose')
                            <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs">Done</span>
                        @elseif($l->status == '1')
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded text-xs">Assigned</span>
                        @else
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-xs">Pending</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-500">
                        {{ $l->entry_time ? \Carbon\Carbon::parse($l->entry_time)->format('d/m/Y g:i A') : '—' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No labor items found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
