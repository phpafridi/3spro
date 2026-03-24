@extends('parts.layout')
@section('title', 'Edit Location - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Edit Part Location</h2>
</div>
@if(session('success'))<div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">{{ session('success') }}</div>@endif
<div class="max-w-xl">
<div class="bg-white rounded shadow-sm border border-gray-200 p-6">
<form action="{{ route('parts.location-change.update') }}" method="POST">
@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Part Number <span class="text-red-500">*</span></label>
        <input type="text" name="typeahead" id="locPartSearch" required autocomplete="off"
               class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500" placeholder="Search part number...">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">New Location <span class="text-red-500">*</span></label>
        <input type="text" name="location" required
               class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500" placeholder="e.g. Shelf B-3">
    </div>
</div>
<div class="mt-6">
    <button type="submit" class="w-full bg-red-600 text-white py-2.5 rounded font-medium hover:bg-red-700 transition-all">Update Location</button>
</div>
</form>
</div>
</div>
@endsection
@push('scripts')
<script>
const locSearch = document.getElementById('locPartSearch');
let lt;
locSearch.addEventListener('input', function() {
    clearTimeout(lt);
    lt = setTimeout(() => {
        fetch('{{ route("parts.ajax.search-part") }}?key=' + encodeURIComponent(this.value))
            .then(r => r.json())
            .then(data => {
                let list = document.getElementById('locList') || document.createElement('datalist');
                list.id = 'locList';
                document.body.appendChild(list);
                locSearch.setAttribute('list', 'locList');
                list.innerHTML = data.map(p => `<option value="${p.value}">${p.label}</option>`).join('');
            });
    }, 300);
});
</script>
@endpush
