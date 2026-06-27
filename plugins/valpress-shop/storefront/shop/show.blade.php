@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    @include('valpress-storefront::partials.category-nav')

    <div class="container py-4">
        <div class="vs-shop">
            <div class="vs-shop-toolbar mt-2 mb-4">
            <div class="vs-shop-toolbar-main">
                @php
                    $breadcrumbItems = [
                        ['label' => __('Shop'), 'url' => valpress_storefront_catalog_url()],
                    ];

                    if ($product->categories->isNotEmpty() && Route::has('shop.category')) {
                        $primaryCategory = $product->categories->first();
                        $breadcrumbItems[] = [
                            'label' => $primaryCategory->name,
                            'url' => route('shop.category', $primaryCategory),
                        ];
                    }

                    $breadcrumbItems[] = [
                        'label' => $product->name,
                        'url' => '',
                    ];

                    $variant = $product->defaultVariant ?? $product->variants->first();
                    $onSale = $variant && $variant->compare_at_price && (float) $variant->compare_at_price > (float) $variant->price;
                @endphp

                @include('valpress-storefront::partials.shop-breadcrumb', ['items' => $breadcrumbItems])
            </div>

            @if(Route::has('cart.index'))
                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-bag me-1"></i>{{ __('valpress-shop::messages.view_cart') }}
                </a>
            @endif
        </div>

        <div class="vs-product-detail">
            <div class="vs-product-detail-image">
                @if($product->imageUrl())
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                @else
                    <div class="d-flex align-items-center justify-content-center p-5 text-muted" style="min-height:320px;">
                        <i class="bi bi-image display-4"></i>
                    </div>
                @endif
            </div>

            <div class="vs-product-detail-panel">
                @if($product->categories->isNotEmpty())
                    <div class="small text-uppercase fw-semibold text-muted mb-2" style="letter-spacing:0.05em;">
                        {{ $product->categories->pluck('name')->join(' · ') }}
                    </div>
                @endif

                <h1 class="h2 fw-bold mb-3">{{ $product->name }}</h1>

                @if($product->short_description)
                    <p class="lead text-muted mb-4">{{ $product->short_description }}</p>
                @endif

                @if(!$product->isVariable() || $product->variants->count() <= 1)
                    @if($variant)
                        <div class="vs-product-detail-price-block">
                            <div class="vs-product-price">
                                {{ \Plugins\ValPressShop\Support\Money::format($variant->price) }}
                                @if($onSale)
                                    <span class="vs-product-price-compare">{{ \Plugins\ValPressShop\Support\Money::format($variant->compare_at_price) }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

                <form action="{{ route('cart.add') }}" method="POST" class="row g-3 align-items-end">
                    @csrf

                    @if($product->isVariable() && $product->variants->count() > 1)
                        <div class="col-12">
                            <label for="variant_id" class="form-label fw-semibold">{{ __('valpress-shop::messages.select_variant') }}</label>
                            <select name="variant_id" id="variant_id" class="form-select form-select-lg" required>
                                @foreach($product->variants as $v)
                                    <option value="{{ $v->id }}">
                                        {{ $v->displayName() }} — {{ \Plugins\ValPressShop\Support\Money::format($v->price) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        @if($variant)
                            <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                        @endif
                    @endif

                    <div class="col-sm-4">
                        <label for="quantity" class="form-label fw-semibold">{{ __('valpress-shop::messages.qty') }}</label>
                        <input type="number" name="quantity" id="quantity" class="form-control form-control-lg" value="1" min="1" max="99">
                    </div>
                    <div class="col-sm-8">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-bag-plus me-1"></i>{{ __('valpress-shop::messages.add_to_cart') }}
                        </button>
                    </div>
                </form>

                <div class="vs-product-trust">
                    <span class="vs-product-trust-item"><i class="bi bi-truck"></i>{{ __('Fast shipping') }}</span>
                    <span class="vs-product-trust-item"><i class="bi bi-shield-check"></i>{{ __('Secure payment') }}</span>
                    <span class="vs-product-trust-item"><i class="bi bi-arrow-repeat"></i>{{ __('Easy returns') }}</span>
                </div>

                @if($product->description)
                    <div class="mt-4 pt-4 border-top">
                        <h2 class="h6 fw-bold text-uppercase mb-3" style="letter-spacing:0.05em;">{{ __('Description') }}</h2>
                        <div class="text-muted">{!! nl2br(e($product->description)) !!}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection
