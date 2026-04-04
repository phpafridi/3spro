@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Bank Receipt Voucher — Items')
@section('content')
@php
$voucherConfig = [
    'type'        => 'BRV',
    'label'       => 'Bank Receipt Voucher',
    'color'       => 'green',
    'debitLabel'  => 'Income GSL (Credit)',
    'autoGslCode' => 2002000,
    'autoGslName' => 'Bank Account',
    'autoSide'    => 'debit',
    'itemsRoute'  => 'accounts.brv.items',
    'submitRoute' => 'accounts.brv',
];
@endphp
@include('finance.accounts._voucher_items')
@endsection
