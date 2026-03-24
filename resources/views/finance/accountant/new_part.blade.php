@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'New Part')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-cloud-upload-alt text-red-500 mr-2"></i> Add New Part
    </h2>
    <form method="POST" action="{{ route('accountant.new-part.store') }}">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Part Number</label>
                <input type="text" name="partnumber" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <input type="text" name="description" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="catetype" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="">-- Select --</option>
                    @foreach($categories as $c)
                    <option value="{{ $c->catetype }}">{{ $c->catetype }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Part Type</label>
                <select name="parttype" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="Genuine">Genuine</option>
                    <option value="Consumable">Consumable</option>
                    <option value="Local">Local</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model Code</label>
                <input type="text" name="modelcode" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
                <input type="number" name="reorder" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input type="text" name="Location" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
        </div>
        <button type="submit" class="mt-6 px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-medium">
            Add Part
        </button>
    </form>
</div>
@endsection
