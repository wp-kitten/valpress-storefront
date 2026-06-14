@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    @include('valpress-storefront::partials.shop-catalog', [
        'title' => $post->post_title ?? __('valpress-shop::messages.shop'),
        'lead' => $post->post_excerpt ?? null,
        'products' => $products,
        'post' => $post,
    ])
@endsection
