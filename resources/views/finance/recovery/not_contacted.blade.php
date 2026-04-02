@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Non-Active Accounts')
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-bell-slash text-orange-500 mr-2"></i> Non-Active Accounts (No Followup in 7 Days)
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-orange-500 to-red-500">
                <tr>
                    @foreach(['#','Customer','Contact','Last Followup','Actions'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($list as $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('recovery.customer-ledger', ['id'=>$r->Customer_id]) }}" class="text-red-600 hover:underline">{{ $r->cust_name }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $r->contact }}</td>
                    <td class="px-4 py-3 text-xs text-gray-400">{{ $r->last_fol ?? 'Never' }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('recovery.followup', ['id'=>$r->Customer_id]) }}"
                           class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200">Add Followup</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-green-500 font-medium">All accounts are active!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
