@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Add Credit Entry')
@section('content')

<div class="bg-white rounded shadow-sm p-6 max-w-xl">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fas fa-hand-holding-usd text-green-600"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Credit / Payment Entry</h2>
            <p class="text-xs text-gray-500">Record a payment received against an outstanding DM invoice</p>
        </div>
    </div>

    {{-- Session Messages --}}
    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded flex items-start gap-2 text-sm">
        <i class="fas fa-exclamation-circle mt-0.5 shrink-0"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded flex items-center gap-2 text-sm">
        <i class="fas fa-check-circle shrink-0"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Show resolved customer name if we came from DM Bills --}}
    @if(isset($prefill->cust_name) && $prefill->cust_name)
    <div class="mb-4 px-4 py-3 bg-blue-50 border border-blue-200 text-blue-800 rounded flex items-center gap-2 text-sm">
        <i class="fas fa-user text-blue-500 shrink-0"></i>
        <span>Customer: <strong>{{ $prefill->cust_name }}</strong></span>
    </div>
    @endif

    <form method="POST" action="{{ route('recovery.add-credit.store') }}">
        @csrf
        <div class="space-y-4">

            {{-- DM Invoice No --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    DM Invoice No <span class="text-red-500">*</span>
                </label>
                <input type="number" name="required_dm" required
                    value="{{ old('required_dm', $prefill->dm_invoice ?? '') }}"
                    placeholder="e.g. 10234"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-green-400 focus:border-green-400 outline-none @error('required_dm') border-red-400 @enderror">
                @error('required_dm')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Must match an existing debit entry invoice number</p>
            </div>

            {{-- Payment Method --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Payment Method <span class="text-red-500">*</span>
                </label>
                <select name="required_payment_method" required
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-green-400 focus:border-green-400 outline-none @error('required_payment_method') border-red-400 @enderror">
                    <option value="">— Select Method —</option>
                    @foreach(['Cash','Cheque','Online Transfer','IBFT','Bank Draft'] as $m)
                    <option value="{{ $m }}"
                        {{ old('required_payment_method', $prefill->Payment_method ?? '') === $m ? 'selected' : '' }}>
                        {{ $m }}
                    </option>
                    @endforeach
                </select>
                @error('required_payment_method')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- RT / Reference No --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">RT / Reference No</label>
                <input type="text" name="required_rt"
                    value="{{ old('required_rt', $prefill->RT_no ?? '') }}"
                    placeholder="Cheque no., transaction ID, etc."
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-green-400 focus:border-green-400 outline-none">
            </div>

            {{-- Date --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Payment Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="required_date" required
                    value="{{ old('required_date', $prefill->cr_date ?? date('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-green-400 focus:border-green-400 outline-none @error('required_date') border-red-400 @enderror">
                @error('required_date')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Amount --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Amount Received (Rs) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rs</span>
                    <input type="number" name="required_amount" required min="1"
                        value="{{ old('required_amount', $prefill->cr_amount ?? '') }}"
                        placeholder="0"
                        class="w-full border border-gray-300 rounded pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-green-400 focus:border-green-400 outline-none @error('required_amount') border-red-400 @enderror">
                </div>
                @error('required_amount')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remarks --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                <textarea name="remarks" rows="2"
                    placeholder="Optional notes..."
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-green-400 focus:border-green-400 outline-none resize-none">{{ old('remarks', $prefill->remarks ?? '') }}</textarea>
            </div>

        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit"
                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-semibold text-sm transition-colors">
                <i class="fas fa-save mr-2"></i>Save Credit Entry
            </button>
            <a href="{{ url()->previous() }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
</div>

@endsection
