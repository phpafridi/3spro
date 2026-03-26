@extends('layouts.master')
@section('title', 'CRM — Follow-Up Calls')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-phone-alt text-green-600 mr-2"></i>CRM Follow-Up Calls
            </h2>
            <p class="text-sm text-gray-500 mt-0.5">Log FFS, PSFU, ASFU, CSF &amp; CFU calls. Track remarks, outcomes and next follow-up dates.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button onclick="showTab('all')" id="tab-all"
                    class="tab-btn px-3 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white transition-colors">
                All Jobs <span class="ml-1 px-1.5 py-0.5 bg-white/30 text-white text-xs rounded-full">{{ $allJobs->count() }}</span>
            </button>
            <button onclick="showTab('consumable')" id="tab-consumable"
                    class="tab-btn px-3 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-orange-500 hover:text-white transition-colors">
                <i class="fas fa-oil-can mr-1"></i>Consumable
                <span class="ml-1 px-1.5 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full">{{ $consumableJobs->count() }}</span>
            </button>
            <button onclick="showTab('due')" id="tab-due"
                    class="tab-btn px-3 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-red-500 hover:text-white transition-colors">
                <i class="fas fa-calendar-exclamation mr-1"></i>Due Today
                <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-700 text-xs rounded-full">{{ $dueToday->count() }}</span>
            </button>
            <button onclick="showTab('history')" id="tab-history"
                    class="tab-btn px-3 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-white transition-colors">
                <i class="fas fa-history mr-1"></i>Call History
            </button>
        </div>
    </div>

    {{-- Call-type legend --}}
    <div class="flex flex-wrap gap-2 text-xs">
        <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold">FFS — First Follow-up Service</span>
        <span class="px-2.5 py-1 bg-indigo-100 text-indigo-800 rounded-full font-semibold">PSFU — Post Service Follow Up</span>
        <span class="px-2.5 py-1 bg-teal-100 text-teal-800 rounded-full font-semibold">ASFU — After Sales Follow Up</span>
        <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full font-semibold">CSF — Customer Satisfaction Follow-up</span>
        <span class="px-2.5 py-1 bg-pink-100 text-pink-800 rounded-full font-semibold">CFU — Complaint Follow Up</span>
    </div>

    {{-- Flash --}}
    @if(session('crm_success'))
        <div class="p-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('crm_success') }}
        </div>
    @endif

    {{-- ALL JOBS --}}
    <div id="pane-all">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">All Recent Completed Jobs <span class="text-sm font-normal text-gray-400">(last 60 days)</span></h3>
                <span class="text-xs text-gray-400">{{ $allJobs->count() }} records</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">RO#</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Reg / Vehicle</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Mobile</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">SA</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Closed</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Last Call</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Next F/U</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($allJobs as $job)
                        @php
                            $daysAgo  = (int) \Carbon\Carbon::parse($job->Open_date_time)->diffInDays(now());
                            $lastCall = $callLogs->get($job->Jobc_id)?->first();
                            $nextFU   = $lastCall?->next_followup_date;
                            $isOverdue = $nextFU && \Carbon\Carbon::parse($nextFU)->isPast();
                        @endphp
                        <tr class="hover:bg-gray-50 {{ $job->had_consumable ? 'border-l-4 border-orange-400' : '' }}">
                            <td class="px-3 py-3 font-bold text-gray-900">#{{ $job->Jobc_id }}</td>
                            <td class="px-3 py-3">
                                <span class="font-semibold text-blue-700">{{ $job->Registration ?: '—' }}</span>
                                <span class="block text-xs text-gray-500">{{ $job->Make }} {{ $job->Variant }}</span>
                            </td>
                            <td class="px-3 py-3 font-medium text-gray-800">{{ $job->Customer_name }}</td>
                            <td class="px-3 py-3">
                                @if($job->mobile)
                                    <a href="tel:{{ $job->mobile }}" class="text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                                        <i class="fas fa-phone text-xs"></i> {{ $job->mobile }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-gray-500">{{ $job->SA }}</td>
                            <td class="px-3 py-3 text-gray-500">
                                {{ \Carbon\Carbon::parse($job->Open_date_time)->format('d M Y') }}
                                <span class="block text-xs text-gray-400">{{ $daysAgo }}d ago</span>
                            </td>
                            <td class="px-3 py-3">
                                @if($lastCall)
                                    <span class="inline-block px-2 py-0.5 text-xs font-bold rounded-full
                                        {{ $lastCall->call_type === 'FFS'  ? 'bg-blue-100 text-blue-800'   : '' }}
                                        {{ $lastCall->call_type === 'PSFU' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ $lastCall->call_type === 'ASFU' ? 'bg-teal-100 text-teal-800'   : '' }}
                                        {{ $lastCall->call_type === 'CSF'  ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $lastCall->call_type === 'CFU'  ? 'bg-pink-100 text-pink-800'   : '' }}
                                    ">{{ $lastCall->call_type }}</span>
                                    <span class="block text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($lastCall->called_at)->format('d M') }}</span>
                                    <span class="block text-xs text-gray-500 truncate max-w-28" title="{{ $lastCall->remarks }}">{{ Str::limit($lastCall->remarks, 25) }}</span>
                                @else
                                    <span class="text-xs text-gray-300">No calls yet</span>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                @if($nextFU)
                                    <span class="text-xs font-semibold {{ $isOverdue ? 'text-red-600' : 'text-green-700' }}">
                                        <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($nextFU)->format('d M Y') }}
                                        @if($isOverdue) <span class="block text-red-500">Overdue!</span> @endif
                                    </span>
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center">
                                <button onclick="openCallModal({{ $job->Jobc_id }}, '{{ addslashes($job->Customer_name) }}', '{{ $job->mobile }}', '{{ $job->Registration }}')"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition">
                                    <i class="fas fa-phone-alt"></i> Log Call
                                </button>
                                @if($callLogs->get($job->Jobc_id)?->count() > 0)
                                <button onclick="viewHistory({{ $job->Jobc_id }})"
                                        class="mt-1 inline-flex items-center gap-1 px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium rounded-lg transition">
                                    <i class="fas fa-history"></i> {{ $callLogs->get($job->Jobc_id)->count() }} log(s)
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="px-6 py-10 text-center text-gray-400">No completed jobs in the last 60 days.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CONSUMABLE JOBS --}}
    <div id="pane-consumable" class="hidden">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-orange-100 bg-orange-50 flex items-center justify-between">
                <h3 class="font-semibold text-orange-800"><i class="fas fa-oil-can mr-2"></i>Jobs With Consumables — Priority Call List</h3>
                <span class="text-xs text-orange-500">{{ $consumableJobs->count() }} records</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-orange-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-orange-600 uppercase">RO#</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-orange-600 uppercase">Reg / Vehicle</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-orange-600 uppercase">Customer</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-orange-600 uppercase">Mobile</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-orange-600 uppercase">Consumables</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-orange-600 uppercase">Closed</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-orange-600 uppercase">Last Call</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-orange-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($consumableJobs as $job)
                        @php
                            $daysAgo  = (int) \Carbon\Carbon::parse($job->Open_date_time)->diffInDays(now());
                            $lastCall = $callLogs->get($job->Jobc_id)?->first();
                        @endphp
                        <tr class="hover:bg-orange-50 border-l-4 border-orange-400">
                            <td class="px-3 py-3 font-bold text-gray-900">#{{ $job->Jobc_id }}</td>
                            <td class="px-3 py-3">
                                <span class="font-semibold text-blue-700">{{ $job->Registration ?: '—' }}</span>
                                <span class="block text-xs text-gray-500">{{ $job->Make }} {{ $job->Variant }}</span>
                            </td>
                            <td class="px-3 py-3 font-medium text-gray-800">{{ $job->Customer_name }}</td>
                            <td class="px-3 py-3">
                                @if($job->mobile)
                                    <a href="tel:{{ $job->mobile }}" class="text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                                        <i class="fas fa-phone text-xs"></i> {{ $job->mobile }}
                                    </a>
                                @else <span class="text-gray-400">—</span> @endif
                            </td>
                            <td class="px-3 py-3">
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">
                                    <i class="fas fa-oil-can mr-1"></i>{{ $job->consumable_count }} item(s)
                                </span>
                                <span class="block text-xs text-gray-500 mt-0.5">Total: {{ number_format($job->consumable_total, 0) }}</span>
                            </td>
                            <td class="px-3 py-3 text-gray-500">
                                {{ \Carbon\Carbon::parse($job->Open_date_time)->format('d M Y') }}
                                <span class="block text-xs {{ $daysAgo <= 7 ? 'text-red-500 font-semibold' : 'text-gray-400' }}">
                                    {{ $daysAgo }}d ago @if($daysAgo <= 7) — Call Now! @endif
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                @if($lastCall)
                                    <span class="inline-block px-2 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-800">{{ $lastCall->call_type }}</span>
                                    <span class="block text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($lastCall->called_at)->format('d M') }}</span>
                                @else
                                    <span class="text-xs text-gray-300">Not called</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center">
                                <button onclick="openCallModal({{ $job->Jobc_id }}, '{{ addslashes($job->Customer_name) }}', '{{ $job->mobile }}', '{{ $job->Registration }}')"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded-lg transition">
                                    <i class="fas fa-phone-alt"></i> Log Call
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-6 py-10 text-center text-gray-400">No jobs with consumables.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- DUE TODAY --}}
    <div id="pane-due" class="hidden">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-red-100 bg-red-50 flex items-center justify-between">
                <h3 class="font-semibold text-red-800"><i class="fas fa-calendar-day mr-2"></i>Follow-Ups Due Today / Overdue</h3>
                <span class="text-xs text-red-500">{{ $dueToday->count() }} records</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-red-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-red-600 uppercase">RO#</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-red-600 uppercase">Customer</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-red-600 uppercase">Mobile</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-red-600 uppercase">Call Type</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-red-600 uppercase">Due Date</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-red-600 uppercase">Last Remarks</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-red-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($dueToday as $log)
                        @php $isOverdue = \Carbon\Carbon::parse($log->next_followup_date)->isPast() && !\Carbon\Carbon::parse($log->next_followup_date)->isToday(); @endphp
                        <tr class="hover:bg-red-50 border-l-4 {{ $isOverdue ? 'border-red-600' : 'border-yellow-400' }}">
                            <td class="px-3 py-3 font-bold text-gray-900">#{{ $log->jobc_id }}</td>
                            <td class="px-3 py-3 font-medium text-gray-800">{{ $log->customer_name }}</td>
                            <td class="px-3 py-3">
                                @if($log->mobile)
                                    <a href="tel:{{ $log->mobile }}" class="text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                                        <i class="fas fa-phone text-xs"></i> {{ $log->mobile }}
                                    </a>
                                @else <span class="text-gray-400">—</span> @endif
                            </td>
                            <td class="px-3 py-3">
                                <span class="px-2 py-1 text-xs font-bold rounded-full
                                    {{ $log->call_type === 'FFS'  ? 'bg-blue-100 text-blue-800'   : '' }}
                                    {{ $log->call_type === 'PSFU' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $log->call_type === 'ASFU' ? 'bg-teal-100 text-teal-800'   : '' }}
                                    {{ $log->call_type === 'CSF'  ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $log->call_type === 'CFU'  ? 'bg-pink-100 text-pink-800'   : '' }}
                                ">{{ $log->call_type }}</span>
                            </td>
                            <td class="px-3 py-3 font-semibold {{ $isOverdue ? 'text-red-600' : 'text-yellow-700' }}">
                                {{ \Carbon\Carbon::parse($log->next_followup_date)->format('d M Y') }}
                                @if($isOverdue)<span class="block text-xs text-red-500">Overdue</span>@else<span class="block text-xs text-yellow-600">Today</span>@endif
                            </td>
                            <td class="px-3 py-3 text-xs text-gray-600 max-w-xs truncate" title="{{ $log->remarks }}">{{ $log->remarks ?: '—' }}</td>
                            <td class="px-3 py-3 text-center">
                                <button onclick="openCallModal({{ $log->jobc_id }}, '{{ addslashes($log->customer_name) }}', '{{ $log->mobile }}', '{{ $log->registration }}')"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition">
                                    <i class="fas fa-phone-alt"></i> Call Now
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400"><i class="fas fa-check-circle text-green-400 text-2xl mb-2 block"></i>No follow-ups due today.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CALL HISTORY --}}
    <div id="pane-history" class="hidden">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800"><i class="fas fa-history mr-2 text-gray-500"></i>All Call Logs</h3>
                <span class="text-xs text-gray-400">{{ $recentLogs->count() }} recent entries</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date & Time</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">RO#</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Mobile</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Remarks</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Next F/U</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">By</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($recentLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 text-gray-500">
                                {{ \Carbon\Carbon::parse($log->called_at)->format('d M Y') }}
                                <span class="block text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->called_at)->format('H:i') }}</span>
                            </td>
                            <td class="px-3 py-3 font-bold text-gray-900">#{{ $log->jobc_id }}</td>
                            <td class="px-3 py-3 font-medium text-gray-800">{{ $log->customer_name }}</td>
                            <td class="px-3 py-3 text-gray-600">{{ $log->mobile ?: '—' }}</td>
                            <td class="px-3 py-3">
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full
                                    {{ $log->call_type === 'FFS'  ? 'bg-blue-100 text-blue-800'    : '' }}
                                    {{ $log->call_type === 'PSFU' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $log->call_type === 'ASFU' ? 'bg-teal-100 text-teal-800'    : '' }}
                                    {{ $log->call_type === 'CSF'  ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $log->call_type === 'CFU'  ? 'bg-pink-100 text-pink-800'    : '' }}
                                ">{{ $log->call_type }}</span>
                            </td>
                            <td class="px-3 py-3">
                                @php $statusColors = ['Contacted'=>'green','Not Reachable'=>'red','Callback Requested'=>'yellow','Voicemail'=>'gray','Wrong Number'=>'orange']; @endphp
                                <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $statusColors[$log->call_status] ?? 'gray' }}-100 text-{{ $statusColors[$log->call_status] ?? 'gray' }}-800">
                                    {{ $log->call_status }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-xs text-gray-600 max-w-xs" title="{{ $log->remarks }}">{{ Str::limit($log->remarks, 60) ?: '—' }}</td>
                            <td class="px-3 py-3 text-xs {{ $log->next_followup_date ? 'text-blue-700 font-semibold' : 'text-gray-300' }}">
                                {{ $log->next_followup_date ? \Carbon\Carbon::parse($log->next_followup_date)->format('d M Y') : '—' }}
                            </td>
                            <td class="px-3 py-3 text-xs text-gray-500">{{ $log->called_by ?: '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="px-6 py-10 text-center text-gray-400">No call logs yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- LOG CALL MODAL --}}
<div id="callModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-phone-alt text-green-600 mr-2"></i>Log Call</h3>
            <button onclick="closeCallModal()" class="text-gray-400 hover:text-gray-700 text-xl"><i class="fas fa-times"></i></button>
        </div>

        <form method="POST" action="{{ route('sales.crm-log') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="jobc_id" id="modal_jobc_id">
            <input type="hidden" name="customer_id" id="modal_customer_id">

            <div class="p-3 bg-blue-50 rounded-lg text-sm">
                <p class="font-semibold text-gray-700" id="modal_customer_display">—</p>
                <p class="text-gray-500" id="modal_mobile_display">—</p>
                <p class="text-gray-400 text-xs" id="modal_reg_display">—</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Call Type <span class="text-red-500">*</span></label>
                    <select name="call_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="FFS">FFS — First Follow-up Service</option>
                        <option value="PSFU">PSFU — Post Service Follow Up</option>
                        <option value="ASFU">ASFU — After Sales Follow Up</option>
                        <option value="CSF">CSF — Customer Satisfaction Follow-up</option>
                        <option value="CFU">CFU — Complaint Follow Up</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Call Status <span class="text-red-500">*</span></label>
                    <select name="call_status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Contacted">Contacted</option>
                        <option value="Not Reachable">Not Reachable</option>
                        <option value="Callback Requested">Callback Requested</option>
                        <option value="Voicemail">Voicemail</option>
                        <option value="Wrong Number">Wrong Number</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Call Date &amp; Time <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="called_at"
                       value="{{ now()->format('Y-m-d\TH:i') }}"
                       required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks / What was discussed</label>
                <textarea name="remarks" rows="3" placeholder="e.g. Customer satisfied, oil change reminder given, booked next visit..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Next Follow-Up Date</label>
                <input type="date" name="next_followup_date"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Leave blank if no follow-up needed">
                <p class="text-xs text-gray-400 mt-1">Leave blank if no further follow-up is required.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Save Call Log
                </button>
                <button type="button" onclick="closeCallModal()"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- HISTORY MODAL (per RO) --}}
<div id="historyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[80vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-history text-gray-500 mr-2"></i>Call History — RO <span id="historyRo"></span></h3>
            <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-700 text-xl"><i class="fas fa-times"></i></button>
        </div>
        <div id="historyContent" class="overflow-y-auto p-6 space-y-3 text-sm"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// All call logs indexed by jobc_id for JS access
const allCallLogs = @json($callLogsAll);

function showTab(tab) {
    ['all','consumable','due','history'].forEach(t => {
        document.getElementById('pane-' + t)?.classList.add('hidden');
        const btn = document.getElementById('tab-' + t);
        if (btn) btn.className = 'tab-btn px-3 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-600 hover:text-white transition-colors';
    });
    document.getElementById('pane-' + tab)?.classList.remove('hidden');
    const activeBtn = document.getElementById('tab-' + tab);
    if (activeBtn) {
        const colors = {all:'bg-blue-600', consumable:'bg-orange-500', due:'bg-red-600', history:'bg-gray-700'};
        activeBtn.className = `tab-btn px-3 py-2 text-sm font-medium rounded-lg ${colors[tab]} text-white transition-colors`;
    }
}

function openCallModal(jobcId, customerName, mobile, registration) {
    document.getElementById('modal_jobc_id').value = jobcId;
    document.getElementById('modal_customer_display').textContent = customerName;
    document.getElementById('modal_mobile_display').textContent = mobile || 'No mobile on record';
    document.getElementById('modal_reg_display').textContent = 'RO#' + jobcId + (registration ? ' | ' + registration : '');
    document.getElementById('callModal').classList.remove('hidden');
}

function closeCallModal() {
    document.getElementById('callModal').classList.add('hidden');
}

function viewHistory(jobcId) {
    document.getElementById('historyRo').textContent = '#' + jobcId;
    const logs = allCallLogs.filter(l => l.jobc_id == jobcId);
    const typeColors = {FFS:'blue', PSFU:'indigo', ASFU:'teal', CSF:'yellow', CFU:'pink'};
    const statusColors = {Contacted:'green', 'Not Reachable':'red', 'Callback Requested':'yellow', Voicemail:'gray', 'Wrong Number':'orange'};

    if (!logs.length) {
        document.getElementById('historyContent').innerHTML = '<p class="text-gray-400 text-center py-6">No call logs for this RO.</p>';
    } else {
        document.getElementById('historyContent').innerHTML = logs.map(l => {
            const tc = typeColors[l.call_type] || 'gray';
            const sc = statusColors[l.call_status] || 'gray';
            const d = new Date(l.called_at);
            const dateStr = d.toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'}) + ' ' + d.toLocaleTimeString('en-GB', {hour:'2-digit', minute:'2-digit'});
            return `<div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="flex gap-2 flex-wrap">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-${tc}-100 text-${tc}-800">${l.call_type}</span>
                        <span class="px-2 py-0.5 text-xs rounded-full bg-${sc}-100 text-${sc}-800">${l.call_status}</span>
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap">${dateStr}</span>
                </div>
                <p class="text-gray-700 text-sm">${l.remarks || '<span class="text-gray-400 italic">No remarks</span>'}</p>
                ${l.next_followup_date ? `<p class="text-xs text-blue-600 mt-1.5 font-semibold"><i class="fas fa-calendar mr-1"></i>Next Follow-up: ${l.next_followup_date}</p>` : ''}
                <p class="text-xs text-gray-400 mt-1">By: ${l.called_by || '—'}</p>
            </div>`;
        }).join('');
    }
    document.getElementById('historyModal').classList.remove('hidden');
}

function closeHistoryModal() {
    document.getElementById('historyModal').classList.add('hidden');
}

// Close modal on backdrop click
document.getElementById('callModal').addEventListener('click', function(e) {
    if (e.target === this) closeCallModal();
});
document.getElementById('historyModal').addEventListener('click', function(e) {
    if (e.target === this) closeHistoryModal();
});
</script>
@endpush
