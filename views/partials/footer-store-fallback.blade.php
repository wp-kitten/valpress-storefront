<ul class="vs-footer-links">
    @if(valpress_storefront_shop_available())
        <li><a href="{{ valpress_storefront_catalog_url() }}">{{ __('Shop') }}</a></li>
        @if(Route::has('cart.index'))
            <li><a href="{{ route('cart.index') }}">{{ __('valpress-shop::messages.cart') }}</a></li>
        @endif
        @auth
            @if(Route::has('shop.account.index'))
                <li><a href="{{ route('shop.account.index') }}">{{ __('valpress-shop::messages.my_account') }}</a></li>
            @endif
        @endauth
    @endif
</ul>
