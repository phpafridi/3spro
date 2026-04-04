@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Journal Voucher — Items')
@section('content')
@php
$voucherConfig = [
    'type'        => 'JV',
    'label'       => 'Journal Voucher',
    'color'       => 'gray',
    'debitLabel'  => 'GSL (Debit/Credit)',
    'autoGslCode' => 0,
    'autoGslName' => 'N/A',
    'autoSide'    => 'none',
    'itemsRoute'  => 'accounts.jv.items',
    'submitRoute' => 'accounts.jv',
];
@endphp
@include('finance.accounts._voucher_items')
@endsection
