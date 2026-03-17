@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Customer Clearance')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">
        <i class="fas fa-check-double text-indigo-500 mr-2"></i> Clearance: {{ $id }}
    </h2>
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-red-50 rounded-xl p-4 text-center">
            <p class="text-xs text-gray-500">Total Debit</p>
            <p class="text-xl font-bold text-red-600">Rs {{ number_format($debts->sum('Debt_amount')) }}</p>
        </div>
        <div class="bg-green-50 rounded-xl p-4 text-center">
            <p class="text-xs text-gray-500">Total Credit</p>
            <p class="text-xl font-bold text-green-600">Rs {{ number_format($credits->sum('cr_amount')) }}</p>
        </div>
        <div class="rounded-xl p-4 text-center {{ $balance > 0 ? 'bg-orange-50' : 'bg-emerald-50' }}">
            <p class="text-xs text-gray-500">Balance</p>
            <p class="text-xl font-bold {{ $balance > 0 ? 'text-orange-600' : 'text-emerald-600' }}">
                Rs {{ number_format($balance) }}
            </p>
            @if($balance <= 0)
            <span class="text-xs bg-green-500 text-white px-2 py-0.5 rounded-full mt-1 inline-block">CLEARED</span>
            @endif
        </div>
    </div>
    <a href="{{ route('recovery.customer-ledger', ['id'=>$id]) }}"
       class="text-sm text-indigo-600 hover:underline">← View Full Ledger</a>
</div>
@endsection
