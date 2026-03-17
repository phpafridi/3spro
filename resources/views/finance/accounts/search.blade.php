@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Accounts - Search Voucher')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-search text-indigo-500 mr-2"></i>Search Voucher
    </h2>

    {{-- Search Form --}}
    <form method="POST" action="{{ route('accounts.search') }}" class="flex gap-3 mb-8 max-w-md">
        @csrf
        <input type="text" name="search_voucher" value="{{ $voucher->mas_vch_id ?? '' }}"
               placeholder="Enter Voucher ID..."
               class="flex-1 border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-400">
        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
            <i class="fas fa-search"></i>
        </button>
    </form>

    @if($voucher)
    {{-- Voucher Details --}}
    <div class="p-5 bg-indigo-50 border border-indigo-200 rounded-xl mb-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div><span class="text-gray-500 text-xs">Type</span><br><strong>{{ $voucher->vchr_type }}</strong></div>
            <div><span class="text-gray-500 text-xs">Ref No</span><br><strong>{{ $voucher->RefNo }}</strong></div>
            <div><span class="text-gray-500 text-xs">Date</span><br><strong>{{ $voucher->VoucherDate }}</strong></div>
            <div><span class="text-gray-500 text-xs">Status</span><br>
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                    {{ $voucher->A_T === 'Yes' ? 'bg-green-100 text-green-700' : ($voucher->A_T === 'Forward' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ $voucher->A_T ?? 'Draft' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Voucher Items --}}
    @if($items && count($items))
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    @foreach(['GSL Code','GSL','Description','Debit','Credit'] as $h)
                    <th class="px-4 py-2 text-left text-xs text-gray-600 uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($items as $item)
                <tr>
                    <td class="px-4 py-2 font-mono">{{ $item->GSL_code }}</td>
                    <td class="px-4 py-2">{{ $item->GSL }}</td>
                    <td class="px-4 py-2">{{ $item->Description }}</td>
                    <td class="px-4 py-2 text-red-600">{{ $item->Debit ? number_format($item->Debit,2) : '' }}</td>
                    <td class="px-4 py-2 text-green-600">{{ $item->Credit ? number_format($item->Credit,2) : '' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 font-bold">
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right text-sm text-gray-700">Totals:</td>
                    <td class="px-4 py-2 text-red-600">{{ number_format($items->sum('Debit'),2) }}</td>
                    <td class="px-4 py-2 text-green-600">{{ number_format($items->sum('Credit'),2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- Reopen Request --}}
    @if($voucher->A_T === 'Yes')
    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
        <h3 class="text-sm font-semibold text-yellow-800 mb-3">
            <i class="fas fa-undo mr-2"></i>Request to Reopen this Voucher
        </h3>
        <form method="POST" action="{{ route('accounts.search') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="reopen_voucher" value="{{ $voucher->mas_vch_id }}">
            <textarea name="reason" rows="2" required placeholder="State the reason for reopening..."
                      class="w-full border border-yellow-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-yellow-400"></textarea>
            <button type="submit" class="px-5 py-2 bg-yellow-500 text-white rounded-xl text-sm font-medium hover:bg-yellow-600">
                Submit Reopen Request
            </button>
        </form>
    </div>
    @endif
    @endif
</div>
@endsection
