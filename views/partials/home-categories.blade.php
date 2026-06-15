@php
    $categories = valpress_storefront_shop_categories()->take(6);
@endphp

@if($categories->isNotEmpty() && Route::has('shop.category'))
<section class="vs-section">
    <div class="container">
        <div class="vs-section-header">
            <div>
                <h2 class="vs-section-title">{{ __('Shop by category') }}</h2>
                <p class="vs-section-subtitle">{{ __('Find exactly what you are looking for.') }}</p>
            </div>
            <a href="{{ valpress_storefront_catalog_url() }}" class="btn btn-outline-primary">{{ __('View all') }}</a>
        </div>
        <div class="vs-category-cards">
            @foreach($categories as $cat)
                <a href="{{ route('shop.category', $cat) }}" class="vs-category-card">
                    <span class="vs-category-card-icon" aria-hidden="true">
                        <i class="bi bi-tag"></i>
                    </span>
                    <span class="vs-category-card-name">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
