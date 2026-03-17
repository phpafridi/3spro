@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Labor Auto Update')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 max-w-md">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-percentage text-indigo-500 mr-2"></i> Auto Update Labor Prices
    </h2>
    <form method="POST" action="{{ route('accountant.labor-auto.update') }}" onsubmit="return confirm('This will update ALL labor prices. Are you sure?')">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Update Type</label>
            <select name="whatupdate" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="Increase">Increase</option>
                <option value="Decrease">Decrease</option>
            </select>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Percentage (%)</label>
            <input type="number" name="update" step="0.01" min="0.01" max="100" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="e.g. 5">
        </div>
        <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium">
            Apply Update
        </button>
    </form>
</div>
@endsection
