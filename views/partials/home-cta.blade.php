@if(valpress_storefront_shop_available())
<section class="vs-section">
    <div class="container">
        <div class="vs-cta-banner">
            <div>
                <h2>{{ __('Ready to find your next favorite?') }}</h2>
                <p>{{ __('Explore our full catalog and enjoy a seamless shopping experience.') }}</p>
            </div>
            <a href="{{ valpress_storefront_catalog_url() }}" class="btn btn-light btn-lg vs-btn-light">{{ __('Browse catalog') }}</a>
        </div>
    </div>
</section>
@endif
