@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Accounts - GL Details')

@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-1">
        <i class="fas fa-layer-group text-red-500 mr-2"></i>General Ledger (GL)
    </h2>
    <p class="text-sm text-gray-400 mb-6">
        Hierarchy: Main Account → <strong class="text-red-600">GL</strong> → GSL
    </p>

    {{-- Step 1: Select Main Account --}}
    <div class="mb-6">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
            <span class="bg-red-100 text-red-700 rounded-full px-2 py-0.5 mr-1">Step 1</span>
            Select Main Account to drill into
        </p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('accounts.add-gl') }}"
               class="px-4 py-2 rounded text-sm border transition
                      {{ !$filterMaId ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-400' }}">
                All GL
            </a>
            @foreach($mainAccounts as $ma)
            <a href="{{ route('accounts.add-gl', ['ma_id' => $ma->ma_id]) }}"
               class="px-4 py-2 rounded text-sm border transition
                      {{ $filterMaId == $ma->ma_id ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-400' }}">
                {{ $ma->main_account }}
                <span class="text-xs opacity-60 ml-1">({{ number_format($ma->rang_start) }}–{{ number_format($ma->rang_end) }})</span>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Add GL Form — only when a main account is selected --}}
    @if($filterMaId)
    @php $selectedMa = $mainAccounts->firstWhere('ma_id', $filterMaId); @endphp
    <div class="mb-8 p-5 bg-red-50 border border-red-200 rounded">
        <h3 class="text-sm font-semibold text-red-700 mb-4">
            <i class="fas fa-plus-circle mr-1"></i>
            Add GL under: <strong>{{ $selectedMa->main_account ?? '' }}</strong>
            <span class="text-xs font-normal text-indigo-400 ml-2">
                (Parent range: {{ number_format($selectedMa->rang_start ?? 0) }} – {{ number_format($selectedMa->rang_end ?? 0) }})
            </span>
        </h3>

        <form method="POST" action="{{ route('accounts.add-gl') }}" id="addGlForm" class="space-y-4">
            @csrf
            <input type="hidden" name="ma_id" value="{{ $filterMaId }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">GL Name <span class="text-red-500">*</span></label>
                    <input type="text" name="GL_name" required
                           placeholder="e.g. Cash and Cash Equivalents"
                           class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Range Start</label>
                    <input type="number" name="rang_start" id="inp_rang_start" readonly
                           class="w-full border border-gray-200 rounded px-4 py-2 text-sm bg-yellow-50 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Range End</label>
                    <input type="number" name="rang_end" id="inp_rang_end" readonly
                           class="w-full border border-gray-200 rounded px-4 py-2 text-sm bg-yellow-50 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">GL Code</label>
                    <input type="number" name="GlCode" id="inp_glcode" readonly
                           class="w-full border border-gray-200 rounded px-4 py-2 text-sm bg-yellow-50 font-mono font-bold text-red-700">
                    <p class="text-xs text-gray-400 mt-1">Auto-set to range start value</p>
                </div>
            </div>

            <div class="flex gap-3 items-center flex-wrap">
                <button type="button" onclick="loadNextRange()"
                        id="loadRangeBtn"
                        class="px-4 py-2 bg-yellow-500 text-white rounded text-sm font-medium hover:bg-yellow-600 transition">
                    <i class="fas fa-magic mr-1"></i>Auto-fill Next Range
                </button>
                <button type="submit" id="submitBtn" disabled
                        class="px-5 py-2 bg-red-600 text-white rounded text-sm font-medium hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed transition">
                    <i class="fas fa-save mr-1"></i>Save GL
                </button>
                <span id="rangeHint" class="text-xs text-red-500 italic"></span>
            </div>
        </form>
    </div>
    @endif

    {{-- GL List --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-red-600">
                <tr>
                    @foreach(['#','GL Code','GL Name','Range Start','Range End','Status','→ GSL'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($glList as $i => $gl)
                <tr class="hover:bg-yellow-50">
                    <td class="px-4 py-3 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 font-mono font-bold text-red-700">{{ $gl->GlCode }}</td>
                    <td class="px-4 py-3 font-medium">{{ $gl->GL_name }}</td>
                    <td class="px-4 py-3 font-mono text-gray-600">{{ number_format($gl->rang_start) }}</td>
                    <td class="px-4 py-3 font-mono text-gray-600">{{ number_format($gl->rang_end) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $gl->gl_status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $gl->gl_status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('accounts.add-gsl', ['GL_id' => $gl->GL_id]) }}"
                           class="px-3 py-1 bg-red-100 text-purple-700 rounded text-xs hover:bg-red-100 transition">
                            <i class="fas fa-chevron-right mr-1"></i>View / Add GSL
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        @if($filterMaId)
                            No GL entries yet under this account. Click <strong>Auto-fill Next Range</strong> above to add one.
                        @else
                            No GL entries found. Select a Main Account above to begin.
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
function loadNextRange() {
    const maId = {{ $filterMaId ?? 'null' }};
    if (!maId) return;

    const btn = document.getElementById('loadRangeBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Loading...';
    btn.disabled  = true;

    fetch(`{{ route('accounts.add-gl') }}?get_next_range=${maId}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }

        document.getElementById('inp_rang_start').value = data.rang_start;
        document.getElementById('inp_rang_end').value   = data.rang_end;
        document.getElementById('inp_glcode').value     = data.rang_start;
        document.getElementById('submitBtn').disabled   = false;
        document.getElementById('rangeHint').textContent =
            `Next available range: ${Number(data.rang_start).toLocaleString()} – ${Number(data.rang_end).toLocaleString()}`;

        btn.innerHTML = '<i class="fas fa-check mr-1"></i>Range Loaded';
        btn.disabled  = false;
    })
    .catch(() => {
        alert('Could not load next range. Please try again.');
        btn.innerHTML = '<i class="fas fa-magic mr-1"></i>Auto-fill Next Range';
        btn.disabled  = false;
    });
}

// Auto-load when page opens with a main account already selected
@if($filterMaId)
document.addEventListener('DOMContentLoaded', () => loadNextRange());
@endif
</script>
@endpush
@endsection
