{{-- resources/views/service/jc/sublet_assign.blade.php --}}
@extends('layouts.master')

@section('title', 'Job Controller - Assign Sublet')

@section('sidebar-menu')
    <a href="{{ route('jc.dashboard') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
        <i class="fas fa-wrench w-6"></i>
        <span>Jobs Requests</span>
    </a>
    <a href="{{ route('jc.sublet') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.sublet') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
        <i class="fas fa-sign-out-alt w-6"></i>
        <span>Sublet Requests</span>
    </a>
    <a href="{{ route('jc.inprogress') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.inprogress') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
        <i class="fas fa-edit w-6"></i>
        <span>Inprogress Jobs</span>
    </a>
    <a href="{{ route('jc.parts-status') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.parts-status') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
        <i class="fas fa-search-plus w-6"></i>
        <span>Parts Status</span>
    </a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">
                    <i class="fas fa-truck mr-2"></i>
                    @if(!isset($jobDoneMode))
                        Assign Sublet
                    @else
                        Complete Sublet
                    @endif
                </h2>
                <a href="{{ route('jc.sublet') }}" class="inline-flex items-center px-4 py-2 bg-white text-purple-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>

        <div class="p-6">
            @if(!isset($jobDoneMode))
                <!-- Assign Sublet Form -->
                <form action="{{ route('jc.sublet-assign.process') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="sublet_id" value="{{ $sublet->sublet_id }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Part Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="parts_details" required rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Enter part description..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Vendor <span class="text-red-500">*</span>
                        </label>
                        <select name="Vendor" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Choose a vendor...</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->vendor_name }}">{{ $vendor->vendor_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Taken By <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="who_taking" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Enter person name">
                    </div>

                    <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-check mr-2"></i> Assign Sublet
                        </button>
                        <button type="reset" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            Reset
                        </button>
                    </div>
                </form>
            @else
                <!-- Job Done Form -->
                <form action="{{ route('jc.sublet-done.process') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="sublet_id" value="{{ $sublet->sublet_id }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Vendor Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="Vendorprice" required step="0.01" min="0"
                                   class="pl-7 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Logistics Cost <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="Logistics" required step="0.01" min="0"
                                   class="pl-7 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="0.00">
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-check-circle mr-2"></i> Complete Sublet
                        </button>
                        <button type="reset" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            Reset
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- Vendors List Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-truck mr-2"></i> Available Vendors
            </h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Work Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Person</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($vendors as $vendor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $vendor->vendor_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $vendor->work_type }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $vendor->contact }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $vendor->contact_person }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $vendor->Location }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">No vendors found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
