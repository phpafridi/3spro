@extends('layouts.master')

@section('title', 'Cashier - Search')

@section('sidebar-menu')
    @include('finance.cashier.sidebar')
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-search text-blue-500 mr-2"></i>
            Search Invoices
        </h2>
    </div>

    <div class="mb-6">
        <div class="relative">
            <input type="text"
                   id="searchInput"
                   placeholder="Search by Job ID, Registration, Customer name or mobile..."
                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
        </div>
    </div>

    <div id="searchResults" class="mt-6">
        <div class="text-center text-gray-500 py-8">
            <i class="fas fa-arrow-up text-4xl mb-3 text-gray-300"></i>
            <p>Type something to search...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let searchTimeout;

    document.getElementById('searchInput').addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const query = this.value;

        if (query.length < 2) {
            document.getElementById('searchResults').innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-arrow-up text-4xl mb-3 text-gray-300"></i>
                    <p>Type at least 2 characters to search...</p>
                </div>
            `;
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/finance/cashier/search-jobs?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    let html = '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">';
                    html += '<thead class="bg-gray-50"><tr>';
                    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job ID</th>';
                    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>';
                    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>';
                    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>';
                    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobile</th>';
                    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>';
                    html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>';
                    html += '</tr></thead><tbody class="bg-white divide-y divide-gray-200">';

                    if (data.length === 0) {
                        html += '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No results found</td></tr>';
                    } else {
                        data.forEach(item => {
                            let statusClass = item.status == '1' ? 'bg-yellow-100 text-yellow-800' :
                                             item.status == '2' ? 'bg-green-100 text-green-800' :
                                             'bg-gray-100 text-gray-800';
                            let statusText = item.status == '1' ? 'Open' :
                                           item.status == '2' ? 'Completed' :
                                           'Closed';

                            html += `<tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-mono">#${item.Jobc_id}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.Customer_name}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.Variant}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.Registration}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.mobile}</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs rounded-full ${statusClass}">${statusText}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="/finance/cashier/invoice?job_id=${item.Jobc_id}" target="_blank"
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-file-invoice"></i> View
                                    </a>
                                </td>
                            </tr>`;
                        });
                    }

                    html += '</tbody></table></div>';
                    document.getElementById('searchResults').innerHTML = html;
                });
        }, 500);
    });
</script>
@endpush
