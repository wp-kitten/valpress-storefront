@extends('valpress-storefront::layouts.storefront')

@section('storefront_header')
    @if(valpress_storefront_setting_bool('show_home_hero'))
    <section class="vs-hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="vs-hero-eyebrow">{{ __('Welcome') }}</span>
                    <h1 class="vs-hero-title">{{ App\Models\Setting::get('site_title', 'ValPress') }}</h1>
                    <p class="vs-hero-lead">
                        {{ App\Models\Setting::get('site_description', __('Discover quality products with a seamless shopping experience.')) }}
                    </p>
                    @if(valpress_storefront_shop_available())
                        <div class="vs-hero-actions">
                            <a href="{{ valpress_storefront_catalog_url() }}" class="btn btn-light btn-lg vs-btn-light">
                                <i class="bi bi-bag me-1"></i>{{ __('Shop now') }}
                            </a>
                            @if(valpress_storefront_setting_bool('show_hero_blog_button') && Route::has('blog'))
                                <a href="{{ route('blog') }}" class="btn btn-outline-light btn-lg vs-btn-ghost">{{ __('Read blog') }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif
@endsection

@section('storefront_content')
    @if(valpress_storefront_setting_bool('show_home_hero'))
        @include('valpress-storefront::partials.home-features')
    @endif

    @if(valpress_storefront_shop_available())
        @php($featured = valpress_storefront_featured_products())

        @include('valpress-storefront::partials.home-categories')

        @if($featured->isNotEmpty())
            @include('valpress-storefront::partials.category-nav')

            <section class="vs-section">
                <div class="container">
                    <div class="vs-section-header">
                        <div>
                            <h2 class="vs-section-title">{{ __('Featured products') }}</h2>
                            <p class="vs-section-subtitle">{{ __('Hand-picked items from our catalog.') }}</p>
                        </div>
                        <a href="{{ valpress_storefront_catalog_url() }}" class="btn btn-outline-primary">{{ __('View all') }}</a>
                    </div>
                    @include('valpress-shop::storefront.shop.partials.product-grid', ['products' => $featured])
                </div>
            </section>
        @endif

        @include('valpress-storefront::partials.home-cta')
    @endif

    @if(!empty($post) && !empty(get_the_content($post)))
        <section class="vs-section @if(valpress_storefront_shop_available()) pt-0 @endif">
            <div class="container">
                <div class="vs-page-content">
                    {!! apply_filters('the_content', $post->post_content) !!}
                </div>
            </div>
        </section>
    @endif
@endsection
