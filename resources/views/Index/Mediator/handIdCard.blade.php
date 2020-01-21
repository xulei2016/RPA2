@extends('index.Mediator.layout')

@section('component')

    <hand-id-card data="{{ json_encode($data, true) }}"></hand-id-card>

@endsection