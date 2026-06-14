@if(valpress_storefront_shop_available())
    <li class="nav-item">
        <a class="nav-link vs-cart-link @if(request()->routeIs('cart.*')) active @endif" href="{{ route('cart.index') }}">
            <i class="bi bi-bag" aria-hidden="true"></i>
            <span class="d-none d-md-inline">{{ __('valpress-shop::messages.cart') }}</span>
            @if($count > 0)
                <span class="vs-cart-badge">{{ $count }}</span>
            @endif
        </a>
    </li>
@endif
