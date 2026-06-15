@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop">
        <h1 class="h2 mb-4">{{ __('valpress-shop::messages.my_subscriptions') }}</h1>

        @include('valpress-shop::storefront.account._nav')

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($subscriptions as $subscription)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <div class="fw-bold">{{ $subscription->product?->name }}</div>
                        <div class="text-muted small">
                            {{ \Plugins\ValPressShop\Support\Money::format($subscription->amount, $subscription->currency) }}
                            / {{ $subscription->billing_interval_count }} {{ $subscription->billing_interval }}
                        </div>
                        <div class="small">{{ __('valpress-shop::messages.status') }}: {{ $subscription->status }}</div>
                        @if($subscription->next_billing_at)
                            <div class="small text-muted">{{ __('valpress-shop::messages.next_billing') }}: {{ $subscription->next_billing_at->toDateString() }}</div>
                        @endif
                    </div>
                    @if($subscription->status === 'active')
                        <form method="post" action="{{ route('shop.account.subscriptions.cancel', $subscription) }}" onsubmit="return confirm('{{ __('valpress-shop::messages.confirm_cancel_subscription') }}')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('valpress-shop::messages.cancel_subscription') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted">{{ __('valpress-shop::messages.no_subscriptions_yet') }}</p>
        @endforelse
    </div>
@endsection
