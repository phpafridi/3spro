@extends('parts.layout')
@section('title', 'IMC Category Parts - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">IMC Category Part</h2>
</div>
@if(session('success'))<div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl">{{ session('error') }}</div>@endif
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<div class="bg-white rounded shadow-sm border border-gray-200 p-6">
<form action="{{ route('parts.new-cate-part.store') }}" method="POST">
@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Part Number <span class="text-red-500">*</span></label>
        <input type="text" name="typeahead" id="catPartSearch" required autocomplete="off"
               class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500" placeholder="Search part number...">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
        <input type="text" name="category" required class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sub Category <span class="text-red-500">*</span></label>
        <input type="text" name="subcategory" required class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500">
    </div>
</div>
<div class="mt-6">
    <button type="submit" class="w-full bg-red-600 text-white py-2.5 rounded font-medium hover:bg-red-700 transition-all">Add Category Part</button>
</div>
</form>
</div>
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-4 border-b border-gray-100"><h3 class="font-semibold text-gray-800">Category Parts List</h3></div>
    <div class="overflow-y-auto max-h-96">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr>
            <th class="px-3 py-2 text-left text-xs text-gray-500">Part #</th>
            <th class="px-3 py-2 text-left text-xs text-gray-500">Category</th>
            <th class="px-3 py-2 text-left text-xs text-gray-500">Sub Cat</th>
            <th class="px-3 py-2 text-center text-xs text-gray-500">Del</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-50">
        @foreach($categories as $cat)
        <tr class="hover:bg-red-50/30">
            <td class="px-3 py-2 font-medium text-gray-800">{{ $cat->partnumber }}</td>
            <td class="px-3 py-2 text-gray-600 text-xs">{{ $cat->category }}</td>
            <td class="px-3 py-2 text-gray-500 text-xs">{{ $cat->subcategory }}</td>
            <td class="px-3 py-2 text-center">
                <form action="{{ route('parts.new-cate-part.delete') }}" method="POST" onsubmit="return confirm('Delete?')">
                    @csrf
                    <input type="hidden" name="id" value="{{ $cat->id }}">
                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs">&#10005;</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
</div>
@endsection
@push('scripts')
<script>
const catPartSearch = document.getElementById('catPartSearch');
let t;
catPartSearch.addEventListener('input', function() {
    clearTimeout(t);
    t = setTimeout(() => {
        fetch('{{ route("parts.ajax.search-part") }}?key=' + encodeURIComponent(this.value))
            .then(r => r.json())
            .then(data => {
                let list = document.getElementById('catPartList') || document.createElement('datalist');
                list.id = 'catPartList';
                document.body.appendChild(list);
                catPartSearch.setAttribute('list', 'catPartList');
                list.innerHTML = data.map(p => `<option value="${p.value}">${p.label}</option>`).join('');
            });
    }, 300);
});
</script>
@endpush
