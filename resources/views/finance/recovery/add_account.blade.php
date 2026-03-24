@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Recovery - Manage Accounts')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-3xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-university text-red-500 mr-2"></i>Manage Recovery Accounts
    </h2>

    {{-- Add Account Form --}}
    {{-- recov_accounts: account_id, Name, Occopation, Primary_contact, Sec_contact, email, amount_limit, r_officer, datetime, status --}}
    <form method="POST" action="{{ route('recovery.add-account.store') }}" class="mb-8 p-5 bg-red-50 border border-red-200 rounded">
        @csrf
        <h3 class="text-sm font-semibold text-red-700 mb-4"><i class="fas fa-plus-circle mr-1"></i>Add New Account</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="Name" required maxlength="38"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500"
                       placeholder="Customer / company name">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Occupation</label>
                <input type="text" name="Occopation" maxlength="30"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500"
                       placeholder="e.g. Businessman">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Primary Contact <span class="text-red-500">*</span></label>
                <input type="number" name="Primary_contact" required
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500"
                       placeholder="03xxxxxxxxx">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Secondary Contact</label>
                <input type="number" name="Sec_contact"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500"
                       placeholder="Optional">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" maxlength="50"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Credit Limit (Rs)</label>
                <input type="number" name="amount_limit" value="0"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500">
            </div>
        </div>
        <button type="submit" class="mt-4 px-5 py-2 bg-red-600 text-white rounded text-sm font-medium hover:bg-red-700 transition">
            <i class="fas fa-save mr-2"></i>Save Account
        </button>
    </form>

    {{-- Existing Accounts Table --}}
    <h3 class="font-semibold text-gray-700 mb-3 text-sm uppercase tracking-wider">Existing Accounts</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-red-600">
                <tr>
                    @foreach(['#','Name','Occupation','Primary Contact','Credit Limit','Officer','Status'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($accounts as $i => $a)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 font-medium">{{ $a->Name }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $a->Occopation }}</td>
                    <td class="px-4 py-3 font-mono">{{ number_format($a->Primary_contact) }}</td>
                    <td class="px-4 py-3 font-semibold text-red-600">Rs {{ number_format($a->amount_limit) }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $a->r_officer }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $a->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $a->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No accounts added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
