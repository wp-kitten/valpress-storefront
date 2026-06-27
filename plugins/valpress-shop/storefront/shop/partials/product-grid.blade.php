@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
    @forelse($products as $product)
        @php
            $variant = $product->defaultVariant;
            $onSale = $variant && $variant->compare_at_price && (float) $variant->compare_at_price > (float) $variant->price;
        @endphp
        <div class="col">
            <article class="vps-product-card vs-product-card h-100">
                <a href="{{ route('shop.show', $product) }}" class="vs-product-media d-block text-decoration-none">
                    @if($onSale)
                        <span class="vs-product-badge">{{ __('Sale') }}</span>
                    @endif
                    @if($product->imageUrl())
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" loading="lazy">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="bi bi-image fs-1"></i>
                        </div>
                    @endif
                    <span class="vs-product-media-overlay">
                        <span class="btn btn-sm btn-light">{{ __('View product') }}</span>
                    </span>
                </a>
                <div class="vs-product-body">
                    @if($product->categories->isNotEmpty())
                        <div class="small text-uppercase fw-semibold text-muted mb-1" style="letter-spacing:0.05em;">
                            {{ $product->categories->pluck('name')->first() }}
                        </div>
                    @endif
                    <h3 class="vs-product-title">
                        <a href="{{ route('shop.show', $product) }}">{{ $product->name }}</a>
                    </h3>
                    @if($product->short_description)
                        <p class="small text-muted mb-2">{{ \Illuminate\Support\Str::limit($product->short_description, (int) valpress_storefront_setting('product_excerpt_length', 90)) }}</p>
                    @endif
                    <div class="vs-product-price">
                        {{ $variant ? \Plugins\ValPressShop\Support\Money::format($variant->price) : '—' }}
                        @if($onSale)
                            <span class="vs-product-price-compare">{{ \Plugins\ValPressShop\Support\Money::format($variant->compare_at_price) }}</span>
                        @endif
                    </div>
                    <a href="{{ route('shop.show', $product) }}" class="btn btn-sm btn-primary">
                        {{ __('valpress-shop::messages.view_product') }}
                    </a>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-center py-5 mb-0">
                <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted mb-0">{{ __('valpress-shop::messages.no_products_found') }}</p>
            </div>
        </div>
    @endforelse
</div>

@if(empty($hidePagination) && method_exists($products, 'links'))
<div class="mt-4 vs-pagination">
    {{ $products->links() }}
</div>
@endif
