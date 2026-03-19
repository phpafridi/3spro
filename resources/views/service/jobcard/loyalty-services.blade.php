@extends('layouts.master')
@section('title', 'Loyalty Card Services')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Loyalty Card Services</h2>

        @if(!$loyalty)
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-md text-yellow-800 text-sm">
            No loyalty card found for this vehicle.
        </div>
        @else
        @php
        $services = [
            'Free_Engine_Tuning'         => 'Free Engine Tuning',
            'Free_Computer_Scaning'      => 'Free Computer Scanning',
            'Free_Wheel_Alignment'       => 'Free Wheel Alignment',
            'Free_Top_Wash'              => 'Free Top Wash',
            'Free_Electric_Checkup'      => 'Free Electric Checkup',
            'Free_General_Inspection'    => 'Free General Inspection',
            'Free_Suspension_Checkup'    => 'Free Suspension Checkup',
            'compound_Polish_25OFF'      => 'Compound Polish 25% OFF',
            'Throttle_body_service_50OFF'=> 'Throttle Body Service 50% OFF',
            'Free_AC_heater_Inspection'  => 'Free AC/Heater Inspection',
            'Brake_Service_50OFF'        => 'Brake Service 50% OFF',
            'General_Repair_25OFF'       => 'General Repair 25% OFF',
            'Wheel_Alignment_50OFF'      => 'Wheel Alignment 50% OFF',
            'AC_service_50OFF'           => 'AC Service 50% OFF',
            'Wheel_Balancing_50OFF'      => 'Wheel Balancing 50% OFF',
            'Dent_Paint_2Pieces_50OFF'   => 'Dent & Paint 2 Pieces 50% OFF',
        ];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($services as $col => $label)
            @php $done = ($loyalty->$col ?? '') === 'Done'; @endphp
            <div class="flex items-center justify-between p-3 border rounded-md {{ $done ? 'bg-gray-100 opacity-60' : 'bg-white' }}">
                <span class="text-sm {{ $done ? 'line-through text-gray-400' : 'text-gray-800' }}">{{ $label }}</span>
                @if(!$done && $jobId)
                <form method="POST" action="{{ route('jobcard.loyalty-services') }}">
                    @csrf
                    <input type="hidden" name="veh_id" value="{{ $vehId }}">
                    <input type="hidden" name="job_id" value="{{ $jobId }}">
                    <input type="hidden" name="labor" value="{{ $col }}">
                    <input type="hidden" name="cost" value="0">
                    <button type="submit"
                            class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded">
                        Use
                    </button>
                </form>
                @elseif($done)
                <span class="text-xs text-green-600 font-semibold">✓ Done</span>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
