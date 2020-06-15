@extends('index.Mediator.layout')

@section('component')

    <confirm-rate rate="{{$rate}}" special="{{$isSpecial}}"></confirm-rate>

@endsection