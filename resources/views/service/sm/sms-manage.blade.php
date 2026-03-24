@extends('layouts.master')
@section('title', 'SM - SMS Templates')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-5">SMS Template Management
        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $smsList->count() }}</span>
    </h2>
    <div class="space-y-4">
        @foreach($smsList as $sms)
        <div class="border border-gray-200 rounded p-4 bg-gray-50">
            <form method="POST" action="{{ route('sm.sms.update') }}">
                @csrf
                <input type="hidden" name="edit_sms_id" value="{{ $sms->id }}">
                <div class="flex gap-4 items-start">
                    <div class="w-32 shrink-0">
                        <span class="text-xs font-medium text-gray-500 uppercase">#{{ $sms->id }}</span>
                        <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $sms->type }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $sms->edit_on }}<br>by {{ $sms->edit_by }}</p>
                    </div>
                    <textarea name="message" rows="3" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $sms->sms_text }}</textarea>
                    <button type="submit" class="px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded transition-colors shrink-0">
                        <i class="fa fa-save mr-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection
