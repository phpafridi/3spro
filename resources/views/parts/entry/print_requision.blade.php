@extends('parts.layout')
@section('title', 'Workshop Requisition Print - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Workshop Requisition Print</h2>
</div>
@if(session('success'))<div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">{{ session('success') }}</div>@endif
<div class="max-w-xl">
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
<form action="{{ route('parts.print-requisition.redirect') }}" method="POST">
@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jobcard / RO Number <span class="text-red-500">*</span></label>
        <select name="model" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
            <option value="">-- Select Jobcard --</option>
            @foreach($jobcards as $jc)
            <option value="{{ $jc->job_id }}">RO# {{ $jc->RO_no ?? $jc->job_id }} - {{ $jc->customer_name ?? '' }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="consumble" id="consumble" value="1" class="w-4 h-4 text-indigo-600">
        <label for="consumble" class="text-sm text-gray-700">Print Consumables (uncheck for Parts)</label>
    </div>
</div>
<div class="mt-6">
    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2.5 rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 transition-all">
        <i class="fa fa-print mr-2"></i>Print Requisition
    </button>
</div>
</form>
</div>
</div>
@endsection
