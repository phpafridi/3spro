@extends('layouts.master')
@include('finance.cashier.sidebar')

@section('title', 'Cashier - Open Job Cards')

@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-folder-open text-green-500 mr-2"></i>
            Open Job Cards (Initial RO)
        </h2>
        <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">
            {{ count($openJobs) }} Open
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-green-600 to-green-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Jobcard#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Mobile</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Registration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Clock ON</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">SA</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($openJobs as $job)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-mono">#{{ $job->Jobc_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $job->Variant }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $job->Customer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $job->mobile }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $job->Registration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($job->Open_date_time)->format('d-M g:i A') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $job->SA }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('cashier.print-initial-ro') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="job_id" value="{{ $job->Jobc_id }}">
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                                <i class="fas fa-print mr-1"></i> Print RO
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                        <p>No open job cards found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
