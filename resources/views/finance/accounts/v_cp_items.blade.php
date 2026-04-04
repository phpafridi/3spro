@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Cash Payment Voucher — Items')
@section('content')
@php
$voucherConfig = [
    'type'        => 'CPV',
    'label'       => 'Cash Payment Voucher',
    'color'       => 'red',
    'debitLabel'  => 'Expense GSL (Debit)',
    'autoGslCode' => 2001000,
    'autoGslName' => 'Cash in Hand',
    'autoSide'    => 'credit',
    'itemsRoute'  => 'accounts.cpv.items',
    'submitRoute' => 'accounts.cpv',
];
@endphp
@include('finance.accounts._voucher_items')
@endsection
