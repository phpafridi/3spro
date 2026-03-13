@extends('layouts.master')
@section('title', 'Status Consumable')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="x_panel">
    <div class="x_title"><h2>{{ ucwords(str_replace('-', ' ', 'status-consumable')) }}</h2><div class="clearfix"></div></div>
    <div class="x_content"><p>This page is under construction.</p></div>
</div>
@endsection
