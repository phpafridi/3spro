@extends('layouts.master')
@include('finance.cashier.sidebar')

@section('title', 'Cashier - Reports')

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-chart-line text-purple-500 mr-2"></i>
            Reports & Scrolls
        </h2>
    </div>

    <!-- Date Range Picker -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                        <i class="fas fa-calendar"></i>
                    </span>
                    <input type="text"
                           id="daterange"
                           name="daterange"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Type Buttons -->
    <div class="mb-8">
        <h3 class="text-sm font-medium text-gray-700 mb-3">Invoice Types</h3>
        <div class="flex flex-wrap gap-2">
            @foreach(['CM', 'DM', 'DMC', 'COMP', 'GW', 'JND', 'PDS', 'FFS', 'WC', 'CNI'] as $type)
            <form method="POST" action="{{ route('cashier.report-download') }}" target="_blank" class="inline report-form">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="daterange" class="daterange-input">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    {{ $type }}
                </button>
            </form>
            @endforeach
        </div>
    </div>

    <!-- Scroll Buttons -->
    <div class="mb-8">
        <h3 class="text-sm font-medium text-gray-700 mb-3">Scrolls</h3>
        <div class="flex flex-wrap gap-2">
            <form method="POST" action="{{ route('cashier.business-summary') }}" target="_blank" class="inline report-form">
                @csrf
                <input type="hidden" name="daterange" class="daterange-input">
                <button type="submit"
                        class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium rounded-lg transition-colors">
                    Business Summary
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery must be first -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(function() {
        $('#daterange').daterangepicker({
            opens: 'left',
            startDate: moment(),
            endDate: moment(),
            locale: {
                format: 'MM/DD/YYYY'
            }
        });

        // Inject daterange value into hidden input BEFORE form submits
        $('.report-form').on('submit', function() {
            $(this).find('.daterange-input').val($('#daterange').val());
        });
    });
</script>
@endpush
