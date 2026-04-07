@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Vendor Ledger')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-xl font-bold text-gray-800">
            <i class="fa fa-book text-blue-600 mr-2"></i>Vendor Ledger
        </h2>
        <p class="text-sm text-gray-500 mt-1">View purchase history, payments, and outstanding balance per vendor.</p>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white rounded-lg shadow-sm p-5">
        <form method="GET" action="{{ route('accountant.vendor-ledger') }}" id="ledgerForm">
            <div class="flex flex-wrap gap-4 items-end">

                {{-- Vendor --}}
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vendor <span class="text-red-500">*</span></label>
                    <select name="jobber_id" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Select Vendor —</option>
                        @foreach($jobbers as $jb)
                            <option value="{{ $jb->jobber_id }}" {{ $jabberId == $jb->jobber_id ? 'selected' : '' }}>
                                {{ $jb->jbr_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date From --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}"
                           class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Date To --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ $dateTo ?? '' }}"
                           class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fas fa-search mr-1"></i> Show Ledger
                </button>

                @if($jabberId)
                <a href="{{ route('accountant.vendor-ledger') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-md transition-colors">
                    <i class="fas fa-times mr-1"></i> Clear
                </a>
                <button type="button" onclick="window.print()"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fa fa-print mr-1"></i> Print
                </button>
                @endif
            </div>
        </form>
    </div>

    @if($selectedJobber && $summary)

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 font-medium uppercase">Total Purchases</p>
            <p class="text-lg font-bold text-gray-800 mt-1">{{ number_format($summary['total_purchase'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
            <p class="text-xs text-gray-500 font-medium uppercase">Credit Purchases</p>
            <p class="text-lg font-bold text-yellow-700 mt-1">{{ number_format($summary['credit_purchase'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 font-medium uppercase">Cash Purchases</p>
            <p class="text-lg font-bold text-green-700 mt-1">{{ number_format($summary['cash_purchase'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
            <p class="text-xs text-gray-500 font-medium uppercase">Total Paid</p>
            <p class="text-lg font-bold text-purple-700 mt-1">{{ number_format($summary['total_paid'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-orange-400">
            <p class="text-xs text-gray-500 font-medium uppercase">Returns</p>
            <p class="text-lg font-bold text-orange-600 mt-1">{{ number_format($summary['total_returns'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 {{ $summary['balance_due'] > 0 ? 'border-red-500' : 'border-teal-500' }}">
            <p class="text-xs text-gray-500 font-medium uppercase">Balance Due</p>
            <p class="text-lg font-bold {{ $summary['balance_due'] > 0 ? 'text-red-600' : 'text-teal-600' }} mt-1">
                {{ number_format($summary['balance_due'], 2) }}
            </p>
        </div>
    </div>

    {{-- Ledger Table --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800">
                    Ledger — <span class="text-blue-600">{{ $selectedJobber->jbr_name }}</span>
                </h3>
                @if($selectedJobber->contact)
                    <p class="text-xs text-gray-400 mt-0.5"><i class="fa fa-phone mr-1"></i>{{ $selectedJobber->contact }}</p>
                @endif
            </div>
            <span class="text-sm text-gray-400">{{ $ledger->count() }} transaction(s)</span>
        </div>

        @if($ledger->count())
        @php
            $runningBalance = 0;
        @endphp
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ref#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Payment Method</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Debit (Purchase)</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Credit (Paid/Return)</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Note</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($ledger as $i => $row)
                    @php
                        $runningBalance += ($row->debit - $row->credit);
                        $typeColors = [
                            'Purchase' => 'bg-blue-100 text-blue-700',
                            'Payment'  => 'bg-green-100 text-green-700',
                            'Return'   => 'bg-orange-100 text-orange-700',
                        ];
                        $badgeClass = $typeColors[$row->trans_type] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-2 text-gray-600">
                            {{ \Carbon\Carbon::parse($row->trans_date)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badgeClass }}">
                                {{ $row->trans_type }}
                            </span>
                        </td>
                        <td class="px-4 py-2 font-medium text-gray-700">{{ $row->ref_number }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $row->payment_method ?? '—' }}</td>
                        <td class="px-4 py-2 text-right {{ $row->debit > 0 ? 'text-red-600 font-semibold' : 'text-gray-300' }}">
                            {{ $row->debit > 0 ? number_format($row->debit, 2) : '—' }}
                        </td>
                        <td class="px-4 py-2 text-right {{ $row->credit > 0 ? 'text-green-600 font-semibold' : 'text-gray-300' }}">
                            {{ $row->credit > 0 ? number_format($row->credit, 2) : '—' }}
                        </td>
                        <td class="px-4 py-2 text-right font-bold {{ $runningBalance > 0 ? 'text-red-700' : 'text-teal-600' }}">
                            {{ number_format($runningBalance, 2) }}
                        </td>
                        <td class="px-4 py-2 text-gray-500 text-xs max-w-xs truncate">{{ $row->note ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-sm font-semibold text-gray-700 text-right">Totals:</td>
                        <td class="px-4 py-3 text-right font-bold text-red-700">
                            {{ number_format($ledger->sum('debit'), 2) }}
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-green-700">
                            {{ number_format($ledger->sum('credit'), 2) }}
                        </td>
                        <td class="px-4 py-3 text-right font-bold {{ $runningBalance > 0 ? 'text-red-700' : 'text-teal-600' }}">
                            {{ number_format($runningBalance, 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="px-6 py-12 text-center text-gray-400">
            <i class="fa fa-inbox text-3xl block mb-3"></i>
            No transactions found for this vendor in the selected period.
        </div>
        @endif
    </div>

    @elseif(request()->has('jobber_id') && !$selectedJobber)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-700 text-sm">
        <i class="fa fa-warning mr-2"></i> Vendor not found. Please select a valid vendor.
    </div>
    @endif

</div>

<style>
@media print {
    nav, aside, form, button, a[href] { display: none !important; }
    .shadow-sm { box-shadow: none !important; }
    body { background: white; }
}
</style>
@endsection
