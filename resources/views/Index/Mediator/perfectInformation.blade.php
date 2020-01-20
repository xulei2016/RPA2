@extends('index.Mediator.layout')

@section('component')

    <perfect-information data="{{ json_encode($data, true) }}"></perfect-information>

@endsection