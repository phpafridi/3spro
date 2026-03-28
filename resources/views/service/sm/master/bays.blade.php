@extends('layouts.master')
@section('title', 'SM - Bays Management')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>
@endif

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Add Bay Form -->
    <div class="w-full lg:w-1/3">
        <div class="bg-white rounded shadow-sm p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Add Bay</h2>
            <form method="POST" action="{{ route('sm.master.bays.store') }}">
                @csrf
                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Bay Name <span class="text-red-500">*</span></label>
                        <input type="text" name="bay_name" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Category</label>
                        <select name="category" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="M">Mechanical</option>
                            <option value="DP">Dent Paint</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Type</label>
                        <select name="bay_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>PM</option>
                            <option>GR</option>
                            <option>BP</option>
                            <option>EM</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                        <i class="fa fa-plus mr-2"></i> Add Bay
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bays List -->
    <div class="w-full lg:w-2/3">
        <div class="bg-white rounded shadow-sm p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Bays
                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $bays->count() }}</span>
                </h2>
                <input type="text" id="bay_search" placeholder="Search bays..." class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-44">
            </div>

            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden border border-gray-200 sm:rounded">
                        <table class="min-w-full divide-y divide-gray-200" id="bays_table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bay Name</th>
                                    <th class="hidden sm:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="hidden md:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($bays as $b)
                                <tr class="hover:bg-gray-50 bay-row">
                                    <td class="px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm font-medium text-gray-800 whitespace-nowrap">{{ $b->bay_name }}</td>
                                    <td class="hidden sm:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $b->category ?? '-' }}</td>
                                    <td class="hidden md:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $b->bay_type ?? '-' }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm whitespace-nowrap">
                                        <button type="button"
                                            class="edit-bay-btn text-blue-600 hover:text-blue-800 p-1 mr-1" title="Edit"
                                            data-id="{{ $b->id }}"
                                            data-name="{{ $b->bay_name }}"
                                            data-category="{{ $b->category }}"
                                            data-type="{{ $b->bay_type }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form method="POST" action="{{ route('sm.master.bays.delete') }}" class="inline" onsubmit="return confirm('Delete this bay?')">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $b->id }}">
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-3 sm:px-4 py-8 text-center text-gray-400">No bays found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="block sm:hidden mt-2 text-xs text-gray-400 text-center">
                <i class="fa fa-arrow-left mr-1"></i> Scroll horizontally to see more <i class="fa fa-arrow-right ml-1"></i>
            </div>
        </div>
    </div>
</div>

<!-- Edit Bay Modal -->
<div id="editBayModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Edit Bay</h3>
            <button type="button" id="closeBayModal" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('sm.master.bays.update') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="id" id="edit_bay_id">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Bay Name <span class="text-red-500">*</span></label>
                <input type="text" name="bay_name" id="edit_bay_name" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Category</label>
                <select name="category" id="edit_bay_category" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="M">Mechanical</option>
                    <option value="DP">Dent Paint</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Type</label>
                <select name="bay_type" id="edit_bay_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>PM</option>
                    <option>GR</option>
                    <option>BP</option>
                    <option>EM</option>
                </select>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fa fa-save mr-2"></i> Save Changes
                </button>
                <button type="button" id="cancelBayModal" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Search
document.getElementById('bay_search').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#bays_table tbody .bay-row').forEach(function(tr) {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

// Edit Modal
var bayModal = document.getElementById('editBayModal');
document.querySelectorAll('.edit-bay-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('edit_bay_id').value   = this.dataset.id;
        document.getElementById('edit_bay_name').value = this.dataset.name;
        var catSel  = document.getElementById('edit_bay_category');
        var typeSel = document.getElementById('edit_bay_type');
        Array.from(catSel.options).forEach(function(o)  { o.selected = o.value === btn.dataset.category; });
        Array.from(typeSel.options).forEach(function(o) { o.selected = o.value === btn.dataset.type; });
        bayModal.classList.remove('hidden');
        bayModal.classList.add('flex');
    });
});
document.getElementById('closeBayModal').addEventListener('click', closeBayModal);
document.getElementById('cancelBayModal').addEventListener('click', closeBayModal);
bayModal.addEventListener('click', function(e) { if (e.target === bayModal) closeBayModal(); });
function closeBayModal() { bayModal.classList.add('hidden'); bayModal.classList.remove('flex'); }
</script>
@endpush
@endsection
