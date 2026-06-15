@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop">
        <h1 class="h2 mb-4">{{ __('valpress-shop::messages.my_account') }}</h1>

        @include('valpress-shop::storefront.account._nav')

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5">{{ auth()->user()->name }}</h2>
                        <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">{{ __('valpress-shop::messages.my_orders') }}</div>
                    <div class="card-body">
                        @forelse($orders as $order)
                            <div class="d-flex justify-content-between align-items-center @if(!$loop->last) border-bottom pb-2 mb-2 @endif">
                                <div>
                                    <a href="{{ route('shop.account.order', $order) }}">{{ $order->order_number }}</a>
                                    <div class="small text-muted">{{ $order->created_at?->format('Y-m-d') }}</div>
                                </div>
                                <div class="text-end">
                                    <div>{{ \Plugins\ValPressShop\Support\Money::format($order->grand_total, $order->currency) }}</div>
                                    <div class="small text-muted">{{ $order->status }}</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">{{ __('valpress-shop::messages.no_orders_yet') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
