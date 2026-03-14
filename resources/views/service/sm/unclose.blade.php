@extends('layouts.master')
@section('title', 'SM - Reopen Jobcard')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Reopen / Unclose Jobcard</h2>
        @if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>@endif
        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-md">
            <i class="fa fa-exclamation-triangle mr-2"></i>This will reopen a closed jobcard. The original invoice total will be logged. Requires SM password.
        </div>
        <form method="POST" action="{{ route('sm.unclose.process') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">RO No <span class="text-red-500">*</span></label>
                <input type="text" name="jobc_id" required placeholder="e.g. 12345"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason <span class="text-red-500">*</span></label>
                <textarea name="reason" rows="3" required placeholder="Reason for reopening..."
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SM Password <span class="text-red-500">*</span></label>
                <input type="password" name="passwrd" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-unlock mr-2"></i> Reopen Jobcard
            </button>
        </form>
    </div>
</div>
@endsection
