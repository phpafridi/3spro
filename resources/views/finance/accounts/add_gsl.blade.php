@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Accounts - GSL Details')

@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-1">
        <i class="fas fa-stream text-purple-500 mr-2"></i>General Sub-Ledger (GSL)
    </h2>
    <p class="text-sm text-gray-400 mb-6">
        Hierarchy: Main Account → GL → <strong class="text-red-600">GSL</strong>
    </p>

    {{-- Step 1: Select GL --}}
    <div class="mb-6">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
            <span class="bg-purple-100 text-purple-700 rounded-full px-2 py-0.5 mr-1">Step 1</span>
            Select GL to drill into
        </p>
        <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto">
            <a href="{{ route('accounts.add-gsl') }}"
               class="px-4 py-2 rounded text-sm border transition
                      {{ !$filterGlId ? 'bg-red-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-300 hover:border-purple-400' }}">
                All GSL
            </a>
            @foreach($glList as $gl)
            <a href="{{ route('accounts.add-gsl', ['GL_id' => $gl->GL_id]) }}"
               class="px-4 py-2 rounded text-sm border transition
                      {{ $filterGlId == $gl->GL_id ? 'bg-red-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-300 hover:border-purple-400' }}">
                {{ $gl->GL_name }}
                <span class="text-xs opacity-60 ml-1">({{ number_format($gl->rang_start) }}–{{ number_format($gl->rang_end) }})</span>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Add GSL Form — only when a GL is selected --}}
    @if($filterGlId)
    @php $selectedGl = $glList->firstWhere('GL_id', $filterGlId); @endphp
    <div class="mb-8 p-5 bg-purple-50 border border-purple-200 rounded">
        <h3 class="text-sm font-semibold text-purple-700 mb-4">
            <i class="fas fa-plus-circle mr-1"></i>
            Add GSL under GL: <strong>{{ $selectedGl->GL_name ?? '' }}</strong>
            <span class="text-xs font-normal text-purple-400 ml-2">
                (Range: {{ number_format($selectedGl->rang_start ?? 0) }} – {{ number_format($selectedGl->rang_end ?? 0) }})
            </span>
        </h3>

        <form method="POST" action="{{ route('accounts.add-gsl') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="GL_id" value="{{ $filterGlId }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">GSL Name <span class="text-red-500">*</span></label>
                    <input type="text" name="GSL_name" required
                           placeholder="e.g. Cash in Hand"
                           class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-purple-400">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                    <input type="text" name="Description"
                           placeholder="Optional detailed description"
                           class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-purple-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">GSL Code</label>
                    <input type="number" name="GSL_code" id="inp_gsl_code" readonly required
                           class="w-full border border-gray-200 rounded px-4 py-2 text-sm bg-yellow-50 font-mono font-bold text-purple-700">
                    <p class="text-xs text-gray-400 mt-1">Auto-incremented from last GSL in this GL</p>
                </div>
            </div>

            <div class="flex gap-3 items-center flex-wrap">
                <button type="button" onclick="loadNextGSL()"
                        id="loadGslBtn"
                        class="px-4 py-2 bg-yellow-500 text-white rounded text-sm font-medium hover:bg-yellow-600 transition">
                    <i class="fas fa-magic mr-1"></i>Auto-fill GSL Code
                </button>
                <button type="submit" id="submitGslBtn" disabled
                        class="px-5 py-2 bg-red-600 text-white rounded text-sm font-medium hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    <i class="fas fa-save mr-1"></i>Save GSL
                </button>
                <span id="gslHint" class="text-xs text-purple-500 italic"></span>
            </div>
        </form>
    </div>
    @endif

    {{-- GSL List --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-red-600 to-pink-500">
                <tr>
                    @foreach(['#','GSL Code','GSL Name','Description','GL','Status'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($gslList as $i => $gsl)
                <tr class="hover:bg-yellow-50">
                    <td class="px-4 py-3 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 font-mono font-bold text-purple-700">{{ $gsl->GSL_code }}</td>
                    <td class="px-4 py-3 font-medium">{{ $gsl->GSL_name }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $gsl->Description }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        @php $parentGl = $glList->firstWhere('GL_id', $gsl->GL_id); @endphp
                        {{ $parentGl->GL_name ?? $gsl->GL_id }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $gsl->gsl_status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $gsl->gsl_status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        @if($filterGlId)
                            No GSL entries yet under this GL. Click <strong>Auto-fill GSL Code</strong> above to add one.
                        @else
                            No GSL entries found. Select a GL above to begin.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function loadNextGSL() {
    const glId = {{ $filterGlId ?? 'null' }};
    if (!glId) return;

    const btn = document.getElementById('loadGslBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Loading...';
    btn.disabled  = true;

    fetch(`{{ route('accounts.add-gsl') }}?get_next_gsl=${glId}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }

        document.getElementById('inp_gsl_code').value   = data.GSL_code;
        document.getElementById('submitGslBtn').disabled = false;
        document.getElementById('gslHint').textContent  = `Next code: ${Number(data.GSL_code).toLocaleString()}`;

        btn.innerHTML = '<i class="fas fa-check mr-1"></i>Code Loaded';
        btn.disabled  = false;
    })
    .catch(() => {
        alert('Could not load next GSL code. Please try again.');
        btn.innerHTML = '<i class="fas fa-magic mr-1"></i>Auto-fill GSL Code';
        btn.disabled  = false;
    });
}

@if($filterGlId)
document.addEventListener('DOMContentLoaded', () => loadNextGSL());
@endif
</script>
@endpush
@endsection
