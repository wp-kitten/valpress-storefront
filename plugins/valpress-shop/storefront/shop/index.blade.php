@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop vs-shop">
        @include('valpress-storefront::partials.shop-page-header', ['products' => $products, 'category' => $category ?? null])

        @include('valpress-shop::storefront.shop.partials.product-grid', ['products' => $products])
    </div>
@endsection
