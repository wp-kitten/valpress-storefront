@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop vps-thank-you">
        <h1 class="h2">{{ __('valpress-shop::messages.thank_you') }}</h1>
        <p class="lead">{{ __('valpress-shop::messages.order_received', ['number' => $order->order_number]) }}</p>
        <p class="text-muted">{{ __('valpress-shop::messages.confirmation_sent', ['email' => $order->customer_email]) }}</p>

        <div class="vps-checkout-summary text-start mx-auto" style="max-width: 480px;">
            <dl class="row mb-0">
                <dt class="col-sm-5">{{ __('valpress-shop::messages.order_number') }}</dt>
                <dd class="col-sm-7">{{ $order->order_number }}</dd>
                <dt class="col-sm-5">{{ __('valpress-shop::messages.total') }}</dt>
                <dd class="col-sm-7">{{ \Plugins\ValPressShop\Support\Money::format($order->grand_total, $order->currency) }}</dd>
                <dt class="col-sm-5">{{ __('valpress-shop::messages.payment_status') }}</dt>
                <dd class="col-sm-7">{{ $order->payment_status }}</dd>
            </dl>
        </div>

        <div class="mt-4">
            <a href="{{ route('shop.index') }}" class="btn btn-primary">{{ __('valpress-shop::messages.continue_shopping') }}</a>
        </div>
    </div>
@endsection
