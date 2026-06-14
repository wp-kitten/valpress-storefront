<footer class="vs-footer">
    <div class="container">
        <div class="row g-4 py-5">
            <div class="col-lg-4">
                <div class="vs-footer-brand">{{ App\Models\Setting::get('site_title', 'ValPress') }}</div>
                <p class="vs-footer-text">{{ App\Models\Setting::get('site_description', '') }}</p>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="vs-footer-heading">{{ __('Store') }}</h6>
                <ul class="vs-footer-links">
                    @if(valpress_storefront_shop_available())
                        <li><a href="{{ valpress_storefront_catalog_url() }}">{{ __('Shop') }}</a></li>
                        <li><a href="{{ route('cart.index') }}">{{ __('valpress-shop::messages.cart') }}</a></li>
                        @auth
                            @if(Route::has('shop.account.index'))
                                <li><a href="{{ route('shop.account.index') }}">{{ __('valpress-shop::messages.my_account') }}</a></li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="vs-footer-heading">{{ __('Site') }}</h6>
                <ul class="vs-footer-links">
                    <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    @if(Route::has('blog'))
                        <li><a href="{{ route('blog') }}">{{ __('Blog') }}</a></li>
                    @endif
                </ul>
            </div>
            @if(valpress_storefront_shop_available())
                <div class="col-lg-4">
                    <h6 class="vs-footer-heading">{{ __('Categories') }}</h6>
                    <ul class="vs-footer-links vs-footer-links-columns">
                        @foreach(valpress_storefront_shop_categories()->take((int) valpress_storefront_setting('footer_categories_count', 6)) as $cat)
                            <li><a href="{{ route('shop.category', $cat) }}">{{ $cat->name }}</a></li>
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
