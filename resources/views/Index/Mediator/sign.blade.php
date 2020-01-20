@extends('index.Mediator.layout')

@section('component')

    <sign data="{{ json_encode($data, true) }}"></sign>

@endsection