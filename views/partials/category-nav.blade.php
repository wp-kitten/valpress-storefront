@php
    $categories = valpress_storefront_shop_categories();
    $activeSlug = isset($category) ? $category->slug : null;
@endphp

@if($categories->isNotEmpty())
    <nav class="vs-category-nav" aria-label="{{ __('Product categories') }}">
        <div class="container">
            <div class="vs-category-nav-inner">
                <a href="{{ valpress_storefront_catalog_url() }}" class="vs-category-pill @if(!$activeSlug && !request()->routeIs('shop.category')) is-active @endif">
                    {{ __('All products') }}
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('shop.category', $cat) }}" class="vs-category-pill @if($activeSlug === $cat->slug) is-active @endif">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>
@endif
