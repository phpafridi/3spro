@extends('layouts.master')
@section('title', 'SM - Active Customers')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Active Customers
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $customers->total() }}</span>
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobile</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Update Type</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($customers as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->Customer_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $c->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->mobile }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->City }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->cust_type }}</td>
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
    fetch('{{ route("sm.ac.update-type") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({cust_id: custId, cust_type: sel.value})
    }).then(r=>r.json()).then(res=>{ if(res.status==='ok') sel.style.borderColor='green'; });
}
</script>
@endpush
@endsection
