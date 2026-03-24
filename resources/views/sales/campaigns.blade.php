@extends('layouts.master')
@section('title', 'Sales - Campaigns')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>@endif

<div class="bg-white rounded shadow-sm p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-5">Add New Campaign</h2>
    <form method="POST" action="{{ route('sales.campaigns.store') }}">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Name <span class="text-red-500">*</span></label>
                <input type="text" name="campaign_name" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nature</label>
                <select name="nature" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Safety Recall</option>
                    <option>Service Campaign</option>
                    <option>Customer Satisfaction Program</option>
                    <option>Other</option>
                </select>
            </div>
            <div></div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valid From</label>
                <input type="date" name="cfrom" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valid To</label>
                <input type="date" name="cto" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <button type="submit" class="mt-5 px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
            <i class="fa fa-plus mr-2"></i> Add Campaign
        </button>
    </form>
</div>

<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Campaigns
        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $campaigns->count() }}</span>
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nature</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">From</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">To</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($campaigns as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $c->campaign_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $c->campaign_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->nature }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->c_from }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->c_to }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($c->status=='Active')
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Active</span>
                        @else
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('sales.campaigns.toggle') }}" class="inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $c->campaign_id }}">
                            <input type="hidden" name="status" value="{{ $c->status=='Active' ? 'Inactive' : 'Active' }}">
                            <button type="submit" class="px-2 py-1 {{ $c->status=='Active' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700' }} text-white text-xs rounded transition-colors">
                                {{ $c->status=='Active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No campaigns yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
