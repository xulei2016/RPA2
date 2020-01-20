@extends('index.Mediator.layout')

@section('component')

    <bank-card data="{{ json_encode($data, true) }}"></bank-card>

@endsection