@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop">
        <h1 class="h2 mb-4">{{ __('valpress-shop::messages.addresses') }}</h1>

        @include('valpress-shop::storefront.account._nav')

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4 mb-4">
            @forelse($customer?->addresses ?? [] as $address)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="small text-muted text-uppercase mb-2">{{ $address->type }}</div>
                            @if($address->label)
                                <div class="fw-semibold">{{ $address->label }}</div>
                            @endif
                            <div>{{ $address->line1 }}</div>
                            @if($address->line2)
                                <div>{{ $address->line2 }}</div>
                            @endif
                            <div>{{ $address->city }}@if($address->state), {{ $address->state }}@endif {{ $address->postal_code }}</div>
                            <div>{{ $address->country }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">{{ __('valpress-shop::messages.no_addresses_yet') }}</p>
                </div>
            @endforelse
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">{{ __('valpress-shop::messages.add_address') }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('shop.account.addresses.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="type">{{ __('valpress-shop::messages.type') }}</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="billing">{{ __('valpress-shop::messages.billing_address') }}</option>
                                <option value="shipping">{{ __('valpress-shop::messages.shipping_address') }}</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="label">{{ __('valpress-shop::messages.label') }}</label>
                            <input type="text" name="label" id="label" class="form-control" maxlength="100">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="line1">{{ __('valpress-shop::messages.address_line1') }}</label>
                            <input type="text" name="line1" id="line1" class="form-control" required maxlength="255">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="line2">{{ __('valpress-shop::messages.address_line2') }}</label>
                            <input type="text" name="line2" id="line2" class="form-control" maxlength="255">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="city">{{ __('valpress-shop::messages.city') }}</label>
                            <input type="text" name="city" id="city" class="form-control" required maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="state">{{ __('valpress-shop::messages.state') }}</label>
                            <input type="text" name="state" id="state" class="form-control" maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="postal_code">{{ __('valpress-shop::messages.postal_code') }}</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control" required maxlength="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="country">{{ __('valpress-shop::messages.country') }}</label>
                            <input type="text" name="country" id="country" class="form-control" required maxlength="2" value="US">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_default" id="is_default" value="1" class="form-check-input">
                                <label class="form-check-label" for="is_default">{{ __('valpress-shop::messages.default_address') }}</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">{{ __('valpress-shop::messages.save_address') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
