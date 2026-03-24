@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Recovered Accounts')
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-check-circle text-green-500 mr-2"></i> Recovered Accounts
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-red-600">
                <tr>
                    @foreach(['#','Customer','Contact','Total Debt','Total Credit','Balance'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($list as $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('recovery.customer-ledger', ['id'=>$r->cust_name]) }}" class="text-red-600 hover:underline">{{ $r->cust_name }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $r->contact }}</td>
                    <td class="px-4 py-3 text-red-600">Rs {{ number_format($r->total_debt) }}</td>
                    <td class="px-4 py-3 text-green-600">Rs {{ number_format($r->total_credit) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 font-medium">CLEARED</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No recovered accounts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
