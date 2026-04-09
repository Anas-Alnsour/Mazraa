@extends('layouts.driver')

@if(isset($header))
    @section('header')
        {{ $header }}
    @endsection
@endif

@section('content')
    {{ $slot }}
@endsection
