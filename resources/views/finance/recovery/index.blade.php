@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Recovery - Dashboard')
@section('content')

{{-- Stats Row --}}
<div class="grid grid-cols-3 md:grid-cols-6 gap-4 mb-6">
    @foreach([
        ['Total',   $totalc,    'bg-red-600'],
        ['New',     $new,       'bg-sky-500'],
        ['Open',    $open,      'bg-orange-500'],
        ['Close',   $close,     'bg-green-500'],
        ['Active',  $active,    'bg-yellow-500'],
        ['Pending', $pending,   'bg-red-500'],
    ] as [$label, $val, $color])
    <div class="bg-white rounded shadow-sm p-4 text-center">
        <div class="text-2xl font-bold text-gray-800">{{ $val }}</div>
        <div class="text-xs text-gray-500 mt-1">{{ $label }}</div>
        <div class="{{ $color }} h-1 rounded-full mt-2 opacity-60"></div>
    </div>
    @endforeach
</div>

{{-- Total Debit Banner --}}
<div class="bg-red-600 text-white rounded p-4 mb-6 flex items-center justify-between">
    <div>
        <p class="text-sm opacity-80">Total Outstanding Debit</p>
        <p class="text-3xl font-bold mt-1">Rs {{ number_format($totalDebit) }}</p>
    </div>
    <a href="{{ route('recovery.dm-bills') }}"
       class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded transition">
        <i class="fas fa-file-invoice mr-1"></i> DM Bills
    </a>
</div>

{{-- Outstanding Debtors Table --}}
<div class="bg-white rounded shadow-sm p-6 mb-6">
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
                @php $rowNum = 0; @endphp
                @forelse($debtors as $d)
                @if(($d->remain_amount ?? 0) > 0)
                @php $rowNum++; @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $rowNum }}</td>
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('recovery.customer-ledger', ['id'=>$d->Customer_id]) }}" class="text-red-600 hover:underline">
                            {{ $d->cust_name }}
                        </a>
                    </td>
                    <td class="px-4 py-3">{{ $d->contact }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $d->age ?? '' }}</td>
                    <td class="px-4 py-3 font-medium text-red-600">
                        <a href="{{ route('recovery.clearance', ['id'=>$d->Customer_id]) }}" class="hover:underline">
                            Rs {{ number_format($d->remain_amount) }}
                        </a>
                    </td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="{{ route('recovery.history', ['id'=>$d->Customer_id]) }}"
                           class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs hover:bg-yellow-200">History</a>
                        <a href="{{ route('recovery.followup', ['id'=>$d->Customer_id]) }}"
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

{{-- ── Pending DM / DMC Bills (inline, no redirect needed) ─────── --}}
@if(isset($dmBills) && $dmBills->count())
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-file-invoice text-red-500 mr-2"></i>
            Pending DM / DMC Bills
            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-bold">{{ $dmBills->count() }}</span>
        </h2>
        <a href="{{ route('recovery.dm-bills') }}" class="text-xs text-red-600 hover:underline">View All →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-red-600">
                <tr>
                    @foreach(['Invoice','JC #','Customer','Reg','Type','Care Of','Total','Date'] as $h)
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($dmBills->take(10) as $b)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 font-mono font-bold text-red-700">{{ $b->Invoice_id }}</td>
                    <td class="px-3 py-2 font-mono text-gray-600">{{ $b->Jobc_id }}</td>
                    <td class="px-3 py-2 font-medium text-gray-800">{{ $b->Customer_name }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $b->Veh_reg_no }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $b->type === 'DMC' ? 'bg-purple-100 text-purple-700' : 'bg-red-100 text-red-700' }}">
                            {{ $b->type }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-600">{{ $b->careof }}</td>
                    <td class="px-3 py-2 font-bold text-red-600">Rs {{ number_format($b->Total) }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $b->bookingtime }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
