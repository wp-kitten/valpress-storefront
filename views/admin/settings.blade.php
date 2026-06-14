@extends('layouts.admin')

@section('title', __('Storefront Settings') . ' - ValPress')
@section('page_title', __('Storefront Settings'))

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <p class="text-muted mb-4">{{ __('Customize layout, catalog, and appearance options for the ValPress Storefront theme.') }}</p>

    <form method="POST" action="{{ route('admin.storefront.settings.store') }}">
        @csrf

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">{{ __('Catalog') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="products_per_page" class="form-label fw-bold">{{ __('Products per page') }}</label>
                        <input type="number" min="1" max="100" name="products_per_page" id="products_per_page" class="form-control @error('products_per_page') is-invalid @enderror" value="{{ old('products_per_page', $settings['products_per_page']) }}" required>
                        <div class="form-text">{{ __('Use multiples of your grid column count (e.g. 15 for a 5-column layout).') }}</div>
                        @error('products_per_page')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="featured_products_count" class="form-label fw-bold">{{ __('Featured products on homepage') }}</label>
                        <input type="number" min="0" max="48" name="featured_products_count" id="featured_products_count" class="form-control @error('featured_products_count') is-invalid @enderror" value="{{ old('featured_products_count', $settings['featured_products_count']) }}" required>
                        @error('featured_products_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="footer_categories_count" class="form-label fw-bold">{{ __('Footer category links') }}</label>
                        <input type="number" min="0" max="24" name="footer_categories_count" id="footer_categories_count" class="form-control @error('footer_categories_count') is-invalid @enderror" value="{{ old('footer_categories_count', $settings['footer_categories_count']) }}" required>
                        @error('footer_categories_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="product_excerpt_length" class="form-label fw-bold">{{ __('Product excerpt length') }}</label>
                        <input type="number" min="20" max="500" name="product_excerpt_length" id="product_excerpt_length" class="form-control @error('product_excerpt_length') is-invalid @enderror" value="{{ old('product_excerpt_length', $settings['product_excerpt_length']) }}" required>
                        @error('product_excerpt_length')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="product_grid_min_width" class="form-label fw-bold">{{ __('Product card min width (px)') }}</label>
                        <input type="number" min="160" max="400" name="product_grid_min_width" id="product_grid_min_width" class="form-control @error('product_grid_min_width') is-invalid @enderror" value="{{ old('product_grid_min_width', $settings['product_grid_min_width']) }}" required>
                        @error('product_grid_min_width')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="container_max_width" class="form-label fw-bold">{{ __('Content max width (px)') }}</label>
                        <input type="number" min="960" max="1600" name="container_max_width" id="container_max_width" class="form-control @error('container_max_width') is-invalid @enderror" value="{{ old('container_max_width', $settings['container_max_width']) }}" required>
                        @error('container_max_width')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">{{ __('Homepage') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_home_hero" id="show_home_hero" value="1" @checked(old('show_home_hero', $settings['show_home_hero']))>
                    <label class="form-check-label fw-bold" for="show_home_hero">{{ __('Show homepage hero') }}</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_featured_products" id="show_featured_products" value="1" @checked(old('show_featured_products', $settings['show_featured_products']))>
                    <label class="form-check-label fw-bold" for="show_featured_products">{{ __('Show featured products section') }}</label>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="show_hero_blog_button" id="show_hero_blog_button" value="1" @checked(old('show_hero_blog_button', $settings['show_hero_blog_button']))>
                    <label class="form-check-label fw-bold" for="show_hero_blog_button">{{ __('Show blog button in hero') }}</label>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">{{ __('Colors & layout') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="accent_color" class="form-label fw-bold">{{ __('Accent color') }}</label>
                        <input type="color" name="accent_color" id="accent_color" class="form-control form-control-color w-100 @error('accent_color') is-invalid @enderror" value="{{ old('accent_color', $settings['accent_color']) }}" required>
                        @error('accent_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="accent_dark_color" class="form-label fw-bold">{{ __('Accent dark color') }}</label>
                        <input type="color" name="accent_dark_color" id="accent_dark_color" class="form-control form-control-color w-100 @error('accent_dark_color') is-invalid @enderror" value="{{ old('accent_dark_color', $settings['accent_dark_color']) }}" required>
                        @error('accent_dark_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="accent_soft_color" class="form-label fw-bold">{{ __('Accent soft color') }}</label>
                        <input type="color" name="accent_soft_color" id="accent_soft_color" class="form-control form-control-color w-100 @error('accent_soft_color') is-invalid @enderror" value="{{ old('accent_soft_color', $settings['accent_soft_color']) }}" required>
                        @error('accent_soft_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="border_radius" class="form-label fw-bold">{{ __('Border radius') }}</label>
                        <input type="text" name="border_radius" id="border_radius" class="form-control @error('border_radius') is-invalid @enderror" value="{{ old('border_radius', $settings['border_radius']) }}" placeholder="1rem" required>
                        @error('border_radius')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="hero_gradient_start" class="form-label fw-bold">{{ __('Hero gradient start') }}</label>
                        <input type="color" name="hero_gradient_start" id="hero_gradient_start" class="form-control form-control-color w-100 @error('hero_gradient_start') is-invalid @enderror" value="{{ old('hero_gradient_start', $settings['hero_gradient_start']) }}" required>
                        @error('hero_gradient_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="hero_gradient_mid" class="form-label fw-bold">{{ __('Hero gradient middle') }}</label>
                        <input type="color" name="hero_gradient_mid" id="hero_gradient_mid" class="form-control form-control-color w-100 @error('hero_gradient_mid') is-invalid @enderror" value="{{ old('hero_gradient_mid', $settings['hero_gradient_mid']) }}" required>
                        @error('hero_gradient_mid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="hero_gradient_end" class="form-label fw-bold">{{ __('Hero gradient end') }}</label>
                        <input type="color" name="hero_gradient_end" id="hero_gradient_end" class="form-control form-control-color w-100 @error('hero_gradient_end') is-invalid @enderror" value="{{ old('hero_gradient_end', $settings['hero_gradient_end']) }}" required>
                        @error('hero_gradient_end')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Save settings') }}</button>
    </form>
@endsection
