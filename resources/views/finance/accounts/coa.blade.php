@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Charts of Accounts')

@section('content')
<div class="bg-white rounded shadow-sm p-6">

    {{-- Header --}}
    <table class="w-full mb-4">
        <tr>
            <td><h2 class="text-2xl font-semibold text-gray-800">
                <i class="fas fa-sitemap text-red-500 mr-2"></i>Charts of Accounts
            </h2></td>
            <td class="text-right">
                <button type="button" onclick="document.getElementById('addModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium">
                    <i class="fas fa-plus mr-1"></i> Add New
                </button>
            </td>
        </tr>
    </table>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">{{ session('success') }}</div>
    @endif

    {{-- Main Accounts Table: click row → drills into GL (same as legacy Accounts system) --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm" id="coaTable">
            <thead style="background-color:gray; color:white;">
                <tr>
                    <th class="px-4 py-3 text-left">Main Account</th>
                    <th class="px-4 py-3 text-left">Range Start</th>
                    <th class="px-4 py-3 text-left">Range End</th>
                    <th class="px-4 py-3 text-left">
                        <a href="{{ route('accounts.coa') }}" style="color:yellow;text-decoration:none;" target="_blank">
                            View Charts of Account
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($mainAccounts as $a)
                <tr class="hover:bg-yellow-100 cursor-pointer font-medium border-b"
                    onclick="document.getElementById('form_ma_{{ $a->ma_id }}').submit()"
                    title="Click to view GL under {{ $a->main_account }}">
                    <td class="px-4 py-3 text-red-700">
                        <form id="form_ma_{{ $a->ma_id }}" method="GET" action="{{ route('accounts.add-gl') }}">
                            <input type="hidden" name="ma_id"        value="{{ $a->ma_id }}">
                            <input type="hidden" name="rang_start"   value="{{ $a->rang_start }}">
                            <input type="hidden" name="rang_end"     value="{{ $a->rang_end }}">
                            <input type="hidden" name="main_account" value="{{ $a->main_account }}">
                        </form>
                        <strong>{{ $a->main_account }}</strong>
                    </td>
                    <td class="px-4 py-3 font-mono">{{ number_format($a->rang_start) }}</td>
                    <td class="px-4 py-3 font-mono">{{ number_format($a->rang_end) }}</td>
                    <td class="px-4 py-3 text-xs text-blue-500">→ click to view GL</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                        No main accounts defined yet. Click <strong>Add New</strong> to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Add New Modal — range auto-filled (same as Accounts system) --}}
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h5 class="font-semibold text-gray-800">Add Main Account</h5>
            <button onclick="document.getElementById('addModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>
        <form method="POST" action="{{ route('accounts.coa') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Main Account Name</label>
                <input type="text" name="main_acount" required placeholder="e.g. Assets"
                    class="w-full border border-gray-300 rounded px-4 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Range Starts</label>
                <input type="number" name="rang_start" readonly value="{{ $nextRangStart }}"
                    class="w-full border border-gray-200 bg-gray-50 rounded px-4 py-2 text-sm font-mono">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Range Ends</label>
                <input type="number" name="rang_end" readonly value="{{ $nextRangEnd }}"
                    class="w-full border border-gray-200 bg-gray-50 rounded px-4 py-2 text-sm font-mono">
            </div>
            <div class="flex justify-end gap-3 mt-2">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                    class="px-4 py-2 border border-gray-300 text-gray-600 rounded text-sm">Close</button>
                <button type="submit"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium">Add New</button>
            </div>
        </form>
    </div>
</div>
@endsection
