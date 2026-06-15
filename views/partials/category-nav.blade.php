@php
    $categories = valpress_storefront_shop_categories();
    $activeSlug = isset($category)
        ? $category->slug
        : (isset($product) && $product->categories->isNotEmpty()
            ? $product->categories->first()->slug
            : null);
@endphp

@if($categories->isNotEmpty())
    <nav class="vs-category-nav" aria-label="{{ __('Product categories') }}">
        <div class="container">
            <div class="vs-category-nav-inner">
                <a href="{{ valpress_storefront_catalog_url() }}" class="vs-category-pill @if(!$activeSlug && !request()->routeIs('shop.category')) is-active @endif">
                    {{ __('All products') }}
                </a>
                @foreach($categories as $cat)
                    @if(Route::has('shop.category'))
                        <a href="{{ route('shop.category', $cat) }}" class="vs-category-pill @if($activeSlug === $cat->slug) is-active @endif">
                            {{ $cat->name }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </nav>
@endif
