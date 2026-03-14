@extends('layouts.master')
@section('title', 'Add Labor - RO# ' . $jobId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    <div class="md:col-span-2 bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Add Labor — RO# {{ $jobId }}</h2>
            <a href="{{ route('jobcard.additional', $jobId) }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa fa-arrow-left mr-1"></i>Back</a>
        </div>
        <form method="POST" action="{{ route('jobcard.additional.jobrequest.store') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="job_id" value="{{ $jobId }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Labor / Job <span class="text-red-500">*</span></label>
                <select name="jobrequest" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Labor --</option>
                    @foreach($laborList as $labor)
                    <option value="{{ $labor->Labor }}">{{ $labor->Labor }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type" id="labor_type" required onchange="togglePrice(this)" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Workshop">Workshop</option>
                    <option value="Sublet">Sublet</option>
                    <option value="Warranty">Warranty</option>
                    <option value="Goodwill">Goodwill</option>
                    <option value="Campaign">Campaign</option>
                </select>
            </div>
            <div id="price_row">
                <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                <input type="number" name="price" id="price_input" step="0.01" min="0" value="0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                <input type="text" name="reason" placeholder="(for warranty/goodwill)" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-plus mr-2"></i> Add Labor
            </button>
        </form>
    </div>
    <div class="md:col-span-3 bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-3">Current Labor
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $labors->count() }}</span>
        </h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Added</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($labors as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $l->Labor }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->type }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($l->cost,0) }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->status ?: 'Pending' }}</td>
                    <td class="px-4 py-2 text-sm">
                        @if($l->Additional)<span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">Additional</span>@endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-4 text-center text-gray-400 text-sm italic">No labor added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
function togglePrice(sel) {
    document.getElementById('price_row').style.display = sel.value === 'Workshop' ? '' : 'none';
    if (sel.value !== 'Workshop') document.getElementById('price_input').value = 0;
}
document.addEventListener('DOMContentLoaded', function() { togglePrice(document.getElementById('labor_type')); });
</script>
@endpush
@endsection
