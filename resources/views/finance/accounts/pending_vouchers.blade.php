@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Accounts - Pending Vouchers')
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-clock text-yellow-500 mr-2"></i>Pending Vouchers
        </h2>
        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">
            {{ count($vouchers) }} Pending
        </span>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-yellow-500 to-orange-500">
                <tr>
                    @foreach(['#','Type','Ref No','Date','Book No','Created By','Actions'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($vouchers as $i => $v)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $i+1 }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ in_array($v->vchr_type,['CPV','CRV']) ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                            {{ $v->vchr_type }}
                        </span>
                    </td>
                    <td class="px-4 py-3 font-mono">{{ $v->RefNo }}</td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $v->VoucherDate }}</td>
                    <td class="px-4 py-3">{{ $v->BookNo }}</td>
                    <td class="px-4 py-3">{{ $v->UserName }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-2">
                            {{-- Edit line items --}}
                            <a href="{{ route('accounts.voucher.edit', $v->mas_vch_id) }}"
                               class="px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white rounded text-xs font-medium">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            {{-- Submit / Forward --}}
                            <form method="POST" action="{{ route('accounts.pending-vouchers') }}">
                                @csrf
                                <input type="hidden" name="Submitit" value="{{ $v->mas_vch_id }}">
                                <button class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs">
                                    <i class="fas fa-paper-plane mr-1"></i>Submit
                                </button>
                            </form>
                            {{-- Trash --}}
                            <form method="POST" action="{{ route('accounts.pending-vouchers') }}"
                                  onsubmit="return confirm('Trash this voucher?')">
                                @csrf
                                <input type="hidden" name="vch_status_cancel" value="{{ $v->mas_vch_id }}">
                                <button class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs">
                                    <i class="fas fa-trash mr-1"></i>Trash
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No pending vouchers</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
