@extends('layouts.master')
@section('title', 'SM - Labor List')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>
@endif

<!-- Stack on mobile, grid on desktop -->
<div class="flex flex-col lg:flex-row gap-6">
    <!-- Add Labor Form - Full width on mobile, 1/3 on desktop -->
    <div class="w-full lg:w-1/3">
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Add Labor</h2>

            <form method="POST" action="{{ route('sm.master.labor.store') }}">
                @csrf

                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">
                            Labor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="Labor" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Cate1</label>
                        <input type="text" name="Cate1"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Cate2</label>
                        <input type="text" name="Cate2"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Cate3</label>
                        <input type="text" name="Cate3"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Cate4</label>
                        <input type="text" name="Cate4"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Cate5</label>
                        <input type="text" name="Cate5"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit"
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                        <i class="fa fa-plus mr-2"></i> Add Labor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Labor List - Full width on mobile, 2/3 on desktop -->
    <div class="w-full lg:w-2/3">
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Labor List</h2>
                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-sm rounded-full self-start sm:self-auto">
                    {{ $laborList->count() }} Total
                </span>
            </div>

            <!-- Horizontal scroll on mobile -->
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden border border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                                    <th class="hidden sm:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cate1</th>
                                    <th class="hidden md:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cate2</th>
                                    <th class="hidden lg:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cate3</th>
                                    <th class="hidden xl:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cate4</th>
                                    <th class="hidden 2xl:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cate5</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($laborList as $l)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Labor_ID }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm font-medium text-gray-800 whitespace-nowrap">{{ $l->Labor }}</td>
                                    <td class="hidden sm:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Cate1 ?? '-' }}</td>
                                    <td class="hidden md:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Cate2 ?? '-' }}</td>
                                    <td class="hidden lg:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Cate3 ?? '-' }}</td>
                                    <td class="hidden xl:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Cate4 ?? '-' }}</td>
                                    <td class="hidden 2xl:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $l->Cate5 ?? '-' }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm whitespace-nowrap">
                                        <form method="POST" action="{{ route('sm.master.labor.delete') }}" class="inline" onsubmit="return confirm('Delete this labor?')">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $l->Labor_ID }}">
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-3 sm:px-4 py-8 text-center text-gray-400">
                                        No labor found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Mobile view hint -->
            <div class="block sm:hidden mt-2 text-xs text-gray-400 text-center">
                <i class="fa fa-arrow-left mr-1"></i> Scroll horizontally to see all categories <i class="fa fa-arrow-right ml-1"></i>
            </div>

            <!-- Column visibility legend (optional) -->
            <div class="hidden sm:flex mt-3 text-xs text-gray-400 gap-3 flex-wrap">
                <span class="flex items-center"><span class="w-2 h-2 bg-gray-300 rounded-full mr-1"></span> Always visible: ID, Labor, Action</span>
                <span class="flex items-center"><span class="w-2 h-2 bg-gray-300 rounded-full mr-1"></span> sm: Cate1</span>
                <span class="flex items-center"><span class="w-2 h-2 bg-gray-300 rounded-full mr-1"></span> md: Cate2</span>
                <span class="flex items-center"><span class="w-2 h-2 bg-gray-300 rounded-full mr-1"></span> lg: Cate3</span>
                <span class="flex items-center"><span class="w-2 h-2 bg-gray-300 rounded-full mr-1"></span> xl: Cate4</span>
                <span class="flex items-center"><span class="w-2 h-2 bg-gray-300 rounded-full mr-1"></span> 2xl: Cate5</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function(){
    // Optional: Add any custom JavaScript here
});
</script>
@endpush
@endsection
