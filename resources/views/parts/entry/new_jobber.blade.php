@extends('parts.layout')
@section('title', 'New Jobber - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Add New Jobber / Vendor</h2>
</div>
@if(session('success'))<div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl">{{ session('error') }}</div>@endif
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
<form action="{{ route('parts.new-jobber.store') }}" method="POST">
@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jobber Name <span class="text-red-500">*</span></label>
        <input type="text" name="jobber" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
        <select name="cust_jobber" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
            <option value="Jobber">Jobber</option>
            <option value="Customer">Customer</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
        <input type="text" name="contactperson" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
        <input type="text" name="contact" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
        <input type="text" name="address" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">CNIC</label>
        <input type="text" name="CNIC" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
</div>
<div class="mt-6">
    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2.5 rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 transition-all">Add Jobber</button>
</div>
</form>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-4 border-b border-gray-100"><h3 class="font-semibold text-gray-800">Existing Jobbers</h3></div>
    <div class="overflow-y-auto max-h-96">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr>
            <th class="px-4 py-2 text-left text-xs text-gray-500">Name</th>
            <th class="px-4 py-2 text-left text-xs text-gray-500">Contact</th>
            <th class="px-4 py-2 text-right text-xs text-gray-500">Balance</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-50">
        @foreach($jobbers as $j)
        <tr class="hover:bg-indigo-50/30">
            <td class="px-4 py-2 font-medium text-gray-800">{{ $j->jbr_name }}</td>
            <td class="px-4 py-2 text-gray-500 text-xs">{{ $j->contact }}</td>
            <td class="px-4 py-2 text-right {{ $j->Balance_status < 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($j->Balance_status ?? 0, 2) }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
</div>
@endsection
