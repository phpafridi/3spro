@extends('layouts.master')
@section('title', 'SM - Insurance Companies')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    <div class="md:col-span-2 bg-white rounded shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Add Insurance Company</h2>
        <form method="POST" action="{{ route('sm.insurance.store') }}" class="space-y-3">
            @csrf
            @foreach(['jobber'=>'Company Name *','contactperson'=>'Contact Person','contact'=>'Contact','email'=>'Email','address'=>'Address','ntn'=>'NTN'] as $name=>$label)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                <input type="text" name="{{ $name }}" {{ str_contains($label,'*') ? 'required' : '' }}
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            @endforeach
            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-plus mr-2"></i> Add
            </button>
        </form>
    </div>
    <div class="md:col-span-3 bg-white rounded shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Insurance Companies
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $companies->count() }}</span>
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($companies as $v)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-sm font-medium text-gray-800">{{ $v->company_name }}</td>
                        <td class="px-3 py-2 text-sm text-gray-500">{{ $v->contact }}</td>
                        <td class="px-3 py-2 text-sm text-gray-500">{{ $v->email }}</td>
                        <td class="px-3 py-2 text-sm">
                            @if($v->status=='Active')<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Active</span>
                            @else<span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">Suspended</span>@endif
                        </td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('sm.insurance.toggle') }}" class="inline">
                                @csrf
                                <input type="hidden" name="c_id" value="{{ $v->c_id }}">
                                <input type="hidden" name="action" value="{{ $v->status=='Active'?'suspend':'activate' }}">
                                <button type="submit" class="px-2 py-1 {{ $v->status=='Active' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700' }} text-white text-xs rounded transition-colors">
                                    {{ $v->status=='Active' ? 'Suspend' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">No insurance companies.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
