@extends('layouts.master')
@section('title', 'Add New Labor')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Add New Labor Description</h2>
        <p class="text-sm text-gray-500 mb-4">
            Use this form to add a new labor type not yet in the labor list.
        </p>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('jobcard.new-labor.store') }}">
            @csrf
            <div class="flex gap-2">
                <input type="text" name="new_labor" required
                       style="text-transform:uppercase"
                       placeholder="Enter labor description..."
                       class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    Add Labor!
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
