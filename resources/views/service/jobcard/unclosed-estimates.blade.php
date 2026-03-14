@extends('layouts.master')
@section('title', 'Unclosed Estimates')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-5">
        <h2 class="text-xl font-semibold text-gray-800">Open Estimates</h2>
        <a href="{{ route('jobcard.estimate.create') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
            <i class="fa fa-plus mr-2"></i> New Estimate
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Est ID</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($estimates as $est)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $est->est_id }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $est->estimate_type }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $est->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $est->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $est->Variant }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $est->payment_mode }}</td>
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $est->entry_datetime }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            <a href="{{ route('jobcard.estimate.ro', $est->est_id) }}" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">View</a>
                            <a href="{{ route('jobcard.estimate.labor', $est->est_id) }}" class="px-2 py-1 bg-cyan-600 hover:bg-cyan-700 text-white text-xs rounded transition-colors">Labor</a>
                            <a href="{{ route('jobcard.estimate.part', $est->est_id) }}" class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">Parts</a>
                            <a href="{{ route('jobcard.estimate.consumable', $est->est_id) }}" class="px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs rounded transition-colors">Cons.</a>
                            <a href="{{ route('jobcard.estimate.sublet', $est->est_id) }}" class="px-2 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded transition-colors">Sublet</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No open estimates.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
