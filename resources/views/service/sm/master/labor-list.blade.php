@extends('layouts.master')
@section('title', 'SM - Labor List')
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
    <!-- Add Labor Form -->
    <div class="w-full lg:w-1/3">
        <div class="bg-white rounded shadow-sm p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Add Labor</h2>
            <form method="POST" action="{{ route('sm.master.labor.store') }}">
                @csrf
                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Labor <span class="text-red-500">*</span></label>
                        <input type="text" name="Labor" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Cate1</label>
                        <input type="text" name="Cate1" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Cate2</label>
                        <input type="text" name="Cate2" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Cate3</label>
                        <input type="text" name="Cate3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Cate4</label>
                        <input type="text" name="Cate4" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Cate5</label>
                        <input type="text" name="Cate5" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                        <i class="fa fa-plus mr-2"></i> Add Labor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Labor List -->
    <div class="w-full lg:w-2/3">
        <div class="bg-white rounded shadow-sm p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Labor List
                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $laborList->count() }}</span>
                </h2>
                <input type="text" id="labor_search" placeholder="Search labor..." class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-48">
            </div>

            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden border border-gray-200 sm:rounded">
                        <table class="min-w-full divide-y divide-gray-200" id="labor_table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                                    <th class="hidden sm:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cate1</th>
                                    <th class="hidden md:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cate2</th>
                                    <th class="hidden lg:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cate3</th>
                                    <th class="hidden xl:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cate4</th>
                                    <th class="hidden 2xl:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cate5</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($laborList as $l)
                                <tr class="hover:bg-gray-50 labor-row">
                                    <td class="px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Labor_ID }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm font-medium text-gray-800 whitespace-nowrap">{{ $l->Labor }}</td>
                                    <td class="hidden sm:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Cate1 ?? '-' }}</td>
                                    <td class="hidden md:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Cate2 ?? '-' }}</td>
                                    <td class="hidden lg:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Cate3 ?? '-' }}</td>
                                    <td class="hidden xl:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Cate4 ?? '-' }}</td>
                                    <td class="hidden 2xl:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Cate5 ?? '-' }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm whitespace-nowrap">
                                        <button type="button"
                                            class="edit-labor-btn text-blue-600 hover:text-blue-800 p-1 mr-1" title="Edit"
                                            data-id="{{ $l->Labor_ID }}"
                                            data-labor="{{ $l->Labor }}"
                                            data-cate1="{{ $l->Cate1 }}"
                                            data-cate2="{{ $l->Cate2 }}"
                                            data-cate3="{{ $l->Cate3 }}"
                                            data-cate4="{{ $l->Cate4 }}"
                                            data-cate5="{{ $l->Cate5 }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form method="POST" action="{{ route('sm.master.labor.delete') }}" class="inline" onsubmit="return confirm('Delete this labor?')">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $l->Labor_ID }}">
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-3 sm:px-4 py-8 text-center text-gray-400">No labor found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="block sm:hidden mt-2 text-xs text-gray-400 text-center">
                <i class="fa fa-arrow-left mr-1"></i> Scroll horizontally to see all categories <i class="fa fa-arrow-right ml-1"></i>
            </div>
        </div>
    </div>
</div>

<!-- Edit Labor Modal -->
<div id="editLaborModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Edit Labor</h3>
            <button type="button" id="closeLaborModal" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('sm.master.labor.update') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="id" id="edit_labor_id">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Labor <span class="text-red-500">*</span></label>
                <input type="text" name="Labor" id="edit_labor_name" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Cate1</label>
                    <input type="text" name="Cate1" id="edit_labor_cate1" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Cate2</label>
                    <input type="text" name="Cate2" id="edit_labor_cate2" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Cate3</label>
                    <input type="text" name="Cate3" id="edit_labor_cate3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Cate4</label>
                    <input type="text" name="Cate4" id="edit_labor_cate4" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Cate5</label>
                <input type="text" name="Cate5" id="edit_labor_cate5" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fa fa-save mr-2"></i> Save Changes
                </button>
                <button type="button" id="cancelLaborModal" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Search
document.getElementById('labor_search').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#labor_table tbody .labor-row').forEach(function(tr) {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

// Edit Modal
var laborModal = document.getElementById('editLaborModal');
document.querySelectorAll('.edit-labor-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('edit_labor_id').value    = this.dataset.id;
        document.getElementById('edit_labor_name').value  = this.dataset.labor;
        document.getElementById('edit_labor_cate1').value = this.dataset.cate1 || '';
        document.getElementById('edit_labor_cate2').value = this.dataset.cate2 || '';
        document.getElementById('edit_labor_cate3').value = this.dataset.cate3 || '';
        document.getElementById('edit_labor_cate4').value = this.dataset.cate4 || '';
        document.getElementById('edit_labor_cate5').value = this.dataset.cate5 || '';
        laborModal.classList.remove('hidden');
        laborModal.classList.add('flex');
    });
});
document.getElementById('closeLaborModal').addEventListener('click', closeLaborModal);
document.getElementById('cancelLaborModal').addEventListener('click', closeLaborModal);
laborModal.addEventListener('click', function(e) { if (e.target === laborModal) closeLaborModal(); });
function closeLaborModal() { laborModal.classList.add('hidden'); laborModal.classList.remove('flex'); }
</script>
@endpush
@endsection
