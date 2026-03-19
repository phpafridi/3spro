@extends('layouts.master')
@section('title', 'Warranty Claims')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
@endif

{{-- Tab navigation --}}
<div class="mb-4 border-b border-gray-200">
    <nav class="flex gap-4">
        <button onclick="showTab('pending')"  id="tab-pending"
                class="pb-2 text-sm font-medium border-b-2 border-red-600 text-red-600">
            Pending Claims
        </button>
        <button onclick="showTab('claimed')"  id="tab-claimed"
                class="pb-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
            Claimed / Approved
        </button>
    </nav>
</div>

{{-- Pending Jobs tab --}}
<div id="panel-pending">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Warranty Pending Jobs</h2>
        <input type="text" id="search1" placeholder="Search..."
               class="mb-4 border border-gray-300 rounded-md px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="table1">
                <thead class="bg-red-600">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">#</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Jobcard#</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Customer</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Frame#</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Total</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Date</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Claim</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendingJobs as $i => $job)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-sm text-gray-500">{{ $i+1 }}</td>
                        <td class="px-3 py-2 text-sm font-bold">{{ $job->Jobc_id }}</td>
                        <td class="px-3 py-2 text-sm">{{ $job->Customer_name }}</td>
                        <td class="px-3 py-2 text-xs font-mono">{{ $job->Frame_no }}</td>
                        <td class="px-3 py-2 text-sm">{{ number_format($job->Total ?? 0, 0) }}</td>
                        <td class="px-3 py-2 text-xs text-gray-500">{{ $job->bookingtime }}</td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('jobcard.warranty') }}" class="flex gap-1">
                                @csrf
                                <input type="hidden" name="Labor_id" value="{{ $job->Jobc_id }}">
                                <input type="text" name="warrantyclaim" required placeholder="WC No."
                                       class="border border-gray-300 rounded px-2 py-1 text-xs w-24">
                                <button type="submit"
                                        class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded">
                                    Claim
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No pending warranty jobs.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Claimed / Approved tab --}}
<div id="panel-claimed" class="hidden">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Claimed Warranties</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">RO#</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">WC No.</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Status</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Claim Date</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Remarks</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($claimedWarranties as $i => $w)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-sm text-gray-500">{{ $i+1 }}</td>
                        <td class="px-3 py-2 text-sm font-bold">{{ $w->jobc_id }}</td>
                        <td class="px-3 py-2 text-sm">{{ $w->wc_no }}</td>
                        <td class="px-3 py-2 text-sm">
                            @if($w->status === 'Approved')
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs">Approved</span>
                            @elseif($w->status === 'Denied')
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs">Denied</span>
                            @else
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs">Claimed</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-xs text-gray-500">
                            {{ $w->claim_date ? \Carbon\Carbon::parse($w->claim_date)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-3 py-2 text-xs text-gray-600">{{ $w->remarks ?? '—' }}</td>
                        <td class="px-3 py-2">
                            @if($w->status === 'Claimed')
                            <div class="flex gap-1">
                                {{-- Approve --}}
                                <form method="POST" action="{{ route('jobcard.warranty') }}">
                                    @csrf
                                    <input type="hidden" name="approved" value="{{ $w->w_id }}">
                                    <button type="submit"
                                            class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded">
                                        Approve
                                    </button>
                                </form>
                                {{-- Deny --}}
                                <form method="POST" action="{{ route('jobcard.warranty') }}" class="flex gap-1">
                                    @csrf
                                    <input type="hidden" name="w_id" value="{{ $w->w_id }}">
                                    <input type="text" name="reason" placeholder="Reason..."
                                           class="border border-gray-300 rounded px-2 py-1 text-xs w-24" required>
                                    <button type="submit"
                                            class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded">
                                        Deny
                                    </button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No warranty claims yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showTab(name) {
    ['pending','claimed'].forEach(t => {
        document.getElementById('panel-'+t).classList.toggle('hidden', t !== name);
        const btn = document.getElementById('tab-'+t);
        btn.classList.toggle('border-red-600', t === name);
        btn.classList.toggle('text-red-600', t === name);
        btn.classList.toggle('border-transparent', t !== name);
        btn.classList.toggle('text-gray-500', t !== name);
    });
}
document.getElementById('search1').addEventListener('keyup', function () {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#table1 tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>
@endpush
@endsection
