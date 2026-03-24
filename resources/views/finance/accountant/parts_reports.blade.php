@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Parts Reports')
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-wpforms text-red-500 mr-2"></i> Parts Department Reports
    </h2>
    <div class="mb-6">
        <input type="text" id="parts_reservation" class="border border-gray-300 rounded px-3 py-2 text-sm w-72"
            value="{{ date('m/d/Y') }} - {{ date('m/d/Y') }}">
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach(['Parts Sales Report','Purchase Report','Stock Report','Parts Difference','Labor Detail','Purchase Profile','Parts Price Change','Parts Cancel Report'] as $r)
        <a href="{{ route('parts.reports') }}"
            class="px-4 py-3 rounded bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-sm font-medium">
            <i class="fas fa-file-alt mr-2"></i>{{ $r }}
        </a>
        @endforeach
    </div>
</div>
@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>$('#parts_reservation').daterangepicker();</script>
@endpush
@endsection
