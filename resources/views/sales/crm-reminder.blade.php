@extends('layouts.master')
@section('title', 'CRM \u2014 Follow-Up Calls')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection

@section('content')
<div class="space-y-4">

{{-- HEADER + DATE RANGE FILTER --}}
<div class="flex flex-wrap items-start justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold text-gray-800">
            <i class="fas fa-phone-alt text-green-600 mr-2"></i>CRM &mdash; Follow-Up Calls
        </h2>
        <p class="text-sm text-gray-500 mt-0.5">Log FFS, PSFU, ASFU, CSF, CFU &amp; NVD calls. Track outcomes and next follow-up dates.</p>
    </div>
    <form method="GET" class="flex flex-wrap items-center gap-2 no-print">
        <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-lg px-3 py-2 shadow-sm">
            <i class="fas fa-calendar text-gray-400 text-xs"></i>
            <input type="date" name="date_from" value="{{ $dateFrom }}"
                   class="text-sm border-0 outline-none text-gray-700 w-32">
            <span class="text-gray-300 text-xs mx-1">&rarr;</span>
            <input type="date" name="date_to" value="{{ $dateTo }}"
                   class="text-sm border-0 outline-none text-gray-700 w-32">
        </div>
        <button type="submit" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
            <i class="fas fa-filter mr-1"></i>Filter
        </button>
        <a href="{{ route('sales.crm-reminder') }}"
           class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium rounded-lg transition">Reset</a>
    </form>
</div>

{{-- TABS --}}
<div class="flex flex-wrap gap-2 no-print">
    <button onclick="showTab('all')" id="tab-all"
            class="tab-btn px-3 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white transition-colors">
        <i class="fas fa-list mr-1"></i>All Jobs
        <span class="ml-1 px-1.5 py-0.5 bg-white/30 text-white text-xs rounded-full">{{ $allJobs->count() }}</span>
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
    <button onclick="showTab('delivered')" id="tab-delivered"
            class="tab-btn px-3 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-emerald-600 hover:text-white transition-colors">
        <i class="fas fa-car mr-1"></i>New Cars Delivered
        <span class="ml-1 px-1.5 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded-full">{{ $deliveredOrders->count() }}</span>
    </button>
    <button onclick="showTab('history')" id="tab-history"
            class="tab-btn px-3 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-700 hover:text-white transition-colors">
        <i class="fas fa-history mr-1"></i>Call History
    </button>
</div>

{{-- CALL TYPE LEGEND --}}
<div class="flex flex-wrap gap-2 text-xs no-print">
    <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold">FFS &mdash; First Follow-up Service</span>
    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-800 rounded-full font-semibold">PSFU &mdash; Post Service Follow Up</span>
    <span class="px-2.5 py-1 bg-teal-100 text-teal-800 rounded-full font-semibold">ASFU &mdash; After Sales Follow Up</span>
    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full font-semibold">CSF &mdash; Customer Satisfaction</span>
    <span class="px-2.5 py-1 bg-pink-100 text-pink-800 rounded-full font-semibold">CFU &mdash; Complaint Follow Up</span>
    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-semibold">NVD &mdash; New Vehicle Delivery F/U</span>
</div>

@if(session('crm_success'))
<div class="p-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm flex items-center gap-2">
    <i class="fas fa-check-circle"></i> {{ session('crm_success') }}
</div>
@endif

{{-- ============================================================
     ALL JOBS
============================================================ --}}
<div id="pane-all">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-2">
            <h3 class="font-semibold text-gray-800">All Completed Jobs
                <span class="text-sm font-normal text-gray-400">({{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }})</span>
            </h3>
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
                        <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase no-print">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($allJobs as $job)

                    @php
                        $daysAgo      = (int) \Carbon\Carbon::parse($job->Open_date_time)->diffInDays(now());
                        $lastCall     = $callLogs->get($job->Jobc_id)?->first();
                        $nextFU       = $lastCall?->next_followup_date;
                        $isOverdue    = $nextFU && \Carbon\Carbon::parse($nextFU)->isPast();
                        $callDaysAgo  = $lastCall ? (int)\Carbon\Carbon::parse($lastCall->called_at)->diffInDays(now()) : null;
                    @endphp
                    <tr class="hover:bg-gray-50 {{ $job->had_consumable ? 'border-l-4 border-orange-400' : '' }}">
                        <td class="px-3 py-3 font-bold text-gray-900">#{{ $job->Jobc_id }}</td>
                        <td class="px-3 py-3">
                            <span class="font-semibold text-blue-700">{{ $job->Registration ?: '&mdash;' }}</span>
                            <span class="block text-xs text-gray-500">{{ $job->Make }} {{ $job->Variant }}</span>
                        </td>
                        <td class="px-3 py-3 font-medium text-gray-800">{{ $job->Customer_name }}</td>
                        <td class="px-3 py-3">
                            @if($job->mobile)
                                <a href="tel:{{ $job->mobile }}" class="text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                                    <i class="fas fa-phone text-xs"></i> {{ $job->mobile }}
                                </a>
                                @if($callDaysAgo !== null)
                                <span class="inline-block mt-0.5 px-1.5 py-0.5 text-xs rounded-full font-semibold
                                    {{ $callDaysAgo == 0 ? 'bg-green-100 text-green-700' : ($callDaysAgo <= 3 ? 'bg-blue-100 text-blue-700' : ($callDaysAgo <= 7 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                                    <i class="fas fa-clock text-xs mr-0.5"></i>
                                    {{ $callDaysAgo == 0 ? 'Today' : $callDaysAgo.'d ago' }}
                                </span>
                                @endif
                            @else
                                <span class="text-gray-400">&mdash;</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-gray-500 text-xs">{{ $job->SA }}</td>
                        <td class="px-3 py-3 text-gray-500">
                            {{ \Carbon\Carbon::parse($job->Open_date_time)->format('d M Y') }}
                            <span class="block text-xs text-gray-400">{{ $daysAgo }}d ago</span>
                            @if($job->had_consumable)
                            <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full">
                                <i class="fas fa-oil-can mr-0.5"></i>{{ $job->consumable_count }} cons.
                            </span>
                            @endif
                        </td>
                        <td class="px-3 py-3">
                            @if($lastCall)
                                <span class="inline-block px-2 py-0.5 text-xs font-bold rounded-full
                                    {{ $lastCall->call_type==='FFS'  ? 'bg-blue-100 text-blue-800'    : '' }}
                                    {{ $lastCall->call_type==='PSFU' ? 'bg-indigo-100 text-indigo-800': '' }}
                                    {{ $lastCall->call_type==='ASFU' ? 'bg-teal-100 text-teal-800'    : '' }}
                                    {{ $lastCall->call_type==='CSF'  ? 'bg-yellow-100 text-yellow-800': '' }}
                                    {{ $lastCall->call_type==='CFU'  ? 'bg-pink-100 text-pink-800'    : '' }}
                                ">{{ $lastCall->call_type }}</span>
                                <span class="block text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($lastCall->called_at)->format('d M') }}</span>
                                <span class="block text-xs text-gray-500 truncate max-w-28" title="{{ $lastCall->remarks }}">{{ Str::limit($lastCall->remarks,25) }}</span>
                            @else
                                <span class="text-xs text-gray-300">No calls yet</span>
                            @endif
                        </td>
                        <td class="px-3 py-3">
                            @if($nextFU)
                                <span class="text-xs font-semibold {{ $isOverdue ? 'text-red-600' : 'text-green-700' }}">
                                    <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($nextFU)->format('d M Y') }}
                                    @if($isOverdue)<span class="block text-red-500 text-xs">Overdue!</span>@endif
                                </span>
                            @else
                                <span class="text-xs text-gray-300">&mdash;</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-center no-print">
                            <button onclick="openCallModal({{ $job->Jobc_id }},'{{ addslashes($job->Customer_name) }}','{{ $job->mobile }}','{{ $job->Registration }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition">
                                <i class="fas fa-phone-alt"></i> Log
                            </button>
                            @if($callLogs->get($job->Jobc_id)?->count() > 0)
                            <button onclick="viewHistory({{ $job->Jobc_id }})"
                                    class="mt-1 inline-flex items-center gap-1 px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs rounded-lg transition">
                                <i class="fas fa-history"></i> {{ $callLogs->get($job->Jobc_id)->count() }}
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-6 py-10 text-center text-gray-400">No completed jobs in selected date range.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================
     CONSUMABLE JOBS
============================================================ --}}
<div id="pane-consumable" class="hidden">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-orange-100 bg-orange-50 flex items-center justify-between">
            <h3 class="font-semibold text-orange-800"><i class="fas fa-oil-can mr-2"></i>Jobs With Consumables &mdash; Priority Call List</h3>
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
                        <th class="px-3 py-3 text-left text-xs font-semibold text-orange-600 uppercase">Parts Used</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-orange-600 uppercase">Closed</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-orange-600 uppercase">Last Call</th>
                        <th class="px-3 py-3 text-center text-xs font-semibold text-orange-600 uppercase no-print">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($consumableJobs as $job)
                    @php
                        $daysAgo     = (int) \Carbon\Carbon::parse($job->Open_date_time)->diffInDays(now());
                        $lastCall    = $callLogs->get($job->Jobc_id)?->first();
                        $callDaysAgo = $lastCall ? (int)\Carbon\Carbon::parse($lastCall->called_at)->diffInDays(now()) : null;
                    @endphp
                    <tr class="hover:bg-orange-50 border-l-4 border-orange-400">
                        <td class="px-3 py-3 font-bold text-gray-900">#{{ $job->Jobc_id }}</td>
                        <td class="px-3 py-3">
                            <span class="font-semibold text-blue-700">{{ $job->Registration ?: '&mdash;' }}</span>
                            <span class="block text-xs text-gray-500">{{ $job->Make }} {{ $job->Variant }}</span>
                        </td>
                        <td class="px-3 py-3 font-medium text-gray-800">{{ $job->Customer_name }}</td>
                        <td class="px-3 py-3">
                            @if($job->mobile)
                                <a href="tel:{{ $job->mobile }}" class="text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                                    <i class="fas fa-phone text-xs"></i> {{ $job->mobile }}
                                </a>
                                @if($callDaysAgo !== null)
                                <span class="inline-block mt-0.5 px-1.5 py-0.5 text-xs rounded-full font-semibold
                                    {{ $callDaysAgo==0?'bg-green-100 text-green-700':($callDaysAgo<=3?'bg-blue-100 text-blue-700':($callDaysAgo<=7?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700')) }}">
                                    <i class="fas fa-clock text-xs mr-0.5"></i>{{ $callDaysAgo==0?'Today':$callDaysAgo.'d ago' }}
                                </span>
                                @endif
                            @else <span class="text-gray-400">&mdash;</span> @endif
                        </td>
                        <td class="px-3 py-3">
                            <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">
                                <i class="fas fa-oil-can mr-1"></i>{{ $job->consumable_count }} item(s)
                            </span>
                            <span class="block text-xs text-gray-500 mt-0.5">{{ number_format($job->consumable_total,0) }}</span>
                        </td>
                        <td class="px-3 py-3">
                            @if($job->parts_count > 0)
                                <span class="px-2 py-1 bg-cyan-100 text-cyan-700 text-xs rounded-full font-medium">
                                    <i class="fas fa-cog mr-1"></i>{{ $job->parts_count }} part(s)
                                </span>
                                <span class="block text-xs text-gray-500 mt-0.5">{{ number_format($job->parts_total,0) }}</span>
                            @else
                                <span class="text-gray-300 text-xs">&mdash;</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-gray-500">
                            {{ \Carbon\Carbon::parse($job->Open_date_time)->format('d M Y') }}
                            <span class="block text-xs {{ $daysAgo<=7?'text-red-500 font-semibold':'text-gray-400' }}">
                                {{ $daysAgo }}d ago @if($daysAgo<=7) &mdash; Call Now! @endif
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
                        <td class="px-3 py-3 text-center no-print">
                            <button onclick="openCallModal({{ $job->Jobc_id }},'{{ addslashes($job->Customer_name) }}','{{ $job->mobile }}','{{ $job->Registration }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded-lg transition">
                                <i class="fas fa-phone-alt"></i> Log
                            </button>
                            @if($callLogs->get($job->Jobc_id)?->count() > 0)
                            <button onclick="viewHistory({{ $job->Jobc_id }})"
                                    class="mt-1 inline-flex items-center gap-1 px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs rounded-lg transition">
                                <i class="fas fa-history"></i> {{ $callLogs->get($job->Jobc_id)->count() }}
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-6 py-10 text-center text-gray-400">No jobs with consumables.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================
     DUE TODAY
============================================================ --}}
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
                        <th class="px-3 py-3 text-center text-xs font-semibold text-red-600 uppercase no-print">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($dueToday as $log)
                    @php
                        $isOverdue   = \Carbon\Carbon::parse($log->next_followup_date)->isPast() && !\Carbon\Carbon::parse($log->next_followup_date)->isToday();
                        $callDaysAgo = (int)\Carbon\Carbon::parse($log->called_at)->diffInDays(now());
                    @endphp
                    <tr class="hover:bg-red-50 border-l-4 {{ $isOverdue?'border-red-600':'border-yellow-400' }}">
                        <td class="px-3 py-3 font-bold text-gray-900">#{{ $log->jobc_id }}</td>
                        <td class="px-3 py-3 font-medium text-gray-800">{{ $log->customer_name }}</td>
                        <td class="px-3 py-3">
                            @if($log->mobile)
                                <a href="tel:{{ $log->mobile }}" class="text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                                    <i class="fas fa-phone text-xs"></i> {{ $log->mobile }}
                                </a>
                                <span class="inline-block mt-0.5 px-1.5 py-0.5 text-xs rounded-full font-semibold
                                    {{ $callDaysAgo==0?'bg-green-100 text-green-700':($callDaysAgo<=3?'bg-blue-100 text-blue-700':($callDaysAgo<=7?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700')) }}">
                                    <i class="fas fa-clock text-xs mr-0.5"></i>{{ $callDaysAgo==0?'Called Today':'Called '.$callDaysAgo.'d ago' }}
                                </span>
                            @else <span class="text-gray-400">&mdash;</span> @endif
                        </td>
                        <td class="px-3 py-3">
                            <span class="px-2 py-1 text-xs font-bold rounded-full
                                {{ $log->call_type==='FFS' ?'bg-blue-100 text-blue-800'   :'' }}
                                {{ $log->call_type==='PSFU'?'bg-indigo-100 text-indigo-800':'' }}
                                {{ $log->call_type==='ASFU'?'bg-teal-100 text-teal-800'   :'' }}
                                {{ $log->call_type==='CSF' ?'bg-yellow-100 text-yellow-800':'' }}
                                {{ $log->call_type==='CFU' ?'bg-pink-100 text-pink-800'   :'' }}
                            ">{{ $log->call_type }}</span>
                        </td>
                        <td class="px-3 py-3 font-semibold {{ $isOverdue?'text-red-600':'text-yellow-700' }}">
                            {{ \Carbon\Carbon::parse($log->next_followup_date)->format('d M Y') }}
                            @if($isOverdue)<span class="block text-xs text-red-500">Overdue</span>@else<span class="block text-xs text-yellow-600">Today</span>@endif
                        </td>
                        <td class="px-3 py-3 text-xs text-gray-600 max-w-xs truncate" title="{{ $log->remarks }}">{{ $log->remarks?:'&mdash;' }}</td>
                        <td class="px-3 py-3 text-center no-print">
                            <button onclick="openCallModal({{ $log->jobc_id }},'{{ addslashes($log->customer_name) }}','{{ $log->mobile }}','{{ $log->registration }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition">
                                <i class="fas fa-phone-alt"></i> Call Now
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400">
                        <i class="fas fa-check-circle text-green-400 text-2xl mb-2 block"></i>No follow-ups due today.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================
     NEW CARS DELIVERED
============================================================ --}}
<div id="pane-delivered" class="hidden">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-emerald-100 bg-emerald-50 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="font-semibold text-emerald-800"><i class="fas fa-car mr-2"></i>New Cars Delivered</h3>
                <p class="text-xs text-emerald-600 mt-0.5">Delivery orders within the selected date range.</p>
            </div>
            <div class="flex items-center gap-3 no-print">
                <label class="flex items-center gap-1.5 text-xs text-emerald-700 font-medium cursor-pointer">
                    <input type="checkbox" id="highlight_nvd_toggle" checked onchange="toggleNvdHighlight(this)" class="rounded text-emerald-600">
                    Highlight pending NVD calls
                </label>
                <span class="text-xs text-emerald-500">{{ $deliveredOrders->count() }} records</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-emerald-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-emerald-700 uppercase">DO#</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-emerald-700 uppercase">Vehicle</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-emerald-700 uppercase">Customer</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-emerald-700 uppercase">Mobile</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-emerald-700 uppercase">Delivery</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-emerald-700 uppercase">Payment</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-emerald-700 uppercase">DO Status</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-emerald-700 uppercase">NVD Follow-up</th>
                        <th class="px-3 py-3 text-center text-xs font-semibold text-emerald-700 uppercase no-print">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100" id="nvd-tbody">
                    @forelse($deliveredOrders as $do)
                    @php
                        $nvdLog      = $doCallLogs->get($do->id)?->first();
                        $nvdDaysAgo  = $nvdLog ? (int)\Carbon\Carbon::parse($nvdLog->called_at)->diffInDays(now()) : null;
                        $delivDays   = $do->delivery_date ? (int)\Carbon\Carbon::parse($do->delivery_date)->diffInDays(now()) : null;
                        $scMap       = ['Pending'=>'yellow','Approved'=>'blue','Delivered'=>'green','Cancelled'=>'red'];
                        $sc          = $scMap[$do->status] ?? 'gray';
                    @endphp
                    <tr class="hover:bg-emerald-50 transition {{ !$nvdLog ? 'nvd-no-call' : '' }}" data-has-nvd="{{ $nvdLog?'1':'0' }}">
                        <td class="px-3 py-3 font-mono font-semibold text-emerald-700">{{ $do->do_no }}</td>
                        <td class="px-3 py-3">
                            <p class="font-semibold text-gray-800">{{ $do->model }} {{ $do->variant }}</p>
                            <p class="text-xs text-gray-400">{{ $do->color }} &middot; {{ $do->model_year }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $do->vin }}</p>
                        </td>
                        <td class="px-3 py-3 font-medium text-gray-800">{{ $do->customer_name }}</td>
                        <td class="px-3 py-3">
                            @if($do->customer_phone)
                                <a href="tel:{{ $do->customer_phone }}" class="text-green-600 hover:text-green-800 font-medium flex items-center gap-1">
                                    <i class="fas fa-phone text-xs"></i> {{ $do->customer_phone }}
                                </a>
                                @if($nvdDaysAgo !== null)
                                <span class="inline-block mt-0.5 px-1.5 py-0.5 text-xs rounded-full font-semibold
                                    {{ $nvdDaysAgo==0?'bg-green-100 text-green-700':($nvdDaysAgo<=3?'bg-blue-100 text-blue-700':($nvdDaysAgo<=7?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700')) }}">
                                    <i class="fas fa-clock text-xs mr-0.5"></i>{{ $nvdDaysAgo==0?'Today':$nvdDaysAgo.'d ago' }}
                                </span>
                                @else
                                <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">
                                    <i class="fas fa-phone-slash text-xs mr-0.5"></i>Not called
                                </span>
                                @endif
                            @else
                                <span class="text-gray-400">&mdash;</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-gray-600">
                            {{ $do->delivery_date ? \Carbon\Carbon::parse($do->delivery_date)->format('d M Y') : '&mdash;' }}
                            @if($delivDays !== null)<span class="block text-xs text-gray-400">{{ $delivDays }}d ago</span>@endif
                        </td>
                        <td class="px-3 py-3">
                            @if($do->payment_type==='Cash')
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded font-medium">Cash</span>
                            @elseif($do->payment_type==='Direct')
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded font-medium">Direct</span>
                            @else
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded font-medium">Finance</span>
                            @endif
                            <span class="block text-xs text-gray-500 mt-0.5">{{ number_format($do->customer_paid_amount,0) }}</span>
                        </td>
                        <td class="px-3 py-3">
                            <span class="px-2 py-0.5 bg-{{ $sc }}-100 text-{{ $sc }}-700 text-xs rounded font-medium">{{ $do->status }}</span>
                        </td>
                        <td class="px-3 py-3">
                            @if($nvdLog)
                                <span class="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">NVD Called</span>
                                <span class="block text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($nvdLog->called_at)->format('d M Y') }}</span>
                                <span class="block text-xs text-gray-500 truncate max-w-28" title="{{ $nvdLog->remarks }}">{{ Str::limit($nvdLog->remarks,25) }}</span>
                            @else
                                <span class="text-xs text-gray-400 italic">No NVD call yet</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-center no-print">
                            <button onclick="openNvdCallModal({{ $do->id }},'{{ addslashes($do->customer_name) }}','{{ $do->customer_phone }}','{{ $do->do_no }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition">
                                <i class="fas fa-phone-alt"></i> Log NVD
                            </button>
                            <a href="{{ route('sv.print-do', $do->id) }}" target="_blank"
                               class="mt-1 inline-flex items-center gap-1 px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs rounded-lg transition">
                                <i class="fas fa-print"></i> DO
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-6 py-10 text-center text-gray-400">No delivered vehicles in selected date range.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================
     CALL HISTORY
============================================================ --}}
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
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date &amp; Time</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ref#</th>
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
                    @php $statusColors=['Contacted'=>'green','Not Reachable'=>'red','Callback Requested'=>'yellow','Voicemail'=>'gray','Wrong Number'=>'orange']; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-3 text-gray-500">
                            {{ \Carbon\Carbon::parse($log->called_at)->format('d M Y') }}
                            <span class="block text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->called_at)->format('H:i') }}</span>
                        </td>
                        <td class="px-3 py-3 font-bold text-gray-900">#{{ $log->jobc_id }}</td>
                        <td class="px-3 py-3 font-medium text-gray-800">{{ $log->customer_name }}</td>
                        <td class="px-3 py-3 text-gray-600">{{ $log->mobile?:'&mdash;' }}</td>
                        <td class="px-3 py-3">
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full
                                {{ $log->call_type==='FFS' ?'bg-blue-100 text-blue-800'    :'' }}
                                {{ $log->call_type==='PSFU'?'bg-indigo-100 text-indigo-800' :'' }}
                                {{ $log->call_type==='ASFU'?'bg-teal-100 text-teal-800'    :'' }}
                                {{ $log->call_type==='CSF' ?'bg-yellow-100 text-yellow-800' :'' }}
                                {{ $log->call_type==='CFU' ?'bg-pink-100 text-pink-800'    :'' }}
                                {{ $log->call_type==='NVD' ?'bg-emerald-100 text-emerald-800':'' }}
                            ">{{ $log->call_type }}</span>
                        </td>
                        <td class="px-3 py-3">
                            <span class="px-2 py-0.5 text-xs rounded-full
                                bg-{{ $statusColors[$log->call_status]??'gray' }}-100
                                text-{{ $statusColors[$log->call_status]??'gray' }}-800">
                                {{ $log->call_status }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-xs text-gray-600 max-w-xs" title="{{ $log->remarks }}">{{ Str::limit($log->remarks,60)?:'&mdash;' }}</td>
                        <td class="px-3 py-3 text-xs {{ $log->next_followup_date?'text-blue-700 font-semibold':'text-gray-300' }}">
                            {{ $log->next_followup_date?\Carbon\Carbon::parse($log->next_followup_date)->format('d M Y'):'&mdash;' }}
                        </td>
                        <td class="px-3 py-3 text-xs text-gray-500">{{ $log->called_by?:'&mdash;' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-6 py-10 text-center text-gray-400">No call logs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>{{-- /space-y-4 --}}

{{-- ============================================================
     LOG CALL MODAL
============================================================ --}}
<div id="callModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden no-print">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-800"><i class="fas fa-phone-alt text-green-600 mr-2"></i>Log Call</h3>
            <button onclick="closeCallModal()" class="text-gray-400 hover:text-gray-700 text-xl"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('sales.crm-log') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="jobc_id" id="modal_jobc_id">
            <div class="p-3 bg-blue-50 rounded-lg text-sm border border-blue-100">
                <p class="font-semibold text-gray-700" id="modal_customer_display">&mdash;</p>
                <p class="text-gray-500" id="modal_mobile_display">&mdash;</p>
                <p class="text-gray-400 text-xs" id="modal_reg_display">&mdash;</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Call Type <span class="text-red-500">*</span></label>
                    <select name="call_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="FFS">FFS &mdash; First Follow-up</option>
                        <option value="PSFU">PSFU &mdash; Post Service F/U</option>
                        <option value="ASFU">ASFU &mdash; After Sales F/U</option>
                        <option value="CSF">CSF &mdash; Customer Satisfaction</option>
                        <option value="CFU">CFU &mdash; Complaint F/U</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Call Status <span class="text-red-500">*</span></label>
                    <select name="call_status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option>Contacted</option>
                        <option>Not Reachable</option>
                        <option>Callback Requested</option>
                        <option>Voicemail</option>
                        <option>Wrong Number</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date &amp; Time <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="called_at" value="{{ now()->format('Y-m-d\TH:i') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                <textarea name="remarks" rows="2" placeholder="What was discussed…"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Next Follow-Up Date</label>
                <input type="date" name="next_followup_date"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Save Call Log
                </button>
                <button type="button" onclick="closeCallModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- LOG NVD CALL MODAL --}}
<div id="nvdCallModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden no-print">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">
        <div class="flex items-center justify-between px-6 py-4 bg-emerald-50 border-b border-emerald-100 rounded-t-2xl">
            <h3 class="text-base font-bold text-emerald-800"><i class="fas fa-car text-emerald-600 mr-2"></i>Log NVD Follow-Up Call</h3>
            <button onclick="closeNvdModal()" class="text-gray-400 hover:text-gray-700 text-xl"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('sales.crm-log') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="jobc_id" id="nvd_modal_do_id">
            <input type="hidden" name="call_type" value="NVD">
            <div class="p-3 bg-emerald-50 rounded-lg text-sm border border-emerald-100">
                <p class="font-semibold text-gray-700" id="nvd_customer_display">&mdash;</p>
                <p class="text-gray-500" id="nvd_phone_display">&mdash;</p>
                <p class="text-emerald-600 text-xs font-medium" id="nvd_do_display">&mdash;</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Call Status <span class="text-red-500">*</span></label>
                    <select name="call_status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
                        <option>Contacted</option>
                        <option>Not Reachable</option>
                        <option>Callback Requested</option>
                        <option>Voicemail</option>
                        <option>Wrong Number</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date &amp; Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="called_at" value="{{ now()->format('Y-m-d\TH:i') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks / Customer Feedback</label>
                <textarea name="remarks" rows="3" placeholder="e.g. Customer happy, asked about FFS date…"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Next Follow-Up Date</label>
                <input type="date" name="next_followup_date"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Save NVD Log
                </button>
                <button type="button" onclick="closeNvdModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- HISTORY MODAL --}}
<div id="historyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden no-print">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[80vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-800"><i class="fas fa-history text-gray-500 mr-2"></i>Call History &mdash; <span id="historyRo"></span></h3>
            <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-700 text-xl"><i class="fas fa-times"></i></button>
        </div>
        <div id="historyContent" class="overflow-y-auto p-6 space-y-3 text-sm"></div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .no-print, nav, aside, header { display: none !important; }
    body { font-size: 11px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #d1d5db; padding: 4px 6px; text-align: left; }
    thead { background-color: #f9fafb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .bg-white { box-shadow: none !important; }
}
</style>
@endpush

@push('scripts')
<script>
const allCallLogs = @json($callLogsAll);

function showTab(tab) {
    ['all','consumable','due','delivered','history'].forEach(function(t) {
        document.getElementById('pane-'+t)?.classList.add('hidden');
        var btn = document.getElementById('tab-'+t);
        if(btn) btn.className = 'tab-btn px-3 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-600 hover:text-white transition-colors';
    });
    document.getElementById('pane-'+tab)?.classList.remove('hidden');
    var aBtn = document.getElementById('tab-'+tab);
    if(aBtn) {
        var cl = {all:'bg-blue-600',consumable:'bg-orange-500',due:'bg-red-600',delivered:'bg-emerald-600',history:'bg-gray-700'};
        aBtn.className = 'tab-btn px-3 py-2 text-sm font-medium rounded-lg '+cl[tab]+' text-white transition-colors';
    }
}

function openCallModal(id, name, mob, reg) {
    document.getElementById('modal_jobc_id').value = id;
    document.getElementById('modal_customer_display').textContent = name;
    document.getElementById('modal_mobile_display').textContent = mob || 'No mobile';
    document.getElementById('modal_reg_display').textContent = 'RO#'+id+(reg?' | '+reg:'');
    document.getElementById('callModal').classList.remove('hidden');
}
function closeCallModal() { document.getElementById('callModal').classList.add('hidden'); }

function openNvdCallModal(doId, name, phone, doNo) {
    document.getElementById('nvd_modal_do_id').value = doId;
    document.getElementById('nvd_customer_display').textContent = name;
    document.getElementById('nvd_phone_display').textContent = phone || 'No phone';
    document.getElementById('nvd_do_display').textContent = 'DO# '+doNo;
    document.getElementById('nvdCallModal').classList.remove('hidden');
}
function closeNvdModal() { document.getElementById('nvdCallModal').classList.add('hidden'); }

function viewHistory(jobcId) {
    document.getElementById('historyRo').textContent = '#'+jobcId;
    var logs = allCallLogs.filter(function(l){ return l.jobc_id==jobcId; });
    var tc = {FFS:'blue',PSFU:'indigo',ASFU:'teal',CSF:'yellow',CFU:'pink',NVD:'emerald'};
    var sc = {Contacted:'green','Not Reachable':'red','Callback Requested':'yellow',Voicemail:'gray','Wrong Number':'orange'};
    document.getElementById('historyContent').innerHTML = logs.length ? logs.map(function(l) {
        var t = tc[l.call_type]||'gray', s = sc[l.call_status]||'gray';
        var d = new Date(l.called_at);
        var ds = d.toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'})+' '+d.toLocaleTimeString('en-GB',{hour:'2-digit',minute:'2-digit'});
        return '<div class="p-4 bg-gray-50 rounded-xl border border-gray-100">'
            +'<div class="flex items-start justify-between gap-3 mb-2 flex-wrap">'
            +'<div class="flex gap-2 flex-wrap">'
            +'<span class="px-2 py-0.5 text-xs font-bold rounded-full bg-'+t+'-100 text-'+t+'-800">'+l.call_type+'</span>'
            +'<span class="px-2 py-0.5 text-xs rounded-full bg-'+s+'-100 text-'+s+'-800">'+l.call_status+'</span>'
            +'</div><span class="text-xs text-gray-400">'+ds+'</span></div>'
            +'<p class="text-gray-700 text-sm">'+(l.remarks||'<span class="text-gray-400 italic">No remarks</span>')+'</p>'
            +(l.next_followup_date?'<p class="text-xs text-blue-600 mt-1.5 font-semibold">Next F/U: '+l.next_followup_date+'</p>':'')
            +'<p class="text-xs text-gray-400 mt-1">By: '+(l.called_by||'—')+'</p></div>';
    }).join('') : '<p class="text-gray-400 text-center py-6">No call logs for this RO.</p>';
    document.getElementById('historyModal').classList.remove('hidden');
}
function closeHistoryModal() { document.getElementById('historyModal').classList.add('hidden'); }

function toggleNvdHighlight(chk) {
    document.querySelectorAll('#nvd-tbody tr.nvd-no-call').forEach(function(r) {
        r.style.background = chk.checked ? '#fef2f2' : '';
        r.style.borderLeft = chk.checked ? '4px solid #ef4444' : '';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var chk = document.getElementById('highlight_nvd_toggle');
    if(chk) toggleNvdHighlight(chk);
    ['callModal','nvdCallModal','historyModal'].forEach(function(id) {
        var el = document.getElementById(id);
        if(el) el.addEventListener('click', function(e){ if(e.target===this) this.classList.add('hidden'); });
    });
});
</script>
@endpush
@endsection
