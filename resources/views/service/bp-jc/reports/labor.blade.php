@extends('layouts.master')
@include('service.partials.bp-jc-sidebar')

@section('title', 'BP-JC - Labor Type Report')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
            Labor Type Report - Body & Paint
        </h2>
    </div>

    <!-- Filter Form -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('bp-jc.report.labor') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-filter mr-1"></i> Apply Filter
                </button>
                <a href="{{ route('bp-jc.report.labor') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm ml-2">
                    <i class="fas fa-undo mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
            <p class="text-sm text-blue-600 font-medium">Total Jobs</p>
            <p class="text-2xl font-bold text-gray-800">{{ $labors->count() }}</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
            <p class="text-sm text-green-600 font-medium">Workshop</p>
            <p class="text-2xl font-bold text-gray-800">{{ $labors->where('type', 'Workshop')->count() }}</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
            <p class="text-sm text-purple-600 font-medium">Vendor</p>
            <p class="text-2xl font-bold text-gray-800">{{ $labors->where('type', 'Vendor')->count() }}</p>
        </div>
        <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-yellow-600 font-medium">Warranty</p>
            <p class="text-2xl font-bold text-gray-800">{{ $labors->where('type', 'Warranty')->count() }}</p>
        </div>
    </div>

    <!-- Results Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entry Time</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($labors as $labor)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $labor->RO_no }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $labor->Registration }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $labor->Labor }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($labor->type == 'Workshop')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Workshop</span>
                        @elseif($labor->type == 'Vendor')
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Vendor</span>
                        @elseif($labor->type == 'Warranty')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Warranty</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">{{ $labor->type }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($labor->cost, 2) }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($labor->status == 'Job Assign')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">In Progress</span>
                        @elseif($labor->status == 'Jobclose')
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Completed</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ date('d-m-Y H:i', strtotime($labor->entry_time)) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No records found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Export Button -->
    <div class="mt-6 flex justify-end">
        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-print mr-1"></i> Print Report
        </button>
    </div>
</div>
@endsection
