@extends('layouts.master')
@section('title', 'Edit Vehicle')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Edit Vehicle Data</h2>
            <a href="{{ route('jobcard.vehicle-detail', ['vehicle_id' => $vehicleId]) }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        <form action="{{ route('jobcard.vehicle.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $vehicleId }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Registration <span class="text-red-500">*</span></label>
                    <input type="text" name="registration" required style="text-transform:uppercase"
                           value="{{ old('registration', $vehicle->Registration) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frame / Chassis No</label>
                    <input type="text" name="fram" style="text-transform:uppercase"
                           value="{{ old('fram', $vehicle->Frame_no) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Variant <span class="text-red-500">*</span></label>
                    <input type="text" name="varaint" required
                           value="{{ old('varaint', $vehicle->Variant) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Model Code</label>
                    <input type="text" name="model" style="text-transform:uppercase"
                           value="{{ old('model', $vehicle->Model) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
                    <input type="text" name="make"
                           value="{{ old('make', $vehicle->Make) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Engine Code</label>
                    <input type="text" name="engine" style="text-transform:uppercase"
                           value="{{ old('engine', $vehicle->Engine_Code) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Engine Number</label>
                    <input type="text" name="engine_no" style="text-transform:uppercase"
                           value="{{ old('engine_no', $vehicle->Engine_number ?? '') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Colour</label>
                    <input type="text" name="color"
                           value="{{ old('color', $vehicle->Colour) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Model Year</label>
                    <input type="text" name="model_year"
                           value="{{ old('model_year', $vehicle->model_year) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="intosell" id="intosell" value="1"
                       {{ ($vehicle->into_sell === 'on') ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                <label for="intosell" class="text-sm text-gray-700">Mark as For Sale</label>
            </div>
            <div id="demand_row" class="{{ ($vehicle->into_sell === 'on') ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">Demand Price</label>
                <input type="number" name="demandprice"
                       value="{{ old('demandprice', $vehicle->demand_price) }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fa fa-save mr-2"></i> Update Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('intosell').addEventListener('change', function () {
    document.getElementById('demand_row').classList.toggle('hidden', !this.checked);
});
</script>
@endpush
@endsection
