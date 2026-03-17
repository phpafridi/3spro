@extends('layouts.master')
@include('service.partials.bp-jc-sidebar')

@section('title', 'BP-JC - Labor Detail Report')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-chart-bar text-indigo-500 mr-2"></i>
            Labor Detail Report - Body & Paint
        </h2>
    </div>

    <!-- Filter Form -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('bp-jc.report.labor-detail') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="Job Assign" {{ request('status') == 'Job Assign' ? 'selected' : '' }}>In Progress</option>
                    <option value="Jobclose" {{ request('status') == 'Jobclose' ? 'selected' : '' }}>Completed</option>
                    <option value="" {{ request('status') === '' && request('status') !== null ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm w-full">
                    <i class="fas fa-filter mr-1"></i> Apply
                </button>
            </div>
            <div class="flex items-end">
                <a href="{{ route('bp-jc.report.labor-detail') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm w-full text-center">
                    <i class="fas fa-undo mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
        <div class="bg-indigo-50 rounded-lg p-3">
            <p class="text-xs text-indigo-600 font-medium">Total Jobs</p>
            <p class="text-xl font-bold text-gray-800">{{ $labors->count() }}</p>
        </div>
        <div class="bg-blue-50 rounded-lg p-3">
            <p class="text-xs text-blue-600 font-medium">In Progress</p>
            <p class="text-xl font-bold text-gray-800">{{ $labors->where('status', 'Job Assign')->count() }}</p>
        </div>
        <div class="bg-green-50 rounded-lg p-3">
            <p class="text-xs text-green-600 font-medium">Completed</p>
            <p class="text-xl font-bold text-gray-800">{{ $labors->where('status', 'Jobclose')->count() }}</p>
        </div>
        <div class="bg-yellow-50 rounded-lg p-3">
            <p class="text-xs text-yellow-600 font-medium">Pending</p>
            <p class="text-xl font-bold text-gray-800">{{ $labors->whereNull('status')->orWhere('status', '')->count() }}</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-3">
            <p class="text-xs text-purple-600 font-medium">Total Cost</p>
            <p class="text-xl font-bold text-gray-800">{{ number_format($labors->sum('cost'), 2) }}</p>
        </div>
        <div class="bg-pink-50 rounded-lg p-3">
            <p class="text-xs text-pink-600 font-medium">Avg Cost/Job</p>
            <p class="text-xl font-bold text-gray-800">
                {{ $labors->count() > 0 ? number_format($labors->sum('cost') / $labors->count(), 2) : 0 }}
            </p>
        </div>
    </div>

    <!-- Results Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">S.No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobile</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Team</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bay</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entry Time</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($labors as $index => $labor)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $labor->RO_no }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $labor->Customer_name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $labor->mobile ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $labor->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $labor->Labor }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($labor->type == 'Workshop')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">WS</span>
                        @elseif($labor->type == 'Vendor')
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">VD</span>
                        @elseif($labor->type == 'Warranty')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">WR</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">{{ substr($labor->type,0,3) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($labor->cost, 2) }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($labor->status == 'Job Assign')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Progress</span>
                        @elseif($labor->status == 'Jobclose')
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Done</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $labor->team ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $labor->bay ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ date('d-m-Y H:i', strtotime($labor->entry_time)) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="px-4 py-6 text-center text-gray-500">No records found</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="7" class="px-4 py-3 text-right text-sm font-medium text-gray-700">Totals:</td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">{{ number_format($labors->sum('cost'), 2) }}</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Export Buttons -->
    <div class="mt-6 flex justify-end gap-2">
        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-print mr-1"></i> Print
        </button>
        <a href="{{ route('bp-jc.report.labor-detail', array_merge(request()->query(), ['export' => 'excel'])) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-file-excel mr-1"></i> Export Excel
        </a>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body { background: white; }
        .bg-white { box-shadow: none; }
        button, a { display: none; }
    }
</style>
@endpush
@endsection
