@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="vps-product-grid">
    @forelse($products as $product)
        @php $variant = $product->defaultVariant; @endphp
        <article class="vps-product-card vs-product-card">
            <a href="{{ route('shop.show', $product) }}" class="vs-product-media d-block">
                @if($product->imageUrl())
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" loading="lazy">
                @else
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        <i class="bi bi-image fs-1"></i>
                    </div>
                @endif
            </a>
            <div class="vs-product-body">
                @if($product->categories->isNotEmpty())
                    <div class="small text-uppercase fw-semibold text-muted mb-1" style="letter-spacing:0.04em;">
                        {{ $product->categories->pluck('name')->join(' · ') }}
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
                </div>
                <a href="{{ route('shop.show', $product) }}" class="btn btn-sm btn-primary">
                    {{ __('valpress-shop::messages.view_product') }}
                </a>
            </div>
        </article>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-center py-5">
                <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted mb-0">{{ __('valpress-shop::messages.no_products_found') }}</p>
            </div>
        </div>
    @endforelse
</div>

@if(method_exists($products, 'links'))
<div class="mt-4 d-flex justify-content-center">
    {{ $products->links() }}
</div>
@endif
