@extends('layouts.admin')

@section('title', __('valpress-storefront::messages.settings_title') . ' - ValPress')
@section('page_title', __('valpress-storefront::messages.settings_title'))

@section('content')
    @php
        $storefrontSettings = $storefrontSettings ?? \Themes\ValpressStorefront\StorefrontSettings::all();
    @endphp

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <p class="text-muted mb-4">{{ __('valpress-storefront::messages.settings_intro') }}</p>

    <form method="POST" action="{{ route('admin.storefront.settings.store') }}">
        @csrf

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">{{ __('valpress-storefront::messages.catalog') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="products_per_page" class="form-label fw-bold">{{ __('valpress-storefront::messages.products_per_page') }}</label>
                        <input type="number" min="1" max="100" name="products_per_page" id="products_per_page" class="form-control @error('products_per_page') is-invalid @enderror" value="{{ old('products_per_page', $storefrontSettings['products_per_page'] ?? 15) }}" required>
                        <div class="form-text">{{ __('valpress-storefront::messages.products_per_page_help') }}</div>
                        @error('products_per_page')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="featured_products_count" class="form-label fw-bold">{{ __('valpress-storefront::messages.featured_products_count') }}</label>
                        <input type="number" min="0" max="48" name="featured_products_count" id="featured_products_count" class="form-control @error('featured_products_count') is-invalid @enderror" value="{{ old('featured_products_count', $storefrontSettings['featured_products_count'] ?? 8) }}" required>
                        @error('featured_products_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="footer_categories_count" class="form-label fw-bold">{{ __('valpress-storefront::messages.footer_categories_count') }}</label>
                        <input type="number" min="0" max="24" name="footer_categories_count" id="footer_categories_count" class="form-control @error('footer_categories_count') is-invalid @enderror" value="{{ old('footer_categories_count', $storefrontSettings['footer_categories_count'] ?? 6) }}" required>
                        @error('footer_categories_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="product_excerpt_length" class="form-label fw-bold">{{ __('valpress-storefront::messages.product_excerpt_length') }}</label>
                        <input type="number" min="20" max="500" name="product_excerpt_length" id="product_excerpt_length" class="form-control @error('product_excerpt_length') is-invalid @enderror" value="{{ old('product_excerpt_length', $storefrontSettings['product_excerpt_length'] ?? 90) }}" required>
                        @error('product_excerpt_length')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="product_grid_min_width" class="form-label fw-bold">{{ __('valpress-storefront::messages.product_grid_min_width') }}</label>
                        <input type="number" min="160" max="400" name="product_grid_min_width" id="product_grid_min_width" class="form-control @error('product_grid_min_width') is-invalid @enderror" value="{{ old('product_grid_min_width', $storefrontSettings['product_grid_min_width'] ?? 240) }}" required>
                        @error('product_grid_min_width')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="container_max_width" class="form-label fw-bold">{{ __('valpress-storefront::messages.container_max_width') }}</label>
                        <input type="number" min="960" max="1600" name="container_max_width" id="container_max_width" class="form-control @error('container_max_width') is-invalid @enderror" value="{{ old('container_max_width', $storefrontSettings['container_max_width'] ?? 1200) }}" required>
                        @error('container_max_width')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">{{ __('valpress-storefront::messages.homepage') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_home_hero" id="show_home_hero" value="1" @checked(old('show_home_hero', $storefrontSettings['show_home_hero'] ?? true))>
                    <label class="form-check-label fw-bold" for="show_home_hero">{{ __('valpress-storefront::messages.show_home_hero') }}</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_featured_products" id="show_featured_products" value="1" @checked(old('show_featured_products', $storefrontSettings['show_featured_products'] ?? true))>
                    <label class="form-check-label fw-bold" for="show_featured_products">{{ __('valpress-storefront::messages.show_featured_products') }}</label>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="show_hero_blog_button" id="show_hero_blog_button" value="1" @checked(old('show_hero_blog_button', $storefrontSettings['show_hero_blog_button'] ?? true))>
                    <label class="form-check-label fw-bold" for="show_hero_blog_button">{{ __('valpress-storefront::messages.show_hero_blog_button') }}</label>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">{{ __('valpress-storefront::messages.colors_layout') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="accent_color" class="form-label fw-bold">{{ __('valpress-storefront::messages.accent_color') }}</label>
                        <input type="color" name="accent_color" id="accent_color" class="form-control form-control-color w-100 @error('accent_color') is-invalid @enderror" value="{{ old('accent_color', $storefrontSettings['accent_color'] ?? '#0d9488') }}" required>
                        @error('accent_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="accent_dark_color" class="form-label fw-bold">{{ __('valpress-storefront::messages.accent_dark_color') }}</label>
                        <input type="color" name="accent_dark_color" id="accent_dark_color" class="form-control form-control-color w-100 @error('accent_dark_color') is-invalid @enderror" value="{{ old('accent_dark_color', $storefrontSettings['accent_dark_color'] ?? '#0f766e') }}" required>
                        @error('accent_dark_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="accent_soft_color" class="form-label fw-bold">{{ __('valpress-storefront::messages.accent_soft_color') }}</label>
                        <input type="color" name="accent_soft_color" id="accent_soft_color" class="form-control form-control-color w-100 @error('accent_soft_color') is-invalid @enderror" value="{{ old('accent_soft_color', $storefrontSettings['accent_soft_color'] ?? '#ccfbf1') }}" required>
                        @error('accent_soft_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="border_radius" class="form-label fw-bold">{{ __('valpress-storefront::messages.border_radius') }}</label>
                        <input type="text" name="border_radius" id="border_radius" class="form-control @error('border_radius') is-invalid @enderror" value="{{ old('border_radius', $storefrontSettings['border_radius'] ?? '1rem') }}" placeholder="1rem" required>
                        @error('border_radius')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="hero_gradient_start" class="form-label fw-bold">{{ __('valpress-storefront::messages.hero_gradient_start') }}</label>
                        <input type="color" name="hero_gradient_start" id="hero_gradient_start" class="form-control form-control-color w-100 @error('hero_gradient_start') is-invalid @enderror" value="{{ old('hero_gradient_start', $storefrontSettings['hero_gradient_start'] ?? '#0f172a') }}" required>
                        @error('hero_gradient_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="hero_gradient_mid" class="form-label fw-bold">{{ __('valpress-storefront::messages.hero_gradient_mid') }}</label>
                        <input type="color" name="hero_gradient_mid" id="hero_gradient_mid" class="form-control form-control-color w-100 @error('hero_gradient_mid') is-invalid @enderror" value="{{ old('hero_gradient_mid', $storefrontSettings['hero_gradient_mid'] ?? '#134e4a') }}" required>
                        @error('hero_gradient_mid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="hero_gradient_end" class="form-label fw-bold">{{ __('valpress-storefront::messages.hero_gradient_end') }}</label>
                        <input type="color" name="hero_gradient_end" id="hero_gradient_end" class="form-control form-control-color w-100 @error('hero_gradient_end') is-invalid @enderror" value="{{ old('hero_gradient_end', $storefrontSettings['hero_gradient_end'] ?? '#0d9488') }}" required>
                        @error('hero_gradient_end')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('valpress-storefront::messages.save_settings') }}</button>
    </form>
@endsection
