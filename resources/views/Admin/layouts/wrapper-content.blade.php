@extends('admin.layouts.index')

@section('wrapper-content')

    <div id="pjax-container">
        <section class="content" id="pjax-container">
            {{-- wrapper --}}
            @include('admin.layouts.wrapper')

            {{-- content --}}
            @yield('content')

        </section>
    </div>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
        <i class="fa fa-chevron-up"></i>
    </a>

@endsection