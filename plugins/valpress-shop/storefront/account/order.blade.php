@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop">
        <div class="vps-shop-header mb-4">
            <h1 class="h2 mb-0">{{ __('valpress-shop::messages.order') }} {{ $order->order_number }}</h1>
            <a href="{{ route('shop.account.orders') }}" class="btn btn-outline-secondary">{{ __('valpress-shop::messages.back_to_orders') }}</a>
        </div>

        @include('valpress-shop::storefront.account._nav')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table vps-cart-table">
                        <thead>
                            <tr>
                                <th>{{ __('valpress-shop::messages.product') }}</th>
                                <th>{{ __('valpress-shop::messages.qty') }}</th>
                                <th>{{ __('valpress-shop::messages.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ \Plugins\ValPressShop\Support\Money::format($item->total, $order->currency) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="vps-checkout-summary">
                    <dl class="row mb-0">
                        <dt class="col-sm-6">{{ __('valpress-shop::messages.status') }}</dt>
                        <dd class="col-sm-6 text-end">{{ $order->status }}</dd>
                        <dt class="col-sm-6">{{ __('valpress-shop::messages.payment_status') }}</dt>
                        <dd class="col-sm-6 text-end">{{ $order->payment_status }}</dd>
                        <dt class="col-sm-6">{{ __('valpress-shop::messages.subtotal') }}</dt>
                        <dd class="col-sm-6 text-end">{{ \Plugins\ValPressShop\Support\Money::format($order->subtotal, $order->currency) }}</dd>
                        @if($order->discount_total > 0)
                            <dt class="col-sm-6">{{ __('valpress-shop::messages.discount') }}</dt>
                            <dd class="col-sm-6 text-end">−{{ \Plugins\ValPressShop\Support\Money::format($order->discount_total, $order->currency) }}</dd>
                        @endif
                        <dt class="col-sm-6">{{ __('valpress-shop::messages.total') }}</dt>
                        <dd class="col-sm-6 text-end"><strong>{{ \Plugins\ValPressShop\Support\Money::format($order->grand_total, $order->currency) }}</strong></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
