{{-- resources/views/service/jc/parts-status.blade.php --}}
@extends('layouts.master')

@section('title', 'Job Controller - Parts Status')

@section('sidebar-menu')
    <a href="{{ route('jc.dashboard') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-wrench w-6"></i>
        <span>Jobs Requests</span>
    </a>
    <a href="{{ route('jc.sublet') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.sublet') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-sign-out-alt w-6"></i>
        <span>Sublet Requests</span>
    </a>
    <a href="{{ route('jc.inprogress') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.inprogress') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-edit w-6"></i>
        <span>Inprogress Jobs</span>
    </a>
    <a href="{{ route('jc.parts-status') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.parts-status') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-search-plus w-6"></i>
        <span>Parts Status</span>
    </a>
@endsection

@push('styles')
<style>
    .status-badge {
        @apply px-2 py-1 text-xs font-medium rounded-full;
    }
    .status-pending { @apply bg-yellow-100 text-yellow-800; }
    .status-issued { @apply bg-green-100 text-green-800; }
    .status-na { @apply bg-red-100 text-red-800; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8">
            <button onclick="showTab('parts')" id="tabPartsBtn" class="tab-btn py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                Parts
            </button>
            <button onclick="showTab('consumable')" id="tabConsumableBtn" class="tab-btn py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Consumable
            </button>
        </nav>
    </div>

    <!-- Parts Tab -->
    <div id="partsTab" class="tab-content">
        <div class="bg-white rounded shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Parts Status</h2>
                <div class="w-64">
                    <input type="text" id="searchParts" placeholder="Search..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="partsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jobcard#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reg#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issued Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($partsStatus as $index => $part)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">#{{ $part->RO_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $part->Registration }}</td>
                            <td class="px-6 py-4 text-sm max-w-xs truncate">{{ $part->part_description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($part->status == '0')
                                    <span class="status-badge status-pending">Pending</span>
                                @elseif($part->status == '2')
                                    <span class="status-badge status-na">Not Available</span>
                                @else
                                    <span class="status-badge status-issued">Issued</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($part->entry_datetime)->format('d-M g:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($part->issue_time && $part->issue_time != '0000-00-00 00:00:00')
                                    {{ \Carbon\Carbon::parse($part->issue_time)->format('d-M g:i A') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>No parts found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Consumable Tab -->
    <div id="consumableTab" class="tab-content hidden">
        <div class="bg-white rounded shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Consumable Status</h2>
                <div class="w-64">
                    <input type="text" id="searchConsumable" placeholder="Search..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="consumableTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jobcard#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reg#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issued Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($consumableStatus as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">#{{ $item->RO_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->Registration }}</td>
                            <td class="px-6 py-4 text-sm max-w-xs truncate">{{ $item->cons_description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status == '0')
                                    <span class="status-badge status-pending">Pending</span>
                                @elseif($item->status == '2')
                                    <span class="status-badge status-na">Not Available</span>
                                @else
                                    <span class="status-badge status-issued">Issued</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($item->entry_datetime)->format('d-M g:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($item->issue_time && $item->issue_time != '0000-00-00 00:00:00')
                                    {{ \Carbon\Carbon::parse($item->issue_time)->format('d-M g:i A') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>No consumables found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab switching
    function showTab(tab) {
        const partsTab = document.getElementById('partsTab');
        const consumableTab = document.getElementById('consumableTab');
        const partsBtn = document.getElementById('tabPartsBtn');
        const consumableBtn = document.getElementById('tabConsumableBtn');

        if (tab === 'parts') {
            partsTab.classList.remove('hidden');
            consumableTab.classList.add('hidden');
            partsBtn.classList.add('border-blue-500', 'text-blue-600');
            partsBtn.classList.remove('border-transparent', 'text-gray-500');
            consumableBtn.classList.remove('border-blue-500', 'text-blue-600');
            consumableBtn.classList.add('border-transparent', 'text-gray-500');
        } else {
            partsTab.classList.add('hidden');
            consumableTab.classList.remove('hidden');
            consumableBtn.classList.add('border-blue-500', 'text-blue-600');
            consumableBtn.classList.remove('border-transparent', 'text-gray-500');
            partsBtn.classList.remove('border-blue-500', 'text-blue-600');
            partsBtn.classList.add('border-transparent', 'text-gray-500');
        }
    }

    // Search functionality
    document.getElementById('searchParts')?.addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('#partsTable tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });

    document.getElementById('searchConsumable')?.addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('#consumableTable tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });
</script>
@endpush
