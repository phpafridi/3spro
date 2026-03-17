@extends('layouts.master')
@section('title', 'Sales - Active Customers')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-blue-500">
        <div class="text-2xl font-bold text-blue-500">{{ $customers->total() }}</div>
        <div class="text-sm text-gray-500 mt-1">Total Customers</div>
    </div>
    @foreach($typeStats->take(2) as $ts)
    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-purple-400">
        <div class="text-2xl font-bold text-purple-500">{{ $ts->total }}</div>
        <div class="text-sm text-gray-500 mt-1">{{ $ts->cust_type }}</div>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Active Customers</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobile</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Update Type</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($customers as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $c->Customer_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $c->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $c->mobile }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->City }}</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">{{ $c->cust_type }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <select onchange="updateType(this, {{ $c->Customer_id }})"
                                class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @foreach(['Individuals','Govt','Force','Corporate','Banks','Investor','Others'] as $t)
                            <option value="{{ $t }}" {{ $c->cust_type==$t?'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $customers->links() }}</div>
</div>
@push('scripts')
<script>
function updateType(sel, custId) {
    fetch('{{ route("sales.ac.update-type") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({cust_id: custId, cust_type: sel.value})
    }).then(r=>r.json()).then(res=>{ if(res.status==='ok') sel.style.borderColor='green'; });
}
</script>
@endpush
@endsection
