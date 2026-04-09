@extends('layouts.admin')

@if(isset($header))
    @section('header')
        {{ $header }}
    @endsection
@endif

@section('content')
    {{ $slot }}
@endsection
