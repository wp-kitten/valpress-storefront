@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop vs-shop">
        <div class="vs-shop-toolbar">
            <h1 class="h2 mb-0">{{ __('valpress-shop::messages.cart') }}</h1>
            <a href="{{ valpress_storefront_catalog_url() }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>{{ __('valpress-shop::messages.continue_shopping') }}
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($cart->items->isEmpty())
            <div class="vs-cart-empty">
                <i class="bi bi-bag-x display-4 text-muted d-block mb-3"></i>
                <h2 class="h4 fw-bold mb-2">{{ __('Your cart is empty') }}</h2>
                <p class="text-muted mb-4">{{ __('valpress-shop::messages.cart_empty') }}</p>
                <a href="{{ valpress_storefront_catalog_url() }}" class="btn btn-primary btn-lg">{{ __('valpress-shop::messages.continue_shopping') }}</a>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="vs-cart-items">
                        @foreach($cart->items as $item)
                            @php
                                $lineTotal = (float) $item->unit_price_snapshot * (int) $item->quantity;
                                $product = $item->variant?->product;
                                $imageUrl = $product?->imageUrl();
                            @endphp
                            <article class="vs-cart-item">
                                <div class="vs-cart-item-thumb">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="vs-cart-item-name">{{ $product?->name ?? __('valpress-shop::messages.product') }}</p>
                                    @if($item->variant && $product?->isVariable())
                                        <p class="vs-cart-item-variant">{{ $item->variant->displayName() }}</p>
                                    @endif
                                    <p class="vs-cart-item-price mb-2">{{ \Plugins\ValPressShop\Support\Money::format($item->unit_price_snapshot) }}</p>
                                    <div class="vs-cart-item-actions">
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex gap-2 align-items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" class="form-control form-control-sm" style="width:4.5rem;" value="{{ $item->quantity }}" min="0" max="99" aria-label="{{ __('valpress-shop::messages.qty') }}">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('valpress-shop::messages.update') }}</button>
                                        </form>
                                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="{{ __('valpress-shop::messages.remove') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="vs-cart-item-price d-none d-md-block">
                                    {{ \Plugins\ValPressShop\Support\Money::format($lineTotal) }}
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="vps-checkout-summary vs-checkout-summary-sticky">
                        <h2 class="h5 mb-3">{{ __('valpress-shop::messages.order_summary') }}</h2>

                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="mb-3">
                            @csrf
                            <label class="form-label small fw-semibold" for="coupon_code">{{ __('valpress-shop::messages.code') }}</label>
                            <div class="input-group">
                                <input type="text" name="code" id="coupon_code" class="form-control" value="{{ $cart->coupon_code }}" placeholder="{{ __('valpress-shop::messages.apply_coupon') }}">
                                <button type="submit" class="btn btn-outline-secondary">{{ __('valpress-shop::messages.apply_coupon') }}</button>
                            </div>
                        </form>

                        @if($cart->coupon_code)
                            <form action="{{ route('cart.coupon.remove') }}" method="POST" class="mb-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link px-0">{{ __('valpress-shop::messages.remove_coupon') }} ({{ $cart->coupon_code }})</button>
                            </form>
                        @endif

                        <dl class="row mb-3">
                            <dt class="col-7">{{ __('valpress-shop::messages.subtotal') }}</dt>
                            <dd class="col-5 text-end">{{ \Plugins\ValPressShop\Support\Money::format($totals['subtotal']) }}</dd>
                            @if(($totals['discount'] ?? 0) > 0)
                                <dt class="col-7">{{ __('valpress-shop::messages.discount') }}</dt>
                                <dd class="col-5 text-end">−{{ \Plugins\ValPressShop\Support\Money::format($totals['discount']) }}</dd>
                            @endif
                            <dt class="col-7">{{ __('valpress-shop::messages.shipping') }}</dt>
                            <dd class="col-5 text-end">{{ \Plugins\ValPressShop\Support\Money::format($totals['shipping']) }}</dd>
                            <dt class="col-7">{{ __('valpress-shop::messages.tax') }}</dt>
                            <dd class="col-5 text-end">{{ \Plugins\ValPressShop\Support\Money::format($totals['tax']) }}</dd>
                            <dt class="col-7 fw-bold">{{ __('valpress-shop::messages.total') }}</dt>
                            <dd class="col-5 text-end fw-bold fs-5">{{ \Plugins\ValPressShop\Support\Money::format($totals['grand_total']) }}</dd>
                        </dl>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 btn-lg">{{ __('valpress-shop::messages.proceed_to_checkout') }}</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
