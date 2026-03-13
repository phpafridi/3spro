{{-- resources/views/parts/layout.blade.php --}}
{{-- All Parts views extend this instead of layouts.master directly --}}
@extends('layouts.master')

@section('sidebar-menu')
    @include('parts.entry.sidebar')
@endsection
