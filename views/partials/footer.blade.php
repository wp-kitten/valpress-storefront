<footer class="vs-footer">
    <div class="container">
        <div class="row g-4 py-5">
            <div class="col-lg-4">
                <div class="vs-footer-brand">{{ App\Models\Setting::get('site_title', 'ValPress') }}</div>
                <p class="vs-footer-text">{{ App\Models\Setting::get('site_description', '') }}</p>
            </div>
            @if(valpress_storefront_shop_available() || valpress_storefront_has_nav_menu('storefront_store'))
                <div class="col-6 col-lg-2">
                    <h6 class="vs-footer-heading">{{ __('Store') }}</h6>
                    {!! valpress_storefront_render_footer_menu('storefront_store', 'valpress-storefront::partials.footer-store-fallback') !!}
                </div>
            @endif
            <div class="col-6 col-lg-2">
                <h6 class="vs-footer-heading">{{ __('Site') }}</h6>
                {!! valpress_storefront_render_footer_menu('footer', 'valpress-storefront::partials.footer-site-fallback') !!}
            </div>
            @if(valpress_storefront_shop_available())
                <div class="col-lg-4">
                    <h6 class="vs-footer-heading">{{ __('Categories') }}</h6>
                    <ul class="vs-footer-links vs-footer-links-columns">
                        @foreach(valpress_storefront_shop_categories()->take((int) valpress_storefront_setting('footer_categories_count', 6)) as $cat)
                            @if(Route::has('shop.category'))
                                <li><a href="{{ route('shop.category', $cat) }}">{{ $cat->name }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="vs-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ App\Models\Setting::get('site_title', 'ValPress') }}. {{ __('All rights reserved.') }}</span>
            <span class="vs-footer-powered">{{ __('Powered by ValPress') }}</span>
        </div>
    </div>
</footer>
