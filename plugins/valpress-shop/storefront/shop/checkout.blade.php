@extends('valpress-storefront::layouts.storefront')

@push('storefront_head')
    @if(!empty($stripePublishableKey))
        <meta name="vps-stripe-key" content="{{ $stripePublishableKey }}">
        <script src="https://js.stripe.com/v3/"></script>
    @endif
@endpush

@section('storefront_content')
    <div class="vps-shop vs-shop">
        <div class="vs-shop-toolbar">
            <h1 class="h2 mb-0">{{ __('valpress-shop::messages.checkout') }}</h1>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>{{ __('valpress-shop::messages.back_to_cart') }}
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf
                    <input type="hidden" name="idempotency_key" value="{{ old('idempotency_key', $idempotencyKey) }}">

                    <div class="vs-checkout-card">
                        <h2 class="vs-checkout-card-title">
                            <span class="vs-step-num">1</span>
                            {{ __('valpress-shop::messages.contact_details') }}
                        </h2>
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">{{ __('valpress-shop::messages.email') }}</label>
                            <input type="email" name="customer_email" id="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email') }}" required>
                            @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">{{ __('valpress-shop::messages.full_name') }}</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}">
                        </div>
                        <div class="mb-0">
                            <label for="customer_note" class="form-label">{{ __('valpress-shop::messages.order_note') }}</label>
                            <textarea name="customer_note" id="customer_note" class="form-control" rows="3">{{ old('customer_note') }}</textarea>
                        </div>
                    </div>

                    <div class="vs-checkout-card">
                        <h2 class="vs-checkout-card-title">
                            <span class="vs-step-num">2</span>
                            {{ __('valpress-shop::messages.billing_address') }}
                        </h2>
                        @include('valpress-storefront::partials.checkout-address-fields', ['field' => 'billing_address'])
                    </div>

					<div class="vs-checkout-card" id="vs-shipping-section">
						<h2 class="vs-checkout-card-title">
							<span class="vs-step-num">3</span>
							{{ __('valpress-shop::messages.shipping_address') }}
						</h2>
						<div class="form-check vs-same-as-billing">
							<input class="form-check-input" type="checkbox" id="vs-same-as-billing" value="1" checked>
							<label class="form-check-label" for="vs-same-as-billing">{{ __('Same as billing address') }}</label>
						</div>
						<div id="vs-shipping-fields" class="d-none">
							@include('valpress-storefront::partials.checkout-address-fields', ['field' => 'shipping_address'])
						</div>
						<div id="vs-shipping-mirror-fields" aria-hidden="true">
							<input type="hidden" name="shipping_address[line1]" data-mirror="billing_address[line1]">
							<input type="hidden" name="shipping_address[line2]" data-mirror="billing_address[line2]">
							<input type="hidden" name="shipping_address[city]" data-mirror="billing_address[city]">
							<input type="hidden" name="shipping_address[state]" data-mirror="billing_address[state]">
							<input type="hidden" name="shipping_address[postal_code]" data-mirror="billing_address[postal_code]">
							<input type="hidden" name="shipping_address[country]" data-mirror="billing_address[country]">
						</div>
					</div>

                    <div class="vs-checkout-card">
                        <h2 class="vs-checkout-card-title">
                            <span class="vs-step-num">4</span>
                            {{ __('valpress-shop::messages.payment_method') }}
                        </h2>
                        @foreach($gateways as $id => $gateway)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_gateway" id="gateway_{{ $id }}" value="{{ $id }}"
                                    @checked(old('payment_gateway', array_key_first($gateways)) === $id) required>
                                <label class="form-check-label" for="gateway_{{ $id }}">
                                    <strong>{{ $gateway['label'] ?? $id }}</strong>
                                    @if(!empty($gateway['description']))
                                        <div class="small text-muted">{{ $gateway['description'] }}</div>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                        @error('payment_gateway')<div class="text-danger small">{{ $message }}</div>@enderror

                        @if(!empty($stripePublishableKey) && isset($gateways['stripe']))
                            <div id="vps-stripe-payment-element" class="mt-3" style="display:none;"></div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 w-md-auto">
                        <i class="bi bi-lock me-1"></i>{{ __('valpress-shop::messages.place_order') }}
                    </button>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="vps-checkout-summary vs-checkout-summary-sticky">
                    <h2 class="h5 mb-3">{{ __('valpress-shop::messages.order_summary') }}</h2>
                    <div class="mb-3">
                        @foreach($cart->items as $item)
                            @php
                                $product = $item->variant?->product;
                                $imageUrl = $product?->imageUrl();
                            @endphp
                            <div class="vs-checkout-line-item">
                                <div class="vs-checkout-line-thumb">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="">
                                    @endif
                                </div>
                                <div class="vs-checkout-line-meta">
                                    <p class="vs-checkout-line-name">{{ $product?->name ?? __('valpress-shop::messages.product') }}</p>
                                    <span class="vs-checkout-line-qty">{{ __('Qty') }}: {{ $item->quantity }}</span>
                                </div>
                                <span class="vs-checkout-line-price">{{ \Plugins\ValPressShop\Support\Money::format((float) $item->unit_price_snapshot * (int) $item->quantity) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-8">{{ __('valpress-shop::messages.subtotal') }}</dt>
                        <dd class="col-4 text-end">{{ \Plugins\ValPressShop\Support\Money::format($totals['subtotal']) }}</dd>
                        @if(($totals['discount'] ?? 0) > 0)
                            <dt class="col-8">{{ __('valpress-shop::messages.discount') }}</dt>
                            <dd class="col-4 text-end">−{{ \Plugins\ValPressShop\Support\Money::format($totals['discount']) }}</dd>
                        @endif
                        <dt class="col-8">{{ __('valpress-shop::messages.shipping') }}</dt>
                        <dd class="col-4 text-end">{{ \Plugins\ValPressShop\Support\Money::format($totals['shipping']) }}</dd>
                        <dt class="col-8">{{ __('valpress-shop::messages.tax') }}</dt>
                        <dd class="col-4 text-end">{{ \Plugins\ValPressShop\Support\Money::format($totals['tax']) }}</dd>
                        <dt class="col-8 pt-2 border-top"><strong>{{ __('valpress-shop::messages.total') }}</strong></dt>
                        <dd class="col-4 text-end pt-2 border-top"><strong class="fs-5">{{ \Plugins\ValPressShop\Support\Money::format($totals['grand_total']) }}</strong></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
