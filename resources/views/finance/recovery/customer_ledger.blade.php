@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Customer Ledger')
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-book text-red-500 mr-2"></i> Ledger: {{ $id }}
        </h2>
        <div class="flex gap-3">
            <a href="{{ route('recovery.add-debt', ['id'=>$id]) }}"
               class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded text-sm">+ Debit</a>
            <a href="{{ route('recovery.add-credit', ['inv'=>$id]) }}"
               class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded text-sm">+ Credit</a>
        </div>
    </div>
    {{-- Balance Summary --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-red-50 rounded p-4 text-center">
            <p class="text-xs text-gray-500">Total Debit</p>
            <p class="text-xl font-bold text-red-600">Rs {{ number_format($totalDebt) }}</p>
        </div>
        <div class="bg-green-50 rounded p-4 text-center">
            <p class="text-xs text-gray-500">Total Credit</p>
            <p class="text-xl font-bold text-green-600">Rs {{ number_format($totalCredit) }}</p>
        </div>
        <div class="rounded p-4 text-center {{ $balance > 0 ? 'bg-orange-50' : 'bg-emerald-50' }}">
            <p class="text-xs text-gray-500">Balance</p>
            <p class="text-xl font-bold {{ $balance > 0 ? 'text-orange-600' : 'text-emerald-600' }}">Rs {{ number_format($balance) }}</p>
        </div>
    </div>
    {{-- Debits --}}
    <h3 class="font-semibold text-gray-700 mb-2">Debit Entries</h3>
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-red-50"><tr>
                @foreach(['Invoice','Vehicle','Date','Amount'] as $h)
                <th class="px-4 py-2 text-left text-xs text-gray-600 uppercase">{{ $h }}</th>
                @endforeach
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($debts as $d)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 font-mono">{{ $d->Invoice_no }}</td>
                    <td class="px-4 py-2">{{ $d->Vehicle_name }}</td>
                    <td class="px-4 py-2 text-xs text-gray-500">{{ $d->Db_date }}</td>
                    <td class="px-4 py-2 font-medium text-red-600">Rs {{ number_format($d->Debt_amount) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Credits --}}
    <h3 class="font-semibold text-gray-700 mb-2">Credit Entries</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-green-50"><tr>
                @foreach(['Invoice','Payment Method','RT No','Date','Amount','Remarks'] as $h)
                <th class="px-4 py-2 text-left text-xs text-gray-600 uppercase">{{ $h }}</th>
                @endforeach
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($credits as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 font-mono">{{ $c->dm_invoice }}</td>
                    <td class="px-4 py-2">{{ $c->Payment_method }}</td>
                    <td class="px-4 py-2 font-mono text-xs">{{ $c->RT_no }}</td>
                    <td class="px-4 py-2 text-xs text-gray-500">{{ $c->cr_date }}</td>
                    <td class="px-4 py-2 font-medium text-green-600">Rs {{ number_format($c->cr_amount) }}</td>
                    <td class="px-4 py-2 text-xs text-gray-500">{{ $c->remarks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
