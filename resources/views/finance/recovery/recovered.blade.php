@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Recovered Accounts')
@section('content')

<div class="bg-white rounded shadow-sm p-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                Recovered Accounts
            </h2>
            <p class="text-sm text-gray-500 mt-0.5">Customers whose full outstanding balance has been cleared</p>
        </div>
        <span class="px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
            {{ $list->count() }} Recovered
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-green-600 to-emerald-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider w-10">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Contact</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Total Debt</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Total Recovered</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Balance</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($list as $r)
                <tr class="hover:bg-green-50 transition-colors duration-100">
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-800">
                        <a href="{{ route('recovery.customer-ledger', ['id' => $r->cust_name]) }}"
                           class="text-green-700 hover:text-green-900 hover:underline flex items-center gap-1">
                            <i class="fas fa-user text-xs text-green-400"></i>
                            {{ $r->cust_name }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $r->contact ?: '—' }}</td>
                    <td class="px-4 py-3 text-right font-medium text-red-600">
                        Rs {{ number_format($r->total_debt) }}
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-green-600">
                        Rs {{ number_format($r->total_credit ?? ($r->total_debt - $r->balance)) }}
                    </td>
                    <td class="px-4 py-3 text-center font-semibold">
                        @if(($r->balance ?? 0) < 0)
                            <span class="text-green-700">Rs {{ number_format(abs($r->balance)) }} <span class="text-xs font-normal">(overpaid)</span></span>
                        @else
                            <span class="text-green-600">Rs 0</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                            <i class="fas fa-check text-xs"></i> CLEARED
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <i class="fas fa-inbox text-3xl"></i>
                            <p class="text-sm">No recovered accounts yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($list->count())
            <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                <tr class="font-semibold text-sm">
                    <td colspan="3" class="px-4 py-3 text-gray-600">Totals ({{ $list->count() }} accounts)</td>
                    <td class="px-4 py-3 text-right text-red-600">Rs {{ number_format($list->sum('total_debt')) }}</td>
                    <td class="px-4 py-3 text-right text-green-600">Rs {{ number_format($list->sum(fn($r) => $r->total_credit ?? ($r->total_debt - $r->balance))) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
