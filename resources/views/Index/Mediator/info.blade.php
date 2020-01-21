@extends('index.Mediator.layout')

@section('component')

    <info data="{{json_encode($infoList)}}"></info>

@endsection
