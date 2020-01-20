@extends('index.Mediator.layout')

@section('component')

    <rate data="{{ json_encode($data, true) }}"></rate>

@endsection