@extends('admin.layouts.index')

@section('wrapper-content')
 
    <section class="content" id="pjax-container">
        {{-- wrapper --}}
        @include('admin.layouts.wrapper')

        {{-- content --}}
        @yield('content')
        
    </section>

@endsection