@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Accounts - Charts of Accounts')
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-sitemap text-red-500 mr-2"></i>Charts of Accounts — Main Heads
    </h2>

    {{-- Add Main Account --}}
    <form method="POST" action="{{ route('accounts.coa') }}" class="mb-8 p-4 bg-red-50 border border-red-200 rounded">
        @csrf
        <h3 class="text-sm font-semibold text-red-700 mb-3">Add Main Account</h3>
        <div class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Account Title</label>
                <input type="text" name="main_acount" required placeholder="e.g. Assets"
                       class="border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Range Start</label>
                <input type="number" name="rang_start" required placeholder="e.g. 1000"
                       class="border border-gray-300 rounded px-4 py-2 text-sm w-32 focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Range End</label>
                <input type="number" name="rang_end" required placeholder="e.g. 1999"
                       class="border border-gray-300 rounded px-4 py-2 text-sm w-32 focus:ring-2 focus:ring-red-500">
            </div>
            <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded text-sm font-medium hover:bg-red-700">
                <i class="fas fa-plus mr-1"></i>Add
            </button>
        </div>
    </form>

    {{-- Main Accounts Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Account Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Range Start</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Range End</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($mainAccounts as $i => $a)
                <tr class="hover:bg-yellow-50 cursor-pointer">
                    <td class="px-4 py-3">{{ $i+1 }}</td>
                    <td class="px-4 py-3 font-semibold text-red-700">{{ $a->main_account }}</td>
                    <td class="px-4 py-3 font-mono">{{ $a->rang_start }}</td>
                    <td class="px-4 py-3 font-mono">{{ $a->rang_end }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No main accounts defined yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
