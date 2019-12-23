@extends('index.Mediator.layout')

@section('component')

    <login data="{{json_encode($list)}}"></login>

@endsection