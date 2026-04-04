@extends('parts.layout')
@section('title', 'New Spare Part')
@section('content')

<div class="mb-4">
    <h2 class="text-xl font-bold text-gray-800">Add New Spare Part</h2>
</div>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-800 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-800 text-sm">{{ session('error') }}</div>
@endif
@if($errors->any())
<div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-800 text-sm">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
</div>
@endif

<div class="max-w-lg">
    <div class="bg-white rounded shadow-sm border border-gray-200 p-5">
        <form action="{{ route('parts.new-part.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Part Number <span class="text-red-500">*</span></label>
                <input type="text" name="partnumber" required value="{{ old('partnumber') }}"
                       style="text-transform:uppercase"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="e.g. 04465-0K060">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <input type="text" name="description" required value="{{ old('description') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Part description">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select name="catetype" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Category --</option>
                   
                    <option value="Chemical"     {{ old('catetype')=='Chemical'     ? 'selected':'' }}>Chemical</option>
                    <option value="Accessories"  {{ old('catetype')=='Accessories'  ? 'selected':'' }}>Accessories</option>
                    <optgroup label="Parts">
                        <option value="KMP"        {{ old('catetype')=='KMP'        ? 'selected':'' }}>KMP</option>
                        <option value="Body&Paint" {{ old('catetype')=='Body&Paint' ? 'selected':'' }}>Body&amp;Paint</option>
                        <option value="Others"     {{ old('catetype')=='Others'     ? 'selected':'' }}>Others</option>
                    </optgroup>
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Part Type</label>
                <select name="parttype"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select --</option>
                    <option value="OEM">OEM</option>
                    <option value="Local"    {{ old('parttype')=='Local'    ? 'selected':'' }}>Local</option>
                    <option value="Imported" {{ old('parttype')=='Imported' ? 'selected':'' }}>Imported</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Model Code</label>
                <input type="text" name="modelcode" value="{{ old('modelcode') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="e.g. 2002">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Re-Order Level <span class="text-red-500">*</span></label>
                <input type="number" name="reorder" required value="{{ old('reorder', 0) }}" min="0"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input type="text" name="Location" value="{{ old('Location') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Shelf / bin location">
            </div>

            <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-medium text-sm transition-colors">
                Add Part
            </button>
        </form>
    </div>
</div>
@endsection
