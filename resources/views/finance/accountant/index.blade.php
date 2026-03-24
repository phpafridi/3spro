@extends('layouts.master')
@include('finance.accountant.sidebar')

@section('title', 'Accountant - Service Reports')

@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-chart-bar text-red-500 mr-2"></i> Service Reports & Scrolls
    </h2>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-600 mb-1">Date Range</label>
        <input type="text" id="reservation" class="border border-gray-300 rounded px-3 py-2 text-sm w-72"
            value="{{ date('m/d/Y') }} - {{ date('m/d/Y') }}">
    </div>

    @foreach([
        ['title'=>'By Type','btns'=>[['All','danger'],['CM','success'],['DM','success'],['DMC','success'],['COMP','success'],['GW','success'],['JND','success'],['PDS','success'],['FFS','success'],['WC','success'],['CNI','success']]],
        ['title'=>'Summary','btns'=>[['Business Summary','dark'],['Sales Tax Invoices','dark'],['Labor Business Report','dark']]],
        ['title'=>'Performance','btns'=>[['SA Performance','info'],['Teams Performance','info'],['Teams Parts','info'],['Customer Ratings','info'],['New vs Old Customers','info']]],
        ['title'=>'By Department','btns'=>[['Mechanical','success'],['Warranty','success'],['Body / Paint','success'],['Top 50 Services','success']]],
        ['title'=>'Excel Genesis','btns'=>[['PM Genesis','slate'],['PM GR BP Genesis','slate'],['MSI Excel Report','slate'],['FIR Genesis Excel','slate']]]
    ] as $section)
    <div class="mb-5">
        <h3 class="text-xs font-semibold text-gray-500 uppercase mb-2">{{ $section['title'] }}</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($section['btns'] as [$label, $color])
            <button onclick="syncAndPost('{{ $label }}')"
                class="px-4 py-2 rounded text-sm font-medium text-white
                @if($color==='danger') bg-red-500 hover:bg-red-600
                @elseif($color==='info') bg-sky-500 hover:bg-sky-600
                @elseif($color==='dark' || $color==='slate') bg-gray-800 hover:bg-gray-900
                @else bg-emerald-500 hover:bg-emerald-600 @endif">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('scripts')
<!-- jQuery first (required for daterangepicker) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function() {
    $('#reservation').daterangepicker({
        opens: 'left',
        startDate: moment(),
        endDate: moment(),
        locale: {
            format: 'MM/DD/YYYY'
        }
    });
});

function syncAndPost(type) {
    alert('Report: ' + type + ' | Date: ' + $('#reservation').val());
}
</script>
@endpush
