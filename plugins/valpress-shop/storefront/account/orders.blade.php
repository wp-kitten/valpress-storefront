@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop">
        <h1 class="h2 mb-4">{{ __('valpress-shop::messages.my_orders') }}</h1>

        @include('valpress-shop::storefront.account._nav')

        <div class="table-responsive">
            <table class="table vps-cart-table">
                <thead>
                    <tr>
                        <th>{{ __('valpress-shop::messages.order') }}</th>
                        <th>{{ __('valpress-shop::messages.date') }}</th>
                        <th>{{ __('valpress-shop::messages.status') }}</th>
                        <th>{{ __('valpress-shop::messages.total') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at?->format('Y-m-d') }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ \Plugins\ValPressShop\Support\Money::format($order->grand_total, $order->currency) }}</td>
                            <td class="text-end">
                                <a href="{{ route('shop.account.order', $order) }}" class="btn btn-sm btn-outline-secondary">{{ __('valpress-shop::messages.view') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">{{ __('valpress-shop::messages.no_orders_yet') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    </div>
@endsection
