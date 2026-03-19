@extends('layouts.master')
@section('title', 'RO #{{ $jobcId }} — Vehicle Checklist')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')

@if(session('vin_warning'))
<div class="mb-4 p-4 bg-yellow-50 border border-yellow-400 rounded-md text-yellow-800 font-semibold text-sm">
    <i class="fa fa-exclamation-triangle mr-2"></i>
    {{ session('vin_warning') }}
</div>
@endif

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">
            RO# <span class="text-red-600">{{ $jobcId }}</span> — Vehicle Items Checklist
        </h2>
        <p class="text-sm text-gray-500 mb-6">
            Check which items are present in / with the vehicle at time of check-in.
        </p>

        <form action="{{ route('jobcard.checklist.store') }}" method="POST">
            @csrf
            <input type="hidden" name="jobc_id" value="{{ $jobcId }}">

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-8">
                @php
                $items = [
                    'USB'             => ['label' => 'USB Flash Drive',    'default' => false],
                    'Reader'          => ['label' => 'Card Reader',         'default' => false],
                    'AshTray'         => ['label' => 'Ash Tray',            'default' => true],
                    'Lighter'         => ['label' => 'Lighter',             'default' => true],
                    'WiperBlades'     => ['label' => 'Wiper Blades',        'default' => false],
                    'SeatCovers'      => ['label' => 'Seat Covers',         'default' => false],
                    'DickeyMat'       => ['label' => 'Dickey Mat',          'default' => false],
                    'SpareWheel'      => ['label' => 'Spare Wheel',         'default' => true],
                    'JackHandle'      => ['label' => 'Jack Handle',         'default' => true],
                    'Tools'           => ['label' => 'Tools',               'default' => true],
                    'Perfume'         => ['label' => 'Perfume',             'default' => false],
                    'Remote'          => ['label' => 'Any Remote',          'default' => true],
                    'FloorMats'       => ['label' => 'Floor Mats',          'default' => true],
                    'RearViewMirrors' => ['label' => 'Rear View Mirrors',   'default' => true],
                    'Cassettes'       => ['label' => 'Cassettes / CDs',     'default' => false],
                    'Hubcaps'         => ['label' => 'Hub Caps',            'default' => true],
                    'Wheelcaps'       => ['label' => 'Wheel Caps',          'default' => true],
                    'Monograms'       => ['label' => 'Monograms',           'default' => false],
                    'Noofkeys'        => ['label' => 'Keys',                'default' => false],
                    'RadioAntenna'    => ['label' => 'Radio Antenna',       'default' => true],
                    'Clock'           => ['label' => 'Clock',               'default' => true],
                    'Nav_sys'         => ['label' => 'Navigation System',   'default' => false],
                ];
                @endphp

                @foreach($items as $name => $item)
                <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-md cursor-pointer hover:bg-green-50 has-[:checked]:border-green-400 has-[:checked]:bg-green-50 transition-colors">
                    <input type="checkbox" name="{{ $name }}"
                           {{ $item['default'] ? 'checked' : '' }}
                           class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <span class="text-sm text-gray-700">{{ $item['label'] }}</span>
                </label>
                @endforeach
            </div>

            <div class="text-center">
                <button type="submit"
                        class="px-10 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors">
                    <i class="fa fa-check mr-2"></i> Submit &amp; Open RO
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
