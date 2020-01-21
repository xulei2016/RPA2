@extends('index.Mediator.layout')

@section('component')

    <{{$component}} readonly="1" data="{{json_encode($data)}}"></{{$component}}>

@endsection

