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
                    <div class="col-md-6 mb-3">
                        <label for="footer_categories_count" class="form-label fw-bold">{{ __('valpress-storefront::messages.footer_categories_count') }}</label>
                        <input type="number" min="0" max="24" name="footer_categories_count" id="footer_categories_count" class="form-control @error('footer_categories_count') is-invalid @enderror" value="{{ old('footer_categories_count', $storefrontSettings['footer_categories_count'] ?? 6) }}" required>
                        @error('footer_categories_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="product_excerpt_length" class="form-label fw-bold">{{ __('valpress-storefront::messages.product_excerpt_length') }}</label>
                        <input type="number" min="20" max="500" name="product_excerpt_length" id="product_excerpt_length" class="form-control @error('product_excerpt_length') is-invalid @enderror" value="{{ old('product_excerpt_length', $storefrontSettings['product_excerpt_length'] ?? 90) }}" required>
                        @error('product_excerpt_length')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">{{ __('valpress-storefront::messages.colors') }}</h2>
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
