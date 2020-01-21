@extends('index.Mediator.layout')

@section('component')

    @if($id == 1)
        <agreement-detail read="{{$read}}"></agreement-detail>
    @elseif($id == 2)
        <agreement-inform read="{{$read}}"></agreement-inform>
    @else
        <agreement-commitment read="{{$read}}"></agreement-commitment>
    @endif

@endsection
