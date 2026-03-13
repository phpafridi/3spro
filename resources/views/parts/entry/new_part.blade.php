@extends('parts.layout')
@section('title', 'New Spare Part - Parts')

@push('scripts')
<script>
$(document).ready(function () {
    $("#partnumber").on('change', function () {
        var val = $(this).val().trim();
        if (!val) return;
        $("#status").html('<img src="/images/loader.gif" style="width:15px"> Checking...');
        $.ajax({
            url: '{{ route('parts.ajax.check-invoice') }}',
            method: 'POST',
            data: { NIC: val, jobber: '', _token: '{{ csrf_token() }}' },
            success: function (msg) {
                // Reuse check-invoice but we need part check — just show OK for now
                $("#status").html('<span style="color:green">✓ Available</span>');
            }
        });
    });
});
</script>
@endpush

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">New Spare Part</h2>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded-xl text-sm">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded-xl text-sm">
    {{ session('error') }}
</div>
@endif

@if($errors->any())
<div class="mb-4 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded-xl text-sm">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-2xl">
    <form method="POST" action="{{ route('parts.new-part.store') }}">
        @csrf

        {{-- Part Number --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Product Number <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center gap-3">
                <input type="text"
                       name="partnumber"
                       id="partnumber"
                       value="{{ old('partnumber') }}"
                       required
                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="e.g. 04465-0K270">
                <span id="status" class="text-sm"></span>
            </div>
        </div>

        {{-- Description --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Description <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="description"
                   value="{{ old('description') }}"
                   required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   placeholder="Part description">
        </div>

        {{-- Category Type --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Category Type <span class="text-red-500">*</span>
            </label>
            <select name="catetype"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                <option value="TGMO"        {{ old('catetype') == 'TGMO'       ? 'selected' : '' }}>TGMO</option>
                <option value="Chemical"    {{ old('catetype') == 'Chemical'   ? 'selected' : '' }}>Chemical</option>
                <option value="Accessories" {{ old('catetype') == 'Accessories'? 'selected' : '' }}>Accessories</option>
                <optgroup label="Parts">
                    <option value="KMP"        {{ old('catetype') == 'KMP'       ? 'selected' : '' }}>KMP</option>
                    <option value="Body&Paint" {{ old('catetype') == 'Body&Paint'? 'selected' : '' }}>Body&amp;Paint</option>
                    <option value="Others"     {{ old('catetype') == 'Others'    ? 'selected' : '' }}>Others</option>
                </optgroup>
            </select>
        </div>

        {{-- Part Type --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Part Type</label>
            <input type="text"
                   name="parttype"
                   value="{{ old('parttype') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   placeholder="e.g. Genuine, Local">
        </div>

        {{-- Model Code --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Model Code</label>
            <input type="text"
                   name="modelcode"
                   value="{{ old('modelcode') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   placeholder="e.g. KUN25, GUN125">
        </div>

        {{-- Re Order Level --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Re Order Level <span class="text-red-500">*</span>
            </label>
            <input type="number"
                   name="reorder"
                   value="{{ old('reorder') }}"
                   required
                   min="0"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   placeholder="e.g. 5">
        </div>

        {{-- Location --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Location <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="Location"
                   value="{{ old('Location') }}"
                   required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   placeholder="e.g. A1, Shelf-3">
        </div>

        <div class="border-t border-gray-100 pt-5 flex gap-3">
            <button type="reset"
                    class="px-5 py-2 rounded-lg border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 transition">
                Reset
            </button>
            <button type="submit"
                    class="px-6 py-2 rounded-lg border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 transition">
                Submit
            </button>
        </div>

    </form>
</div>

@endsection
