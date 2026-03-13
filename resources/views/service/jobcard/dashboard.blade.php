{{-- resources/views/service/jobcard/dashboard.blade.php --}}
@extends('layouts.master')

@section('title', 'Service Advisor Dashboard')

@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Service Advisor Dashboard</h2>
            <p class="text-sm text-gray-500 mt-1">Department: {{ session('dept') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-5 border border-indigo-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-indigo-600 font-medium">Quick Action</p>
                    <h3 class="text-lg font-bold text-indigo-800 mt-1">Open New RO</h3>
                </div>
                <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center">
                    <i class="fa fa-plus text-white text-xl"></i>
                </div>
            </div>
            <a href="{{ route('jobcard.add-vehicle') }}"
               class="mt-4 inline-block bg-indigo-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-600 transition-colors">
                Start Here
            </a>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-5 border border-emerald-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-emerald-600 font-medium">Job Cards</p>
                    <h3 class="text-lg font-bold text-emerald-800 mt-1">Unclosed Jobs</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center">
                    <i class="fa fa-asterisk text-white text-xl"></i>
                </div>
            </div>
            <a href="{{ route('jobcard.index') }}"
               class="mt-4 inline-block bg-emerald-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-emerald-600 transition-colors">
                View All
            </a>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-5 border border-amber-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-amber-600 font-medium">Estimates</p>
                    <h3 class="text-lg font-bold text-amber-800 mt-1">Create Estimate</h3>
                </div>
                <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center">
                    <i class="fa fa-file-text-o text-white text-xl"></i>
                </div>
            </div>
            <a href="{{ route('jobcard.estimate.create') }}"
               class="mt-4 inline-block bg-amber-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors">
                New Estimate
            </a>
        </div>
    </div>
</div>
@endsection
