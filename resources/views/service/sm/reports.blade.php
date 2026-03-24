{{-- Redirect to new reports index --}}
@extends('layouts.master')
@section('title','SM Reports')
@section('sidebar-menu')@include('service.sm.partials.sidebar')@endsection
@section('content')
@include('service.sm.reports.index')
@endsection
