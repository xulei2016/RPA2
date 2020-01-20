@extends('index.Mediator.layout')

@section('component')

    <result back="{{ implode(',', $backList) }}" status="{{ $status }}"></result>

@endsection