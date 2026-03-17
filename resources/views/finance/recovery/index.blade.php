@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Recovery - Dashboard')
@section('content')
{{-- Stats Row --}}
<div class="grid grid-cols-3 md:grid-cols-6 gap-4 mb-6">
    @foreach([
        ['Total',   $totalc,    'bg-indigo-500'],
        ['New',     $new,       'bg-sky-500'],
        ['Open',    $open,      'bg-orange-500'],
        ['Close',   $close,     'bg-green-500'],
        ['Active',  $active,    'bg-yellow-500'],
        ['Pending', $pending,   'bg-red-500'],
    ] as [$label, $val, $color])
    <div class="bg-white rounded-2xl shadow-sm p-4 text-center">
        <div class="text-2xl font-bold text-gray-800">{{ $val }}</div>
        <div class="text-xs text-gray-500 mt-1">{{ $label }}</div>
        <div class="{{ $color }} h-1 rounded-full mt-2 opacity-60"></div>
    </div>
    @endforeach
</div>

{{-- Total Debit Banner --}}
<div class="bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-2xl p-4 mb-6 text-center">
    <p class="text-sm opacity-80">Total Outstanding Debit</p>
    <p class="text-3xl font-bold mt-1">Rs {{ number_format($totalDebit) }}</p>
</div>

{{-- Debtors Table --}}
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Outstanding Accounts</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-red-600 to-rose-600">
                <tr>
                    @foreach(['#','Customer','Contact','Age','Debit Amount','Actions'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($debtors as $d)
                @if(($d->remain_amount ?? 0) > 0)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('recovery.customer-ledger', ['id'=>$d->cust_name]) }}" class="text-indigo-600 hover:underline">
                            {{ $d->cust_name }}
                        </a>
                    </td>
                    <td class="px-4 py-3">{{ $d->contact }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $d->age ?? '' }}</td>
                    <td class="px-4 py-3 font-medium text-red-600">
                        <a href="{{ route('recovery.clearance', ['id'=>$d->cust_name]) }}" class="hover:underline">
                            Rs {{ number_format($d->remain_amount) }}
                        </a>
                    </td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="{{ route('recovery.history', ['id'=>$d->cust_name]) }}"
                           class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs hover:bg-yellow-200">History</a>
                        <a href="{{ route('recovery.followup', ['id'=>$d->cust_name]) }}"
                           class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200">Followup</a>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No debtors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
