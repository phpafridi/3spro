@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'GSL Details')

@section('content')
<div class="bg-white rounded shadow-sm p-6">

    {{-- Header --}}
    <table class="w-full mb-4">
        <tr>
            <td>
                <h2 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-stream text-purple-500 mr-2"></i>GSL Details
                </h2>
            </td>
            @if($filterGlId)
            <td class="text-right">
                <button type="button" onclick="document.getElementById('addGslModal').classList.remove('hidden')"
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

    {{-- Breadcrumb: Main Account > GL Name > (GSL page) — same as Accounts hlabel trail --}}
    <div class="flex flex-wrap gap-2 mb-5 items-center">
        <a href="{{ route('accounts.coa') }}"
            class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-sm">
            {{ $selectedMa->main_account ?? 'Charts of Accounts' }} &rsaquo;
        </a>
        @if($selectedGl)
        {{-- Click GL breadcrumb → go back to GL list for this main account --}}
        <a href="{{ route('accounts.add-gl', ['ma_id' => $selectedGl->ma_id]) }}"
            class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-sm">
            {{ $selectedGl->GL_name }} &rsaquo;
        </a>
        <span class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-sm">
            GSL Details
        </span>
        @endif
        {{-- Search (same as Accounts) --}}
        @if($filterGlId && $gslList->count())
        <input type="text" id="gslSearch" placeholder="Search!!!"
            class="border border-gray-300 rounded px-3 py-1 text-sm ml-2 w-48">
        @endif
    </div>

    @if(!$filterGlId)
    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-300 rounded text-sm text-yellow-800">
        <i class="fas fa-info-circle mr-1"></i>
        Navigate from <a href="{{ route('accounts.coa') }}" class="underline font-semibold">Charts of Accounts</a>
        → GL to view and add GSL entries.
    </div>
    @endif

    {{-- GSL Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm" id="gslTable">
            <colgroup>
                <col style="width:3%">
                <col style="width:9%">
                <col style="width:36%">
                <col style="width:40%">
                <col style="width:12%">
            </colgroup>
            <thead style="background-color:gray; color:white;">
                <tr>
                    <th class="px-4 py-3 text-left">sr#</th>
                    <th class="px-4 py-3 text-left">GSL Code</th>
                    <th class="px-4 py-3 text-left">GSL</th>
                    <th class="px-4 py-3 text-left">Description</th>
                    <th class="px-4 py-3 text-left">
                        <a href="{{ route('accounts.coa') }}" target="_blank"
                            style="color:yellow;text-decoration:none;">Charts of Account</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($gslList as $i => $gsl)
                <tr class="hover:bg-yellow-100 border-b">
                    <td class="px-4 py-3 text-xs text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 font-mono font-bold text-purple-700">{{ $gsl->GSL_code }}</td>
                    <td class="px-4 py-3 font-medium">{{ $gsl->GSL_name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $gsl->Description }}</td>
                    <td class="px-4 py-3"></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                        @if($filterGlId)
                            No GSL entries yet. Click <strong>Add New</strong> to add one.
                        @else
                            Navigate from Charts of Accounts → GL to view GSL entries.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Add GSL Modal — GSL Code auto-filled instantly (same as Accounts: next_id pre-computed) --}}
@if($filterGlId && $selectedGl)
<div id="addGslModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h5 class="font-semibold text-gray-800">GSL Form:</h5>
            <button onclick="document.getElementById('addGslModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>
        <form method="POST" action="{{ route('accounts.add-gsl') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="GL_id" value="{{ $filterGlId }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">GSL Code</label>
                {{-- Auto-filled by controller — same as Accounts $next_id shown readonly --}}
                <input type="number" name="GSL_code" readonly value="{{ $nextCode }}"
                    class="w-full border border-gray-200 bg-gray-50 rounded px-4 py-2 text-sm font-mono font-bold text-purple-700">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">GSL Name</label>
                <input type="text" name="GSL_name" required placeholder="e.g. Cash in Hand"
                    class="w-full border border-gray-300 rounded px-4 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <input type="text" name="Description" required placeholder="Detailed description"
                    class="w-full border border-gray-300 rounded px-4 py-2 text-sm">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('addGslModal').classList.add('hidden')"
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
const gslSearch = document.getElementById('gslSearch');
if (gslSearch) {
    gslSearch.addEventListener('keyup', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#gslTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
}
</script>
@endpush
@endsection
