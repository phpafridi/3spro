@extends('layouts.master')
@section('title', 'SM - New User')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded shadow-sm p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New User</h2>
        @if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
        <form method="POST" action="{{ route('sm.new-user.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required style="text-transform:uppercase"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Login ID <span class="text-red-500">*</span></label>
                    <input type="text" name="login_id" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password2" required minlength="6"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                    <input type="file" name="fileup" accept=".jpg,.jpeg,.png"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department &amp; Position <span class="text-red-500">*</span></label>
                <select name="position" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select --</option>
                    <option value="Service-SManager">Service — Service Manager</option>
                    <option value="Service-SerAdvisor">Service — Service Advisor</option>
                    <option value="Service-JobController">Service — Job Controller</option>
                    <option value="Service-body_PaintJC">Service — Body &amp; Paint JC</option>
                    <option value="Service-IMCc">Service — IMC Coordinator</option>
                    <option value="Finance-Cashier">Finance — Cashier</option>
                    <option value="Finance-FManager">Finance — Finance Manager</option>
                    <option value="Parts-PManager">Parts — Parts Manager</option>
                    <option value="Parts-DataOperator">Parts — Data Operator</option>
                    <option value="IT-IT Manager">IT — IT Manager</option>
                </select>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-user-plus mr-2"></i> Create User
            </button>
        </form>
    </div>
</div>
@endsection
