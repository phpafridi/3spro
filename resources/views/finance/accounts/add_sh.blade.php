@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Accounts - Sub-Heads')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-code-branch text-pink-500 mr-2"></i>GSL Sub-Heads Details
    </h2>

    <form method="POST" action="{{ route('accounts.add-sh') }}" class="mb-8 p-4 bg-pink-50 border border-pink-200 rounded-xl">
        @csrf
        <h3 class="text-sm font-semibold text-pink-700 mb-3">Add Sub-Head</h3>
        <div class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Sub-Head Title</label>
                <input type="text" name="SH_title" required placeholder="e.g. HBL Current Account"
                       class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-400">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Parent GSL</label>
                <select name="GSL_code" required class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-pink-400">
                    <option value="">-- Select GSL --</option>
                    @foreach($gslList as $gsl)
                    <option value="{{ $gsl->GSL_code }}">{{ $gsl->GSL_code }} – {{ $gsl->GSL_title }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-pink-600 text-white rounded-xl text-sm font-medium hover:bg-pink-700">
                <i class="fas fa-plus mr-1"></i>Add Sub-Head
            </button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-pink-500 to-rose-500">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Sub-Head Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">GSL Code</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($shList as $i => $sh)
                <tr class="hover:bg-yellow-50">
                    <td class="px-4 py-3">{{ $i+1 }}</td>
                    <td class="px-4 py-3 font-medium">{{ $sh->SH_title }}</td>
                    <td class="px-4 py-3 font-mono text-gray-500">{{ $sh->GSL_code }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No sub-heads defined yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
