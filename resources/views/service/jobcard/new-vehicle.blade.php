@extends('layouts.master')
@section('title', 'Add Vehicle Details')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')

@if($errors->any())
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
</div>
@endif

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Add Vehicle Details</h2>
            <a href="{{ route('jobcard.add-vehicle') }}" class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>

        <form id="myform" action="{{ route('jobcard.add-vehicle.new.store') }}" method="POST"
              enctype="multipart/form-data" class="space-y-4" novalidate>
            @csrf

            {{-- Registration: readonly if searched by it, editable if searched by frame --}}
            @if($field === 'Registration')
                <input type="hidden" name="registration" value="{{ strtoupper($regFam) }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Registration # <span class="text-red-500">*</span></label>
                    <input type="text" readonly value="{{ strtoupper($regFam) }}"
                           class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Chassis # <span class="text-red-500">*</span></label>
                    <input type="text" name="fram" required style="text-transform:uppercase" minlength="3"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            @else
                <input type="hidden" name="fram" value="{{ strtoupper($regFam) }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Chassis # <span class="text-red-500">*</span></label>
                    <input type="text" readonly value="{{ strtoupper($regFam) }}"
                           class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Registration # <span class="text-red-500">*</span></label>
                    <input type="text" name="registration" required style="text-transform:uppercase" minlength="3"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            @endif

            {{-- Model code — autocomplete via countryname_1 (auto.js) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model Code <span class="text-red-500">*</span></label>
                <input type="text" name="model" id="countryname_1" required minlength="2"
                       style="text-transform:uppercase"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. AXV50">
            </div>

            {{-- Variant — auto-filled by auto.js via country_no_1 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Variant <span class="text-red-500">*</span></label>
                <input type="text" name="varaint" id="country_no_1" required minlength="2"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. Corolla XLI 1.3">
            </div>

            {{-- Make — auto-filled by auto.js via phone_code_1 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Make <span class="text-red-500">*</span></label>
                <input type="text" name="make" id="phone_code_1" required minlength="2"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. Toyota">
            </div>

            {{-- Engine Code — auto-filled by auto.js via country_code_1 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Engine Code <span class="text-red-500">*</span></label>
                <input type="text" name="engine" id="country_code_1" required minlength="2"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g. 2NZ">
            </div>

            {{-- Model Year — dropdown exactly as original --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model Year <span class="text-red-500">*</span></label>
                <select name="model_year" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(range(date('Y') + 2, 1990) as $yr)
                    <option value="{{ $yr }}" {{ $yr == 2019 ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Engine Number --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Engine Number</label>
                <input type="text" name="engine_no"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Color --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <input type="text" name="color"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Into Sell checkbox --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="intosell" id="intosell_chk" value="on"
                       class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                <label for="intosell_chk" class="text-sm text-gray-700">Mark as For Sale</label>
            </div>

            {{-- Demand price + photo capture — shown only when intosell checked (matches original JS) --}}
            <div id="intosell_div"></div>

            <input type="hidden" name="veh_info" value="1">

            <div class="pt-2">
                <button id="send" type="submit"
                        class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                    ADD Vehicle Details &raquo;
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.ac-dropdown {
    position: absolute;
    background: white;
    border: 1px solid #d1d5db;
    border-top: none;
    border-radius: 0 0 6px 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 9999;
    max-height: 220px;
    overflow-y: auto;
    width: 100%;
}
.ac-dropdown div {
    padding: 8px 12px;
    cursor: pointer;
    font-size: 13px;
    color: #111827;
}
.ac-dropdown div:hover,
.ac-dropdown div.active {
    background: #ef4444;
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
// Autocomplete using axios (npm install axios)
// Response: ["2002|HS|toyota|1.4|1"]  →  Model|Variant|Make|Engine|row_num

function fillFields(model, variant, make, engine) {
    document.getElementById('countryname_1').value  = model;
    document.getElementById('country_no_1').value   = variant;
    document.getElementById('phone_code_1').value   = make;
    document.getElementById('country_code_1').value = engine;
}

function showDropdown(inputEl, items) {
    removeDropdown();
    if (!items.length) return;

    var wrapper = document.createElement('div');
    wrapper.className = 'ac-dropdown';
    wrapper.id = 'ac-dropdown';
    // position under input
    inputEl.parentNode.style.position = 'relative';
    inputEl.parentNode.appendChild(wrapper);

    items.forEach(function(item) {
        var parts = item.split('|');
        var model   = parts[0];
        var variant = parts[1];
        var make    = parts[2];
        var engine  = parts[3];

        var row = document.createElement('div');
        row.textContent = model;   // show Model code in dropdown — e.g. "2002"
        row.addEventListener('mousedown', function(e) {
            e.preventDefault();   // stop blur firing before click
            fillFields(model, variant, make, engine);
            removeDropdown();
        });
        wrapper.appendChild(row);
    });
}

function removeDropdown() {
    var el = document.getElementById('ac-dropdown');
    if (el) el.remove();
}

function setupAutocomplete(inputId, searchField) {
    var input = document.getElementById(inputId);
    var timer;

    input.addEventListener('input', function() {
        clearTimeout(timer);
        var val = this.value;
        if (val.length < 2) { removeDropdown(); return; }

        timer = setTimeout(function() {
            axios.post('{{ route("jobcard.ajax.variant") }}', {
                type:            'country_table',
                name_startsWith: val,
                search_field:    searchField
            })
            .then(function(response) {
                showDropdown(input, response.data);
            })
            .catch(function(err) {
                console.error('Autocomplete error:', err);
            });
        }, 300);
    });

    input.addEventListener('blur', function() {
        // small delay so mousedown on dropdown fires first
        setTimeout(removeDropdown, 150);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Model field — original auto.js behaviour
    setupAutocomplete('countryname_1', 'Model');

    // Variant field — type variant, fills Model/Make/Engine
    setupAutocomplete('country_no_1', 'Variant');
});

// Intosell checkbox
document.getElementById('intosell_chk').addEventListener('change', function () {
    if (this.checked) {
        document.getElementById('intosell_div').innerHTML =
            "<div class='mt-3'><label class='block text-sm font-medium text-gray-700 mb-1'>Demand Price</label>" +
            "<input type='text' placeholder='Rs' name='demandprice' class='w-full border border-gray-300 rounded-md px-3 py-2 text-sm'></div>" +
            "<div class='mt-3'><label class='block text-sm font-medium text-gray-700 mb-2'>Take Pics</label>" +
            "<div class='flex flex-wrap gap-2'>" +
            "<label for='front'    class='px-3 py-2 bg-black text-white text-sm rounded cursor-pointer'>Front Pic</label>" +
            "<input type='file' name='front'    id='front'    accept='image/*' capture='camera' style='display:none'>" +
            "<label for='interior' class='px-3 py-2 bg-black text-white text-sm rounded cursor-pointer'>Interior Pic</label>" +
            "<input type='file' name='interior' id='interior' accept='image/*' capture='camera' style='display:none'>" +
            "<label for='side'     class='px-3 py-2 bg-black text-white text-sm rounded cursor-pointer'>Side Pic</label>" +
            "<input type='file' name='side'     id='side'     accept='image/*' capture='camera' style='display:none'>" +
            "<label for='back'     class='px-3 py-2 bg-black text-white text-sm rounded cursor-pointer'>Back Pic</label>" +
            "<input type='file' name='back'     id='back'     accept='image/*' capture='camera' style='display:none' multiple>" +
            "</div></div>";
    } else {
        document.getElementById('intosell_div').innerHTML = '';
    }
});
</script>
@endpush
@endsection
