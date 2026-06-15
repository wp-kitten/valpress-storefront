@php
    $title = $title ?? ($category->name ?? ($page->post_title ?? ($post->post_title ?? __('valpress-shop::messages.shop'))));
    $lead = $lead ?? ($category->description ?? ($page->post_excerpt ?? ($post->post_excerpt ?? __('valpress-shop::messages.shop_archive_intro'))));
    $eyebrow = $eyebrow ?? ($category->name ?? __('Shop catalog'));
@endphp

<section class="vs-hero vs-hero-compact">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="vs-hero-eyebrow">{{ $eyebrow }}</span>
                <h1 class="vs-hero-title">{{ $title }}</h1>
                @if($lead)
                    <p class="vs-hero-lead mb-0">{{ $lead }}</p>
                @endif
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="vs-hero-actions justify-content-lg-end">
                    <a href="{{ route('cart.index') }}" class="btn btn-light btn-lg vs-btn-light">
                        <i class="bi bi-bag me-1"></i> {{ __('valpress-shop::messages.view_cart') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('valpress-storefront::partials.category-nav')

<section class="vs-section">
    <div class="container">
        @include('valpress-shop::storefront.shop.partials.product-grid', ['products' => $products])
    </div>
</section>
