@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Accounts - Reopened Vouchers')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-undo text-orange-500 mr-2"></i>Reopened Vouchers
        </h2>
        <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-3 py-1 rounded-full">
            {{ count($vouchers) }} Reopened
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-orange-500 to-red-500">
                <tr>
                    @foreach(['#','Type','Ref No','Date','Book','Created By','Action'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($vouchers as $i => $v)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $i+1 }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                            {{ $v->vchr_type }}
                        </span>
                    </td>
                    <td class="px-4 py-3 font-mono">{{ $v->RefNo }}</td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $v->VoucherDate }}</td>
                    <td class="px-4 py-3">{{ $v->BookNo }}</td>
                    <td class="px-4 py-3">{{ $v->UserName }}</td>
                    <td class="px-4 py-3 flex gap-2">
                        <form method="POST" action="{{ route('accounts.reopened-vouchers') }}">
                            @csrf
                            <input type="hidden" name="Submitit" value="{{ $v->mas_vch_id }}">
                            <button class="px-3 py-1 bg-green-500 text-white rounded-lg text-xs hover:bg-green-600">
                                <i class="fas fa-paper-plane mr-1"></i>Re-submit
                            </button>
                        </form>
                        <form method="POST" action="{{ route('accounts.reopened-vouchers') }}"
                              onsubmit="return confirm('Trash this voucher?')">
                            @csrf
                            <input type="hidden" name="vch_status_cancel" value="{{ $v->mas_vch_id }}">
                            <button class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600">
                                <i class="fas fa-trash mr-1"></i>Trash
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No reopened vouchers</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
