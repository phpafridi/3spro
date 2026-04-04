@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Cash Receipt Voucher — Items')
@section('content')
@php
$voucherConfig = [
    'type'        => 'CRV',
    'label'       => 'Cash Receipt Voucher',
    'color'       => 'blue',
    'debitLabel'  => 'Income GSL (Credit)',
    'autoGslCode' => 2001000,
    'autoGslName' => 'Cash in Hand',
    'autoSide'    => 'debit',
    'itemsRoute'  => 'accounts.crv.items',
    'submitRoute' => 'accounts.crv',
];
@endphp
@include('finance.accounts._voucher_items')
@endsection
