@extends('layouts.supply')

@if(isset($header))
    @section('header')
        {{ $header }}
    @endsection
@endif

@section('content')
    {{ $slot }}
@endsection
