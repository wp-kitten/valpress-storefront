@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <section class="vs-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="h2 mb-3">{{ $post->post_title }}</h1>
                    @if($post->post_excerpt)
                        <p class="lead text-muted">{{ $post->post_excerpt }}</p>
                    @endif
                    <div class="vs-page-content mt-4">
                        {!! apply_filters('the_content', $post->post_content) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
