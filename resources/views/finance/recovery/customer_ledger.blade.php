@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Customer Ledger')
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-book text-red-500"></i> {{ $custName }}
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Customer ID: {{ $id }} &nbsp;·&nbsp; Full Account Ledger</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('recovery.followup', ['id' => $id]) }}"
               class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded text-sm hover:bg-yellow-200">
               <i class="fas fa-phone mr-1"></i>Followup
            </a>
            <a href="{{ route('recovery.history', ['id' => $id]) }}"
               class="px-3 py-1.5 bg-sky-100 text-sky-700 rounded text-sm hover:bg-sky-200">
               <i class="fas fa-history mr-1"></i>History
            </a>
            <a href="{{ route('recovery.add-credit', ['inv' => $debts->first()->Invoice_no ?? '']) }}"
               class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-sm">
               <i class="fas fa-hand-holding-usd mr-1"></i>+ Credit
            </a>
        </div>
    </div>

    {{-- Balance Summary --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-red-50 rounded p-4 text-center">
            <p class="text-xs text-gray-500 mb-1">Total Debit</p>
            <p class="text-xl font-bold text-red-600">Rs {{ number_format($totalDebt) }}</p>
        </div>
        <div class="bg-green-50 rounded p-4 text-center">
            <p class="text-xs text-gray-500 mb-1">Total Credit</p>
            <p class="text-xl font-bold text-green-600">Rs {{ number_format($totalCredit) }}</p>
        </div>
        <div class="rounded p-4 text-center {{ $balance > 0 ? 'bg-orange-50' : 'bg-emerald-50' }}">
            <p class="text-xs text-gray-500 mb-1">Balance</p>
            <p class="text-xl font-bold {{ $balance > 0 ? 'text-orange-600' : 'text-emerald-600' }}">
                Rs {{ number_format(abs($balance)) }}
            </p>
            @if($balance <= 0)
            <span class="text-xs bg-green-500 text-white px-2 py-0.5 rounded-full">CLEARED</span>
            @endif
        </div>
    </div>

    {{-- Debit Entries --}}
    <h3 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span> Debit Entries
    </h3>
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-red-50">
                <tr>
                    @foreach(['Invoice No','Vehicle','Registration','Date','Amount','Remarks'] as $h)
                    <th class="px-4 py-2 text-left text-xs text-gray-600 uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($debts as $d)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 font-mono font-bold text-gray-700">{{ $d->Invoice_no }}</td>
                    <td class="px-4 py-2">{{ $d->Vehicle_name }}</td>
                    <td class="px-4 py-2 text-xs text-gray-500">{{ $d->Registration }}</td>
                    <td class="px-4 py-2 text-xs text-gray-500">{{ $d->Db_date }}</td>
                    <td class="px-4 py-2 font-medium text-red-600">Rs {{ number_format($d->Debt_amount) }}</td>
                    <td class="px-4 py-2 text-xs text-gray-400">{{ $d->Remarks }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400 text-xs">No debit entries.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Credit Entries --}}
    <h3 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span> Credit / Payment Entries
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-green-50">
                <tr>
                    @foreach(['Invoice','Payment Method','RT / Ref No','Date','Amount','Remarks'] as $h)
                    <th class="px-4 py-2 text-left text-xs text-gray-600 uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($credits as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 font-mono">{{ $c->dm_invoice }}</td>
                    <td class="px-4 py-2">{{ $c->Payment_method }}</td>
                    <td class="px-4 py-2 font-mono text-xs">{{ $c->RT_no ?: '—' }}</td>
                    <td class="px-4 py-2 text-xs text-gray-500">{{ $c->cr_date }}</td>
                    <td class="px-4 py-2 font-medium text-green-600">Rs {{ number_format($c->cr_amount) }}</td>
                    <td class="px-4 py-2 text-xs text-gray-400">{{ $c->remarks }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400 text-xs">No payments recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
