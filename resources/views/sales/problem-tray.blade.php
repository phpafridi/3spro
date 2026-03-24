@extends('layouts.master')
@section('title', 'Sales - Problem Tray')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif

<div class="bg-white rounded shadow-sm p-6">
    <div class="flex items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Problem Tray — Pending
            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-sm rounded-full">{{ $problems->count() }}</span>
        </h2>
    </div>
    @if($problems->isEmpty())
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
            <i class="fa fa-check-circle mr-2"></i>No pending problems.
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Problem</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">CRO</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($problems as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $p->customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $p->Contact }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $p->problem }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">{{ $p->remarks }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $p->cro }}</td>
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $p->fdatetime }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1">
                            <form method="POST" action="{{ route('sales.problem-tray.action') }}" class="inline">
                                @csrf
                                <input type="hidden" name="problem_id" value="{{ $p->p_id }}">
                                <input type="hidden" name="action" value="forward">
                                <button type="submit" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                    Forward SA
                                </button>
                            </form>
                            <form method="POST" action="{{ route('sales.problem-tray.action') }}" class="inline"
                                  onsubmit="return confirm('Terminate this problem?')">
                                @csrf
                                <input type="hidden" name="problem_id" value="{{ $p->p_id }}">
                                <input type="hidden" name="action" value="terminate">
                                <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors">
                                    Terminate
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
