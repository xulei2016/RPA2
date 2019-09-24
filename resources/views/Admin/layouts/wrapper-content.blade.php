@extends('admin.layouts.index')

@section('wrapper-content')
 
    <section class="content" id="pjax-container">

            {{-- content --}}
            @yield('content')
        
    </section>

@endsection