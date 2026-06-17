<ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <li class="nav-item">
        <a class="nav-link @if(is_home()) active @endif" href="{{ route('home') }}">{{ __('Home') }}</a>
    </li>
    @if(valpress_storefront_shop_available())
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('shop.*') && !request()->routeIs('shop.account.*')) active @endif" href="{{ valpress_storefront_catalog_url() }}">{{ __('Shop') }}</a>
        </li>
    @endif
    @if(Route::has('blog'))
        <li class="nav-item">
            <a class="nav-link @if(is_blog()) active @endif" href="{{ route('blog') }}">{{ __('Blog') }}</a>
        </li>
    @endif
</ul>
