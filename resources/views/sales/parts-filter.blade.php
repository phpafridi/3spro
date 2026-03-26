@extends('layouts.master')
@section('title', 'Parts Filter — Cars by Recent Parts')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-xl font-bold text-gray-800">
            <i class="fas fa-filter text-blue-600 mr-2"></i>Filter Cars by Parts Used
        </h2>
        <p class="text-sm text-gray-500 mt-1">Find vehicles based on specific parts used within a date range.</p>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white rounded-lg shadow-sm p-5">
        <form method="GET" action="{{ route('sales.parts-filter') }}" id="filterForm">
            <div class="flex flex-wrap gap-4 items-end">

                {{-- Part Description search --}}
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Part Description</label>
                    <input type="text" name="part" value="{{ $part ?? '' }}"
                           placeholder="e.g. Oil Filter, Brake Pad..."
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Date From --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ $dateFrom ?? '' }}"
                           class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Date To --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" name="date_to" id="date_to" value="{{ $dateTo ?? '' }}"
                           class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fas fa-search mr-1"></i> Search
                </button>

                @if(request()->hasAny(['part','date_from','date_to']))
                <a href="{{ route('sales.parts-filter') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-md transition-colors">
                    <i class="fas fa-times mr-1"></i> Clear
                </a>
                @endif
            </div>

            {{-- Quick Date Buttons --}}
            <div class="mt-3 flex flex-wrap gap-2 items-center">
                <span class="text-xs text-gray-500 font-medium">Quick:</span>
                <button type="button" onclick="setQuickDate(30)"
                        class="px-3 py-1 text-xs bg-gray-100 hover:bg-blue-100 hover:text-blue-700 text-gray-600 rounded-full transition-colors">
                    Last 30 days
                </button>
                <button type="button" onclick="setQuickDate(90)"
                        class="px-3 py-1 text-xs bg-gray-100 hover:bg-blue-100 hover:text-blue-700 text-gray-600 rounded-full transition-colors">
                    Last 3 months
                </button>
                <button type="button" onclick="setQuickDate(180)"
                        class="px-3 py-1 text-xs bg-gray-100 hover:bg-blue-100 hover:text-blue-700 text-gray-600 rounded-full transition-colors">
                    Last 6 months
                </button>
                <button type="button" onclick="setQuickDate(365)"
                        class="px-3 py-1 text-xs bg-gray-100 hover:bg-blue-100 hover:text-blue-700 text-gray-600 rounded-full transition-colors">
                    Last year
                </button>
                <button type="button" onclick="setCurrentMonth()"
                        class="px-3 py-1 text-xs bg-gray-100 hover:bg-blue-100 hover:text-blue-700 text-gray-600 rounded-full transition-colors">
                    This month
                </button>
            </div>
        </form>
    </div>

    {{-- Results --}}
    @if(isset($results))
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">
                Results
                @if($part ?? false)
                    — <span class="text-blue-600">{{ $part }}</span>
                @endif
                @if(($dateFrom ?? false) || ($dateTo ?? false))
                    <span class="text-sm font-normal text-gray-500 ml-1">
                        ({{ $dateFrom ?? '...' }} → {{ $dateTo ?? 'today' }})
                    </span>
                @endif
            </h3>
            <span class="text-sm text-gray-400">{{ $results->count() }} record(s)</span>
        </div>

        @if($results->count())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">RO#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Registration</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Vehicle</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Mobile</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Part Used</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($results as $row)
                    @php $daysAgo = (int) \Carbon\Carbon::parse($row->job_date)->diffInDays(now()); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $row->Jobc_id }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-blue-700">{{ $row->Registration ?: '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->Make }} {{ $row->Variant }}</td>
                        <td class="px-4 py-3 text-sm text-gray-800">{{ $row->Customer_name }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($row->mobile)
                            <a href="tel:{{ $row->mobile }}" class="text-green-600 hover:text-green-800 flex items-center gap-1">
                                <i class="fas fa-phone text-xs"></i> {{ $row->mobile }}
                            </a>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 font-medium">{{ $row->part_description }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $row->qty }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($row->total, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($row->job_date)->format('d M Y') }}
                            <span class="block text-xs text-gray-400">{{ $daysAgo }}d ago</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-sm font-semibold text-gray-700 text-right">Total Amount:</td>
                        <td class="px-4 py-2 text-sm font-bold text-green-700">{{ number_format($results->sum('total'), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="px-6 py-12 text-center text-gray-400">
            <i class="fas fa-search text-3xl block mb-3"></i>
            No results found for the selected filters.
        </div>
        @endif
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function setQuickDate(days) {
    const today = new Date();
    const from  = new Date();
    from.setDate(today.getDate() - days);
    document.getElementById('date_from').value = from.toISOString().split('T')[0];
    document.getElementById('date_to').value   = today.toISOString().split('T')[0];
    document.getElementById('filterForm').submit();
}

function setCurrentMonth() {
    const today = new Date();
    const from  = new Date(today.getFullYear(), today.getMonth(), 1);
    document.getElementById('date_from').value = from.toISOString().split('T')[0];
    document.getElementById('date_to').value   = today.toISOString().split('T')[0];
    document.getElementById('filterForm').submit();
}
</script>
@endpush
