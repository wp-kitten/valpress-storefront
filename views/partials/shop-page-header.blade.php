@php
    $isCategory = isset($category);
    $pageTitle = $isCategory
        ? $category->name
        : ($page->post_title ?? __('valpress-shop::messages.shop'));
    $pageLead = $isCategory && !empty($category->description)
        ? $category->description
        : (!empty($page?->post_excerpt) ? $page->post_excerpt : null);
    $productCount = (isset($products) && method_exists($products, 'total')) ? (int) $products->total() : null;
@endphp

@include('valpress-storefront::partials.category-nav')

<div class="vs-shop-toolbar mt-2 mb-4">
    <div class="vs-shop-toolbar-main">
        @if($isCategory)
            @include('valpress-storefront::partials.shop-breadcrumb', [
                'items' => [
                    ['label' => __('Shop'), 'url' => valpress_storefront_catalog_url()],
                    ['label' => $category->name, 'url' => ''],
                ],
            ])
        @endif

        <h1 class="h2 mt-4 mb-1">{{ $pageTitle }}</h1>

        @if($pageLead)
            <p class="text-muted mb-0">{{ $pageLead }}</p>
        @elseif($isCategory)
            <p class="text-muted mb-0">{{ __('Browse products in :category.', ['category' => $category->name]) }}</p>
        @else
            <p class="text-muted mb-0">{{ __('Browse our full product catalog.') }}</p>
        @endif

        @if($productCount !== null)
            <p class="vs-shop-result-count small text-muted mb-0 mt-1">
                {{ trans_choice(':count product|:count products', $productCount, ['count' => $productCount]) }}
            </p>
        @endif
    </div>

    @if(Route::has('cart.index'))
        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-bag me-1"></i>{{ __('valpress-shop::messages.view_cart') }}
        </a>
    @endif
</div>
