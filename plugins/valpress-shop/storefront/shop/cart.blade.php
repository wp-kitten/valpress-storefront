@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop vs-shop">
        <div class="vs-shop-toolbar">
            <h1 class="h2 mb-0">{{ __('valpress-shop::messages.cart') }}</h1>
            <a href="{{ valpress_storefront_catalog_url() }}" class="btn btn-outline-secondary">
                {{ __('valpress-shop::messages.continue_shopping') }}
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($cart->items->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-bag-x fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted mb-3">{{ __('valpress-shop::messages.cart_empty') }}</p>
                <a href="{{ valpress_storefront_catalog_url() }}" class="btn btn-primary">{{ __('valpress-shop::messages.continue_shopping') }}</a>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table vps-cart-table align-middle">
                            <thead>
                                <tr>
                                    <th>{{ __('valpress-shop::messages.product') }}</th>
                                    <th>{{ __('valpress-shop::messages.unit_price') }}</th>
                                    <th>{{ __('valpress-shop::messages.qty') }}</th>
                                    <th>{{ __('valpress-shop::messages.total') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->items as $item)
                                    @php $lineTotal = (float)$item->unit_price_snapshot * (int)$item->quantity; @endphp
                                    <tr>
                                        <td class="fw-medium">{{ $item->variant?->product?->name ?? __('valpress-shop::messages.product') }}</td>
                                        <td>{{ \Plugins\ValPressShop\Support\Money::format($item->unit_price_snapshot) }}</td>
                                        <td style="min-width: 140px;">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" class="form-control form-control-sm" value="{{ $item->quantity }}" min="0" max="99">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('valpress-shop::messages.update') }}</button>
                                            </form>
                                        </td>
                                        <td class="fw-semibold">{{ \Plugins\ValPressShop\Support\Money::format($lineTotal) }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="{{ __('valpress-shop::messages.remove') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="vps-checkout-summary">
                        <h2 class="h5 mb-3">{{ __('valpress-shop::messages.order_summary') }}</h2>

                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="mb-3">
                            @csrf
                            <label class="form-label" for="coupon_code">{{ __('valpress-shop::messages.code') }}</label>
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
                            <dd class="col-5 text-end fw-bold">{{ \Plugins\ValPressShop\Support\Money::format($totals['grand_total']) }}</dd>
                        </dl>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 btn-lg">{{ __('valpress-shop::messages.proceed_to_checkout') }}</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
