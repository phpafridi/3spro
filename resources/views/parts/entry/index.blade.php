@extends('parts.layout')
@section('title', 'Workshop Requisitions')
@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-800 text-sm">{{ session('success') }}</div>
@endif
@if(session('warning'))
<div class="mb-4 p-3 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 text-sm">{{ session('warning') }}</div>
@endif

{{-- PARTS --}}
<div class="mb-6">
    <div class="bg-red-600 px-4 py-2 rounded-t">
        <h3 class="font-bold text-white">Workshop Requisitions — Parts</h3>
    </div>
    <div class="overflow-x-auto border border-gray-200 rounded-b">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">RO#</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Part Description</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Qty (Issued/Req)</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">U-Price</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Customer / Campaign</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Registration</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Time Elapsed</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Req#</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">SA</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Issue Part</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">NA</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($workshopParts as $p)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 font-bold text-red-600">{{ $p->RO_no }}</td>
                    <td class="px-3 py-2">
                        {{ $p->part_description }}
                        <br><span class="text-xs text-green-600">{{ $p->Variant }} ({{ $p->Model }})</span>
                    </td>
                    <td class="px-3 py-2">{{ $p->issued_qty }}/{{ $p->req_qty }}</td>
                    <td class="px-3 py-2">{{ $p->unitprice }}</td>
                    <td class="px-3 py-2">
                        <span class="text-red-600 font-medium">{{ $p->Customer_name }}</span>
                        <br><span class="text-xs text-green-600">{{ $p->comp_appointed }}/{{ $p->cust_source }}</span>
                    </td>
                    <td class="px-3 py-2">{{ $p->Veh_reg_no }}</td>
                    <td class="px-3 py-2">
                        <p class="text-red-600 font-mono text-xs">{{ $p->time_elapsed }}</p>
                        <span class="text-xs text-green-600">{{ $p->booking_time }}</span>
                    </td>
                    <td class="px-3 py-2">
                        {{-- Req# dropdown matching original --}}
                        <select id="inv_part_{{ $p->parts_sale_id }}" class="border border-gray-300 rounded px-2 py-1 text-xs">
                            @foreach($invoiceNumbers->get($p->RO_no, collect()) as $inv)
                            <option>{{ $inv->part_invoice_no }}</option>
                            @endforeach
                            <option value="New">New</option>
                        </select>
                    </td>
                    <td class="px-3 py-2 text-xs">{{ $p->SA }}</td>
                    <td class="px-3 py-2">
                        {{-- Issue Part — goes to issue page with all data --}}
                        <form action="{{ route('parts.issue-part-form') }}" method="POST">
                            @csrf
                            <input type="hidden" name="part_id"          value="{{ $p->parts_sale_id }}">
                            <input type="hidden" name="RO_no"            value="{{ $p->RO_no }}">
                            <input type="hidden" name="part_description" value="{{ $p->part_description }}">
                            <input type="hidden" name="qty"              value="{{ $p->qty }}">
                            <input type="hidden" name="req_qty"          value="{{ $p->req_qty }}">
                            <input type="hidden" name="issued_qty"       value="{{ $p->issued_qty }}">
                            <input type="hidden" name="unitprice"        value="{{ $p->unitprice }}">
                            <input type="hidden" name="total"            value="{{ $p->total }}">
                            <input type="hidden" name="open_ro"          value="1">
                            <input type="hidden" name="invoice_no_field" id="inv_field_{{ $p->parts_sale_id }}" value="New">
                            <button type="submit" onclick="setInv({{ $p->parts_sale_id }})"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors">
                                Issue Part
                            </button>
                        </form>
                    </td>
                    <td class="px-3 py-2">
                        <form action="{{ route('parts.index') }}" method="POST">
                            @csrf
                            <input type="hidden" name="not_available_id" value="{{ $p->parts_sale_id }}">
                            <button type="submit"
                                    class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded transition-colors">
                                NA
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" class="px-4 py-8 text-center text-gray-400">No pending parts requests</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- CONSUMABLES --}}
<div class="mb-6">
    <div class="bg-red-700 px-4 py-2 rounded-t">
        <h3 class="font-bold text-white">Workshop Requisitions — Consumables</h3>
    </div>
    <div class="overflow-x-auto border border-gray-200 rounded-b">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">RO#</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Description</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Qty (Issued/Req)</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">U-Price</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Customer / Campaign</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Registration</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Time Elapsed</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Req#</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">SA</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Issue Cons</th>
                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">NA</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($workshopConsumbles as $c)
                <tr class="hover:bg-orange-50">
                    <td class="px-3 py-2 font-bold text-red-600">{{ $c->RO_no }}</td>
                    <td class="px-3 py-2">
                        {{ $c->cons_description }}
                        <br><span class="text-xs text-green-600">{{ $c->Variant }} ({{ $c->Model }})</span>
                    </td>
                    <td class="px-3 py-2">{{ $c->issued_qty }}/{{ $c->req_qty }}</td>
                    <td class="px-3 py-2">{{ $c->unitprice }}</td>
                    <td class="px-3 py-2">
                        <span class="text-red-600 font-medium">{{ $c->Customer_name }}</span>
                        <br><span class="text-xs text-green-600">{{ $c->comp_appointed }}/{{ $c->cust_source }}</span>
                    </td>
                    <td class="px-3 py-2">{{ $c->Veh_reg_no }}</td>
                    <td class="px-3 py-2">
                        <p class="text-red-600 font-mono text-xs">{{ $c->time_elapsed }}</p>
                        <span class="text-xs text-green-600">{{ $c->booking_time }}</span>
                    </td>
                    <td class="px-3 py-2">
                        <select id="inv_cons_{{ $c->cons_sale_id }}" class="border border-gray-300 rounded px-2 py-1 text-xs">
                            @foreach($reqNumbers->get($c->RO_no, collect()) as $req)
                            <option>{{ $req->cons_req_no }}</option>
                            @endforeach
                            <option value="New">New</option>
                        </select>
                    </td>
                    <td class="px-3 py-2 text-xs">{{ $c->SA }}</td>
                    <td class="px-3 py-2">
                        <form action="{{ route('parts.issue-cons-form') }}" method="POST">
                            @csrf
                            <input type="hidden" name="part_id"          value="{{ $c->cons_sale_id }}">
                            <input type="hidden" name="RO_no"            value="{{ $c->RO_no }}">
                            <input type="hidden" name="part_description" value="{{ $c->cons_description }}">
                            <input type="hidden" name="qty"              value="{{ $c->qty }}">
                            <input type="hidden" name="req_qty"          value="{{ $c->req_qty }}">
                            <input type="hidden" name="issued_qty"       value="{{ $c->issued_qty }}">
                            <input type="hidden" name="unitprice"        value="{{ $c->unitprice }}">
                            <input type="hidden" name="total"            value="{{ $c->total }}">
                            <input type="hidden" name="open_ro"          value="1">
                            <input type="hidden" name="invoice_no_field" id="cons_field_{{ $c->cons_sale_id }}" value="New">
                            <button type="submit" onclick="setConsInv({{ $c->cons_sale_id }})"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors">
                                Issue Cons
                            </button>
                        </form>
                    </td>
                    <td class="px-3 py-2">
                        <form action="{{ route('parts.index') }}" method="POST">
                            @csrf
                            <input type="hidden" name="not_available_cons" value="{{ $c->cons_sale_id }}">
                            <button type="submit"
                                    class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded transition-colors">
                                NA
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" class="px-4 py-8 text-center text-gray-400">No pending consumable requests</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
// Pass the selected requisition number to the hidden field before form submit
function setInv(partId) {
    var sel = document.getElementById('inv_part_' + partId);
    document.getElementById('inv_field_' + partId).value = sel ? sel.value : 'New';
}
function setConsInv(consId) {
    var sel = document.getElementById('inv_cons_' + consId);
    document.getElementById('cons_field_' + consId).value = sel ? sel.value : 'New';
}

// Auto-refresh every 60 seconds like original (meta refresh)
setTimeout(function() { location.reload(); }, 60000);
</script>
@endpush
@endsection
