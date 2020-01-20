@extends('index.Mediator.layout')

@section('component')

    <id-card data="{{ json_encode($data, true) }}"></id-card>

@endsection