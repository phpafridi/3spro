@extends('layouts.master')
@section('title', 'CRM — Follow-Up Reminder')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-phone-alt text-green-600 mr-2"></i>Follow-Up Reminder
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Recently closed jobcards from the last 60 days. Call customers to check in on their service experience.
            </p>
        </div>
        <div class="flex gap-2">
            <button onclick="showTab('all')"
                    id="tab-all"
                    class="tab-btn px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white transition-colors">
                All Recent
                <span class="ml-1 px-1.5 py-0.5 bg-white/30 text-white text-xs rounded-full">{{ $allJobs->count() }}</span>
            </button>
            <button onclick="showTab('consumable')"
                    id="tab-consumable"
                    class="tab-btn px-4 py-2 text-sm font-medium rounded-md bg-gray-200 text-gray-700 hover:bg-orange-500 hover:text-white transition-colors">
                <i class="fas fa-oil-can mr-1"></i>Had Consumable
                <span class="ml-1 px-1.5 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full">{{ $consumableJobs->count() }}</span>
            </button>
        </div>
    </div>

    {{-- Reminder tip banner --}}
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-start gap-3">
        <i class="fas fa-bell text-amber-500 mt-0.5 text-lg"></i>
        <div>
            <p class="text-sm font-semibold text-amber-800">Call Reminder Tip</p>
            <p class="text-sm text-amber-700 mt-0.5">
                Cars marked <span class="font-semibold text-orange-600">Consumable Used</span> are priority calls — the customer had oil, filters, or fluids replaced and may need a quick checkup soon.
                Use the customer's mobile number to follow up.
            </p>
        </div>
    </div>

    {{-- ALL RECENT JOBS TABLE --}}
    <div id="pane-all">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">All Recent Completed Jobs
                    <span class="ml-2 text-sm font-normal text-gray-500">(last 60 days)</span>
                </h3>
                <span class="text-xs text-gray-400">{{ $allJobs->count() }} records</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">RO#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Registration</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mobile</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SA</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Closed</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Consumable</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($allJobs as $job)
                        @php $daysAgo = \Carbon\Carbon::parse($job->Open_date_time)->diffInDays(now()); @endphp
                        <tr class="hover:bg-gray-50 {{ $job->had_consumable ? 'border-l-4 border-orange-400' : '' }}">
                            <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $job->Jobc_id }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-blue-700">{{ $job->Registration ?: '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $job->Make }} {{ $job->Variant }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 font-medium">{{ $job->Customer_name }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($job->mobile)
                                <a href="tel:{{ $job->mobile }}"
                                   class="text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                                    <i class="fas fa-phone text-xs"></i> {{ $job->mobile }}
                                </a>
                                @else
                                <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $job->SA }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($job->Open_date_time)->format('d M Y') }}
                                <span class="block text-xs text-gray-400">{{ $daysAgo }}d ago</span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($job->had_consumable)
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">
                                        <i class="fas fa-oil-can mr-1"></i>{{ $job->consumable_count }} item(s)
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs">None</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                                <i class="fas fa-check-circle text-green-400 text-2xl mb-2 block"></i>
                                No completed jobs in the last 60 days.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CONSUMABLE JOBS TABLE --}}
    <div id="pane-consumable" class="hidden">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-orange-100 bg-orange-50 flex items-center justify-between">
                <h3 class="font-semibold text-orange-800">
                    <i class="fas fa-oil-can mr-2"></i>Jobs Where Consumables Were Used
                    <span class="ml-2 text-sm font-normal text-orange-600">— Priority call-back list</span>
                </h3>
                <span class="text-xs text-orange-500">{{ $consumableJobs->count() }} records</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-orange-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-orange-600 uppercase tracking-wider">RO#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-orange-600 uppercase tracking-wider">Registration</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-orange-600 uppercase tracking-wider">Vehicle</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-orange-600 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-orange-600 uppercase tracking-wider">Mobile</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-orange-600 uppercase tracking-wider">SA</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-orange-600 uppercase tracking-wider">Closed</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-orange-600 uppercase tracking-wider">Consumables</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($consumableJobs as $job)
                        @php $daysAgo = \Carbon\Carbon::parse($job->Open_date_time)->diffInDays(now()); @endphp
                        <tr class="hover:bg-orange-50 border-l-4 border-orange-400">
                            <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $job->Jobc_id }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-blue-700">{{ $job->Registration ?: '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Make }} {{ $job->Variant }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 font-medium">{{ $job->Customer_name }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($job->mobile)
                                <a href="tel:{{ $job->mobile }}"
                                   class="text-green-600 hover:text-green-800 font-semibold flex items-center gap-1">
                                    <i class="fas fa-phone text-xs"></i> {{ $job->mobile }}
                                </a>
                                @else
                                <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $job->SA }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($job->Open_date_time)->format('d M Y') }}
                                <span class="block text-xs {{ $daysAgo <= 7 ? 'text-red-500 font-semibold' : 'text-gray-400' }}">
                                    {{ $daysAgo }}d ago
                                    @if($daysAgo <= 7) — Call Now! @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">
                                    <i class="fas fa-oil-can mr-1"></i>{{ $job->consumable_count }} item(s)
                                </span>
                                <span class="block text-xs text-gray-500 mt-0.5">
                                    Total: {{ number_format($job->consumable_total, 0) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                                <i class="fas fa-check-circle text-green-400 text-2xl mb-2 block"></i>
                                No jobs with consumables in the last 60 days.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function showTab(tab) {
    document.getElementById('pane-all').classList.add('hidden');
    document.getElementById('pane-consumable').classList.add('hidden');
    document.getElementById('tab-all').className = 'tab-btn px-4 py-2 text-sm font-medium rounded-md bg-gray-200 text-gray-700 hover:bg-blue-600 hover:text-white transition-colors';
    document.getElementById('tab-consumable').className = 'tab-btn px-4 py-2 text-sm font-medium rounded-md bg-gray-200 text-gray-700 hover:bg-orange-500 hover:text-white transition-colors';

    if (tab === 'all') {
        document.getElementById('pane-all').classList.remove('hidden');
        document.getElementById('tab-all').className = 'tab-btn px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white transition-colors';
    } else {
        document.getElementById('pane-consumable').classList.remove('hidden');
        document.getElementById('tab-consumable').className = 'tab-btn px-4 py-2 text-sm font-medium rounded-md bg-orange-500 text-white transition-colors';
    }
}
</script>
@endpush
