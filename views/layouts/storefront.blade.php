@extends('layouts.frontend')

@section('valpress_head')
    {!! valpress_storefront_favicon() !!}
    @stack('storefront_head')
@endsection

@section('valpress_header')
    @include('valpress-storefront::inc.navbar')
    @yield('storefront_header')
@endsection

@section('content')
    <main class="vs-main" id="main-content">
        @yield('storefront_content')
    </main>
@endsection

@section('valpress_footer_content')
    @include('valpress-storefront::partials.footer')
@endsection
