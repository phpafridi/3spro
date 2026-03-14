@extends('layouts.master')
@section('title', 'Estimate Consumables - #' . $estmId)')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
@push('once') {{-- content inserted by controller --}} @endpush
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Estimate Consumables - #' . $estmId)</h2>
        <a href="{{ route('jobcard.unclosed-estimates') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa fa-arrow-left mr-1"></i> Back
        </a>
    </div>
    <p class="text-gray-400 text-sm italic">This section is under construction.</p>
</div>
@endsection
