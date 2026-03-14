@extends('layouts.master')
@section('title', 'Open New RO')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Open New RO</h2>
        <div class="space-y-4">
            <form action="{{ route('jobcard.add-vehicle.search') }}" method="POST">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-1">Search by Registration</label>
                <div class="flex gap-2">
                    <input type="text" name="Registration" style="text-transform:uppercase" pattern=".{3,12}"
                           placeholder="Enter Registration No."
                           class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                        Check!
                    </button>
                </div>
            </form>
            <div class="border-t border-gray-200 pt-4">
                <form action="{{ route('jobcard.add-vehicle.search') }}" method="POST">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search by Chassis Number</label>
                    <div class="flex gap-2">
                        <input type="text" name="fram" style="text-transform:uppercase" pattern=".{4,15}"
                               placeholder="Enter Chassis / Frame No."
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                            Check!
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
