@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'New User')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-user-plus text-red-500 mr-2"></i> Create New User
    </h2>
    <form method="POST" action="{{ route('accountant.new-user.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Login ID</label>
                <input type="text" name="login_id" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password2" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department - Position</label>
                <select name="position" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="">-- Select --</option>
                    @foreach(['Finance-FManager','Finance-Accountant','Finance-Cashier','Finance-RecoveryExec','Service-SerAdvisor','Service-SManager','Service-JobController','Parts-PManager','Parts-DataOperator','IT-IT Manager','SalesVehicle-SVManager','SalesVehicle-SVExec'] as $opt)
                    <option value="{{ $opt }}">{{ str_replace('-',' – ',$opt) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                <input type="file" name="fileup" accept=".jpg,.png"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                <p class="text-xs text-gray-400 mt-1">Max 2MB, JPG/PNG only, max 900×800px</p>
            </div>
        </div>
        <button type="submit" class="mt-6 px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-medium">
            Create User
        </button>
    </form>
</div>
@endsection
