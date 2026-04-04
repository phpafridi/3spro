@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Bank Payment Voucher — Items')
@section('content')
@php
$voucherConfig = [
    'type'        => 'BPV',
    'label'       => 'Bank Payment Voucher',
    'color'       => 'purple',
    'debitLabel'  => 'Expense GSL (Debit)',
    'autoGslCode' => 2002000,
    'autoGslName' => 'Bank Account',
    'autoSide'    => 'credit',
    'itemsRoute'  => 'accounts.bpv.items',
    'submitRoute' => 'accounts.bpv',
];
@endphp
@include('finance.accounts._voucher_items')
@endsection
