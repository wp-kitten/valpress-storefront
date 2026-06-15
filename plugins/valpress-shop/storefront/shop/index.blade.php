@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop vs-shop">
        @if(empty($category))
            <div class="vs-shop-hero">
                <div class="vs-shop-hero-inner">
                    <h1>{{ __('valpress-shop::messages.shop') }}</h1>
                    <p>{{ __('Browse our full product catalog and find something you will love.') }}</p>
                </div>
            </div>
        @endif

        @include('valpress-storefront::partials.shop-page-header', ['products' => $products, 'category' => $category ?? null])

        @include('valpress-shop::storefront.shop.partials.product-grid', ['products' => $products])
    </div>
@endsection
