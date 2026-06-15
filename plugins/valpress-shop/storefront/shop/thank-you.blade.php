@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop vs-shop vps-thank-you">
        <div class="vs-thank-you-icon" aria-hidden="true">
            <i class="bi bi-check-lg"></i>
        </div>
        <h1 class="h2 fw-bold">{{ __('valpress-shop::messages.thank_you') }}</h1>
        <p class="lead">{{ __('valpress-shop::messages.order_received', ['number' => $order->order_number]) }}</p>
        <p class="text-muted mb-4">{{ __('valpress-shop::messages.confirmation_sent', ['email' => $order->customer_email]) }}</p>

        <div class="vps-checkout-summary text-start mx-auto" style="max-width: 480px;">
            <dl class="row mb-0">
                <dt class="col-sm-5">{{ __('valpress-shop::messages.order_number') }}</dt>
                <dd class="col-sm-7 fw-semibold">{{ $order->order_number }}</dd>
                <dt class="col-sm-5">{{ __('valpress-shop::messages.total') }}</dt>
                <dd class="col-sm-7 fw-semibold">{{ \Plugins\ValPressShop\Support\Money::format($order->grand_total, $order->currency) }}</dd>
                <dt class="col-sm-5">{{ __('valpress-shop::messages.payment_status') }}</dt>
                <dd class="col-sm-7 text-capitalize">{{ $order->payment_status }}</dd>
            </dl>
        </div>

        <div class="mt-4 d-flex flex-wrap gap-2 justify-content-center">
            <a href="{{ valpress_storefront_catalog_url() }}" class="btn btn-primary btn-lg">{{ __('valpress-shop::messages.continue_shopping') }}</a>
            @auth
                @if(Route::has('shop.account.orders'))
                    <a href="{{ route('shop.account.orders') }}" class="btn btn-outline-primary btn-lg">{{ __('valpress-shop::messages.my_orders') }}</a>
                @endif
            @endauth
        </div>
    </div>
@endsection
