@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop vs-shop">
        @include('valpress-storefront::partials.category-nav')

        <div class="vs-shop-toolbar mb-4">
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
                @endphp

                @include('valpress-storefront::partials.shop-breadcrumb', ['items' => $breadcrumbItems])

                <h1 class="h2 mb-1 mt-1">{{ $product->name }}</h1>

                @if($product->categories->isNotEmpty())
                    <div class="small text-muted">{{ $product->categories->pluck('name')->join(', ') }}</div>
                @endif
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
                    <div class="d-flex align-items-center justify-content-center p-5 text-muted">
                        <i class="bi bi-image fs-1"></i>
                    </div>
                @endif
            </div>

            <div class="vs-product-detail-panel">
                @if($product->short_description)
                    <p class="lead">{{ $product->short_description }}</p>
                @endif

                @if($product->description)
                    <div class="mb-4 text-muted">{!! nl2br(e($product->description)) !!}</div>
                @endif

                <form action="{{ route('cart.add') }}" method="POST" class="row g-3 align-items-end">
                    @csrf

                    @if($product->isVariable() && $product->variants->count() > 1)
                        <div class="col-12">
                            <label for="variant_id" class="form-label">{{ __('valpress-shop::messages.select_variant') }}</label>
                            <select name="variant_id" id="variant_id" class="form-select" required>
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}">
                                        {{ $variant->displayName() }} — {{ \Plugins\ValPressShop\Support\Money::format($variant->price) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        @php $variant = $product->defaultVariant ?? $product->variants->first(); @endphp
                        @if($variant)
                            <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                            <div class="col-12">
                                <div class="vs-product-price h3 mb-0">
                                    {{ \Plugins\ValPressShop\Support\Money::format($variant->price) }}
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="col-sm-4">
                        <label for="quantity" class="form-label">{{ __('valpress-shop::messages.qty') }}</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="99">
                    </div>
                    <div class="col-sm-8">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-bag-plus me-1"></i>{{ __('valpress-shop::messages.add_to_cart') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
