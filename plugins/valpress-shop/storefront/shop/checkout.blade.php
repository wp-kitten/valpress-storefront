@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    @if(!empty($stripePublishableKey))
        <meta name="vps-stripe-key" content="{{ $stripePublishableKey }}">
        <script src="https://js.stripe.com/v3/"></script>
    @endif

    <div class="vps-shop">
        <div class="vps-shop-header">
            <h1 class="h2 mb-0">{{ __('valpress-shop::messages.checkout') }}</h1>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">{{ __('valpress-shop::messages.back_to_cart') }}</a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf
                    <input type="hidden" name="idempotency_key" value="{{ old('idempotency_key', $idempotencyKey) }}">

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h2 class="h5">{{ __('valpress-shop::messages.contact_details') }}</h2>
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
                    </div>

                    @foreach(['billing_address' => 'billing_address', 'shipping_address' => 'shipping_address'] as $field => $labelKey)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h2 class="h5">{{ __('valpress-shop::messages.' . $labelKey) }}</h2>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('valpress-shop::messages.address_line1') }}</label>
                                    <input type="text" name="{{ $field }}[line1]" class="form-control @error($field . '.line1') is-invalid @enderror" value="{{ old($field . '.line1') }}" required>
                                    @error($field . '.line1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('valpress-shop::messages.address_line2') }}</label>
                                    <input type="text" name="{{ $field }}[line2]" class="form-control" value="{{ old($field . '.line2') }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('valpress-shop::messages.city') }}</label>
                                        <input type="text" name="{{ $field }}[city]" class="form-control @error($field . '.city') is-invalid @enderror" value="{{ old($field . '.city') }}" required>
                                        @error($field . '.city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('valpress-shop::messages.state') }}</label>
                                        <input type="text" name="{{ $field }}[state]" class="form-control" value="{{ old($field . '.state') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('valpress-shop::messages.postal_code') }}</label>
                                        <input type="text" name="{{ $field }}[postal_code]" class="form-control @error($field . '.postal_code') is-invalid @enderror" value="{{ old($field . '.postal_code') }}" required>
                                        @error($field . '.postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('valpress-shop::messages.country') }}</label>
                                        <input type="text" name="{{ $field }}[country]" class="form-control @error($field . '.country') is-invalid @enderror" value="{{ old($field . '.country', 'US') }}" maxlength="2" required>
                                        @error($field . '.country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h2 class="h5">{{ __('valpress-shop::messages.payment_method') }}</h2>
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
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">{{ __('valpress-shop::messages.place_order') }}</button>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="vps-checkout-summary">
                    <h2 class="h5">{{ __('valpress-shop::messages.order_summary') }}</h2>
                    <ul class="list-unstyled mb-3">
                        @foreach($cart->items as $item)
                            <li class="d-flex justify-content-between mb-2">
                                <span>{{ $item->variant?->product?->name }} × {{ $item->quantity }}</span>
                                <span>{{ \Plugins\ValPressShop\Support\Money::format((float)$item->unit_price_snapshot * (int)$item->quantity) }}</span>
                            </li>
                        @endforeach
                    </ul>
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
                        <dt class="col-8"><strong>{{ __('valpress-shop::messages.total') }}</strong></dt>
                        <dd class="col-4 text-end"><strong>{{ \Plugins\ValPressShop\Support\Money::format($totals['grand_total']) }}</strong></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
