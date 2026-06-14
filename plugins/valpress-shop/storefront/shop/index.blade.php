@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop">
        <div class="vps-shop-header">
            <div>
                <h1 class="h2 mb-1">{{ isset($category) ? $category->name : ($page->post_title ?? __('valpress-shop::messages.shop')) }}</h1>
                @if(isset($category) && $category->description)
                    <p class="text-muted mb-0">{{ $category->description }}</p>
                @elseif(!empty($page?->post_excerpt))
                    <p class="text-muted mb-0">{{ $page->post_excerpt }}</p>
                @endif
            </div>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">{{ __('valpress-shop::messages.view_cart') }}</a>
        </div>

        @include('valpress-shop::storefront.shop.partials.product-grid', ['products' => $products])
    </div>
@endsection
