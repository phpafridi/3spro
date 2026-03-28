@extends('layouts.master')
@section('title', 'SM - Variant Codes')
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
    <!-- Add Variant Form -->
    <div class="w-full lg:w-1/3">
        <div class="bg-white rounded shadow-sm p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Add Variant</h2>
            <form method="POST" action="{{ route('sm.master.variants.store') }}">
                @csrf
                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Variant <span class="text-red-500">*</span></label>
                        <input type="text" name="Variant" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Model</label>
                        <input type="text" name="Model" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Make</label>
                        <input type="text" name="Make" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Vehicle Type</label>
                        <input type="text" name="Fram" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Engine</label>
                        <input type="text" name="Engine" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Category</label>
                        <input type="text" name="Category" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                        <i class="fa fa-plus mr-2"></i> Add Variant
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Variants List -->
    <div class="w-full lg:w-2/3">
        <div class="bg-white rounded shadow-sm p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Variants
                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $variants->count() }}</span>
                </h2>
                <input type="text" id="variant_search" placeholder="Search variants..." class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-48">
            </div>

            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden border border-gray-200 sm:rounded">
                        <table class="min-w-full divide-y divide-gray-200" id="variants_table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                                    <th class="hidden sm:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model</th>
                                    <th class="hidden md:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Make</th>
                                    <th class="hidden lg:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="hidden lg:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Engine</th>
                                    <th class="hidden xl:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($variants as $v)
                                <tr class="hover:bg-gray-50 variant-row">
                                    <td class="px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $v->variant_id }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm font-medium text-gray-800 whitespace-nowrap">{{ $v->Variant }}</td>
                                    <td class="hidden sm:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $v->Model ?? '-' }}</td>
                                    <td class="hidden md:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $v->Make ?? '-' }}</td>
                                    <td class="hidden lg:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $v->Fram ?? '-' }}</td>
                                    <td class="hidden lg:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $v->Engine ?? '-' }}</td>
                                    <td class="hidden xl:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $v->Category ?? '-' }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm whitespace-nowrap">
                                        <button type="button"
                                            class="edit-variant-btn text-blue-600 hover:text-blue-800 p-1 mr-1" title="Edit"
                                            data-id="{{ $v->variant_id }}"
                                            data-variant="{{ $v->Variant }}"
                                            data-model="{{ $v->Model }}"
                                            data-make="{{ $v->Make }}"
                                            data-fram="{{ $v->Fram }}"
                                            data-engine="{{ $v->Engine }}"
                                            data-category="{{ $v->Category }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form method="POST" action="{{ route('sm.master.variants.delete') }}" class="inline" onsubmit="return confirm('Delete this variant?')">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $v->variant_id }}">
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-3 sm:px-4 py-8 text-center text-gray-400">No variants found.</td>
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

<!-- Edit Variant Modal -->
<div id="editVariantModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Edit Variant</h3>
            <button type="button" id="closeVariantModal" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('sm.master.variants.update') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="id" id="edit_variant_id">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Variant <span class="text-red-500">*</span></label>
                <input type="text" name="Variant" id="edit_variant_name" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Model</label>
                <input type="text" name="Model" id="edit_variant_model" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Make</label>
                <input type="text" name="Make" id="edit_variant_make" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Vehicle Type</label>
                <input type="text" name="Fram" id="edit_variant_fram" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Engine</label>
                <input type="text" name="Engine" id="edit_variant_engine" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Category</label>
                <input type="text" name="Category" id="edit_variant_category" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fa fa-save mr-2"></i> Save Changes
                </button>
                <button type="button" id="cancelVariantModal" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Search
document.getElementById('variant_search').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#variants_table tbody .variant-row').forEach(function(tr) {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

// Edit Modal
var modal = document.getElementById('editVariantModal');
document.querySelectorAll('.edit-variant-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('edit_variant_id').value       = this.dataset.id;
        document.getElementById('edit_variant_name').value     = this.dataset.variant;
        document.getElementById('edit_variant_model').value    = this.dataset.model || '';
        document.getElementById('edit_variant_make').value     = this.dataset.make || '';
        document.getElementById('edit_variant_fram').value     = this.dataset.fram || '';
        document.getElementById('edit_variant_engine').value   = this.dataset.engine || '';
        document.getElementById('edit_variant_category').value = this.dataset.category || '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });
});
document.getElementById('closeVariantModal').addEventListener('click', closeModal);
document.getElementById('cancelVariantModal').addEventListener('click', closeModal);
modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });
function closeModal() { modal.classList.add('hidden'); modal.classList.remove('flex'); }
</script>
@endpush
@endsection
