@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'GL Details')

@section('content')
<div class="bg-white rounded shadow-sm p-6">

    {{-- Header row with Add New button (same as Accounts) --}}
    <table class="w-full mb-4">
        <tr>
            <td>
                <h2 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-layer-group text-red-500 mr-2"></i>GL Details
                </h2>
            </td>
            @if($filterMaId)
            <td class="text-right">
                <button type="button" onclick="document.getElementById('addGlModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium">
                    <i class="fas fa-plus mr-1"></i> Add New
                </button>
            </td>
            @endif
        </tr>
    </table>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">{{ session('success') }}</div>
    @endif

    {{-- Breadcrumb — same as Accounts: Main Account > (current page) --}}
    <div class="flex flex-wrap gap-2 mb-5 items-center">
        <a href="{{ route('accounts.coa') }}"
            class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-sm">
            {{ $selectedMa->main_account ?? 'Charts of Accounts' }} &rsaquo;
        </a>
        @if($filterMaId)
        <span class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-sm">
            GL Details
        </span>
        @endif
    </div>

    @if(!$filterMaId)
    {{-- No main account selected — show all GLs, no add form (same as Accounts: must come via COA click) --}}
    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-300 rounded text-sm text-yellow-800">
        <i class="fas fa-info-circle mr-1"></i>
        Go to <a href="{{ route('accounts.coa') }}" class="underline font-semibold">Charts of Accounts</a>
        and click a Main Account to add GL entries.
    </div>
    @endif

    {{-- GL Table: click row → drills into GSL (same as Accounts) --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm" id="glTable">
            <colgroup>
                <col style="width:5%">
                <col style="width:40%">
                <col style="width:15%">
                <col style="width:15%">
                <col style="width:25%">
            </colgroup>
            <thead style="background-color:gray; color:white;">
                <tr>
                    <th class="px-4 py-3 text-left">sr#</th>
                    <th class="px-4 py-3 text-left">GL</th>
                    <th class="px-4 py-3 text-left">Range Start</th>
                    <th class="px-4 py-3 text-left">Range End</th>
                    <th class="px-4 py-3 text-left">
                        <a href="{{ route('accounts.coa') }}" target="_blank"
                            style="color:yellow;text-decoration:none;">View Charts of Account</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($glList as $i => $gl)
                <tr class="hover:bg-yellow-100 cursor-pointer border-b font-medium"
                    onclick="document.getElementById('form_gl_{{ $gl->GL_id }}').submit()"
                    title="Click to view GSL under {{ $gl->GL_name }}">
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $i+1 }}</td>
                    <td class="px-4 py-3 text-gray-800">
                        {{-- Navigate to GSL with all needed params (mirrors Accounts' hidden form approach) --}}
                        <form id="form_gl_{{ $gl->GL_id }}" method="GET" action="{{ route('accounts.add-gsl') }}">
                            <input type="hidden" name="GL_id"       value="{{ $gl->GL_id }}">
                            <input type="hidden" name="rang_start"  value="{{ $gl->rang_start }}">
                            <input type="hidden" name="rang_end"    value="{{ $gl->rang_end }}">
                            <input type="hidden" name="GL_name"     value="{{ $gl->GL_name }}">
                            <input type="hidden" name="ma_id"       value="{{ $filterMaId }}">
                            @if($selectedMa)
                            <input type="hidden" name="main_account" value="{{ $selectedMa->main_account }}">
                            @endif
                        </form>
                        {{ $gl->GL_name }}
                    </td>
                    <td class="px-4 py-3 font-mono">{{ number_format($gl->rang_start) }}</td>
                    <td class="px-4 py-3 font-mono">{{ number_format($gl->rang_end) }}</td>
                    <td class="px-4 py-3 text-xs text-blue-500">→ click to view GSL</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                        @if($filterMaId)
                            No GL entries yet. Click <strong>Add New</strong> to add one.
                        @else
                            Select a Main Account from Charts of Accounts to view GL entries.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Search box (same as Accounts) --}}
    @if($filterMaId && $glList->count())
    <div class="mt-3">
        <input type="text" id="glSearch" placeholder="Search..."
            class="border border-gray-300 rounded px-3 py-1 text-sm w-64">
    </div>
    @endif
</div>

{{-- Add GL Modal — range auto-filled (no button click needed, same as Accounts) --}}
@if($filterMaId && $selectedMa)
<div id="addGlModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h5 class="font-semibold text-gray-800">Add GL under: {{ $selectedMa->main_account }}</h5>
            <button onclick="document.getElementById('addGlModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>
        <form method="POST" action="{{ route('accounts.add-gl') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="ma_id" value="{{ $filterMaId }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New GL Name</label>
                <input type="text" name="GL_name" required placeholder="e.g. Cash and Cash Equivalents"
                    class="w-full border border-gray-300 rounded px-4 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Range Starts</label>
                <input type="number" name="rang_start" readonly value="{{ $nextStart }}"
                    class="w-full border border-gray-200 bg-gray-50 rounded px-4 py-2 text-sm font-mono">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Range Ends</label>
                <input type="number" name="rang_end" readonly value="{{ $nextEnd }}"
                    class="w-full border border-gray-200 bg-gray-50 rounded px-4 py-2 text-sm font-mono">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('addGlModal').classList.add('hidden')"
                    class="px-4 py-2 border border-gray-300 text-gray-600 rounded text-sm">Close</button>
                <button type="submit"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium">Add New</button>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
// Search filter (same as Accounts jquery search)
const glSearch = document.getElementById('glSearch');
if (glSearch) {
    glSearch.addEventListener('keyup', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#glTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
}
</script>
@endpush
@endsection
