@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Jobcard History')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-history text-indigo-500 mr-2"></i> Jobcard History
    </h2>
    <form method="GET" action="{{ route('accountant.history') }}" class="flex gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by JC#, customer, or reg..."
            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">Search</button>
    </form>
    @if($jobs !== null)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-indigo-600 to-purple-600">
                <tr>
                    @foreach(['JC #','Customer','Vehicle','Reg','Mobile','SA','Status','Invoice','Total','Closing Time'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($jobs as $j)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono font-bold">{{ $j->Jobc_id }}</td>
                    <td class="px-4 py-3">{{ $j->Customer_name }}</td>
                    <td class="px-4 py-3">{{ $j->Variant }}</td>
                    <td class="px-4 py-3">{{ $j->Registration }}</td>
                    <td class="px-4 py-3">{{ $j->mobile }}</td>
                    <td class="px-4 py-3">{{ $j->SA }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600">{{ $j->status }}</span>
                    </td>
                    <td class="px-4 py-3 font-mono">{{ $j->Invoice_id ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $j->Total ? 'Rs '.number_format($j->Total) : '—' }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $j->closing_time }}</td>
                </tr>
                @empty
                <tr><td colspan="10" class="px-4 py-8 text-center text-gray-400">No results found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
