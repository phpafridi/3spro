@extends('layouts.master')
@section('title', 'Search Engine')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Search Engine</h2>

    <form method="POST" action="{{ route('jobcard.search') }}" class="flex flex-wrap gap-3 mb-6">
        @csrf
        <input type="text" name="search" value="{{ $search }}"
               placeholder="Enter search term..."
               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">

        <select name="field" onchange="this.form.submit()" required
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="" {{ !$field ? 'selected' : '' }}>-- Select Type --</option>
            <optgroup label="Jobcard">
                <option value="jobcard-instail"   {{ $field==='jobcard-instail'   ? 'selected':'' }}>Initial JC</option>
                <option value="jobcard-closed"    {{ $field==='jobcard-closed'    ? 'selected':'' }}>Closed JC</option>
                <option value="Invoice"           {{ $field==='Invoice'           ? 'selected':'' }}>Invoice #</option>
                <option value="jobcard-Customer_id" {{ $field==='jobcard-Customer_id' ? 'selected':'' }}>Cust Code</option>
                <option value="jobcard-Vehicle_id"  {{ $field==='jobcard-Vehicle_id'  ? 'selected':'' }}>Veh Code</option>
            </optgroup>
            <optgroup label="Customer">
                <option value="customer_data-Customer_name" {{ $field==='customer_data-Customer_name' ? 'selected':'' }}>Name</option>
                <option value="customer_data-mobile"        {{ $field==='customer_data-mobile'        ? 'selected':'' }}>Mobile</option>
                <option value="customer_data-Address"       {{ $field==='customer_data-Address'       ? 'selected':'' }}>Address</option>
                <option value="customer_data-Customer_id"   {{ $field==='customer_data-Customer_id'   ? 'selected':'' }}>Cust Code</option>
            </optgroup>
            <optgroup label="Vehicle">
                <option value="vehicles_data-Registration" {{ $field==='vehicles_data-Registration' ? 'selected':'' }}>Registration</option>
                <option value="vehicles_data-Frame_no"     {{ $field==='vehicles_data-Frame_no'     ? 'selected':'' }}>Frame Number</option>
                <option value="vehicles_data-Model"        {{ $field==='vehicles_data-Model'        ? 'selected':'' }}>Model</option>
                <option value="vehicles_data-Vehicle_id"   {{ $field==='vehicles_data-Vehicle_id'   ? 'selected':'' }}>Veh Code</option>
            </optgroup>
            <optgroup label="RO Items">
                <option value="jobc_parts"    {{ $field==='jobc_parts'    ? 'selected':'' }}>Parts (by RO#)</option>
                <option value="jobc_consumble"{{ $field==='jobc_consumble'? 'selected':'' }}>Consumble (by RO#)</option>
            </optgroup>
        </select>
    </form>

    {{-- Results --}}
    @if($results->count())
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            @if($tableType === 'customer')
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">#</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Cust Code</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Customer Name</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Mobile</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Veh Reg#</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Address</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Type</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Last Visit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($results as $i => $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $i+1 }}</td>
                    <td class="px-3 py-2 text-sm">
                        <a href="{{ route('jobcard.search') }}?field=vehicles_data-Vehicle_id&search={{ $r->Customer_id }}"
                           class="text-blue-600 hover:underline" target="_blank">{{ $r->Customer_id }}</a>
                    </td>
                    <td class="px-3 py-2 text-sm font-medium">{{ $r->Customer_name }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->mobile }}</td>
                    <td class="px-3 py-2 text-sm text-red-600">{{ $r->regs ?? '—' }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->Address }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->cust_type }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $r->lastvisit }}</td>
                </tr>
                @endforeach
            </tbody>

            @elseif($tableType === 'vehicle')
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">#</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Veh Code</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Registration</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Customer(s)</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Frame #</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Model</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Variant</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Colour</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Engine</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">For Sale</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Last Visit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($results as $i => $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $i+1 }}</td>
                    <td class="px-3 py-2 text-sm">
                        <a href="{{ route('jobcard.vehicle-detail', ['vehicle_id' => $r->Vehicle_id]) }}"
                           class="text-blue-600 hover:underline">{{ $r->Vehicle_id }}</a>
                    </td>
                    <td class="px-3 py-2 text-sm font-medium text-red-600">{{ $r->Registration }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->customers ?? '—' }}</td>
                    <td class="px-3 py-2 text-xs font-mono">{{ $r->Frame_no }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->Model }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->Variant }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->Colour }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->Engine_Code }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->into_sell }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $r->lastvisit }}</td>
                </tr>
                @endforeach
            </tbody>

            @elseif($tableType === 'parts')
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">#</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Inv#</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Part Number</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Qty</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Unit Price</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Net Amount</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Issued To</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Issued Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($results as $i => $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $i+1 }}</td>
                    <td class="px-3 py-2 text-sm text-blue-600">WP-{{ $r->part_invoice_no }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->part_number }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->issued_qty }}</td>
                    <td class="px-3 py-2 text-sm">{{ number_format($r->unitprice, 0) }}</td>
                    <td class="px-3 py-2 text-sm font-medium">{{ number_format($r->total, 0) }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->issue_to }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $r->bookingtime }}</td>
                </tr>
                @endforeach
                <tr class="bg-gray-100 font-semibold">
                    <td colspan="4" class="px-3 py-2 text-right text-sm">TOTAL</td>
                    <td colspan="2" class="px-3 py-2 text-sm text-center">{{ number_format($total, 0) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>

            @elseif($tableType === 'consumble')
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">#</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Req#</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Part Number</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Qty</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Unit Price</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Net Amount</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Issued To</th>
                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-left">Issued Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($results as $i => $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $i+1 }}</td>
                    <td class="px-3 py-2 text-sm text-blue-600">WC-{{ $r->cons_req_no }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->cons_number }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->issued_qty }}</td>
                    <td class="px-3 py-2 text-sm">{{ number_format($r->unitprice, 0) }}</td>
                    <td class="px-3 py-2 text-sm font-medium">{{ number_format($r->total, 0) }}</td>
                    <td class="px-3 py-2 text-sm">{{ $r->issue_to }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $r->bookingtime }}</td>
                </tr>
                @endforeach
                <tr class="bg-gray-100 font-semibold">
                    <td colspan="4" class="px-3 py-2 text-right text-sm">TOTAL</td>
                    <td colspan="2" class="px-3 py-2 text-sm text-center">{{ number_format($total, 0) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
            @endif
        </table>
    </div>
    @elseif($field && $search !== '')
    <div class="text-center py-10 text-gray-400">
        <i class="fa fa-search text-3xl block mb-2"></i>
        No results found for "{{ $search }}".
    </div>
    @endif
</div>
@endsection
