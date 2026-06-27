@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    @php
        $productTotal = $products?->total() ?? 0;
        $postTotal = $posts->total();
        $totalResults = $productTotal + $postTotal;
    @endphp

    <section class="vs-section">
        <div class="container">
            <nav class="vs-shop-breadcrumb mb-3" aria-label="{{ __('Breadcrumb') }}">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Search') }}</li>
                </ol>
            </nav>

            <div class="vs-section-header">
                <div>
                    <h1 class="vs-section-title">{{ __('Search Results') }}</h1>
                    <p class="vs-section-subtitle">
                        @if($query === '')
                            {{ __('Enter a search term to find products and posts.') }}
                        @elseif($totalResults > 0)
                            {{ trans_choice('{1} Found :count result for ":query"|[2,*] Found :count results for ":query"', $totalResults, ['count' => $totalResults, 'query' => $query]) }}
                        @else
                            {{ __('No results found for ":query"', ['query' => $query]) }}
                        @endif
                    </p>
                </div>
            </div>

            @if($query !== '' && $products && $products->count() > 0)
                <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                        <h2 class="h4 mb-0">{{ __('Products') }}</h2>
                        <span class="small text-muted">
                            {{ trans_choice(':count product|:count products', $productTotal, ['count' => $productTotal]) }}
                        </span>
                    </div>

                    @include('valpress-shop::storefront.shop.partials.product-grid', ['products' => $products, 'hidePagination' => true])

                    <div class="mt-4 vs-pagination">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif

            @if($posts->count() > 0)
                <div class="@if($products && $products->count() > 0) pt-4 border-top @endif">
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                        <h2 class="h4 mb-0">{{ __('Posts') }}</h2>
                        <span class="small text-muted">
                            {{ trans_choice(':count post|:count posts', $postTotal, ['count' => $postTotal]) }}
                        </span>
                    </div>

                    <div class="row g-4">
                        @foreach($posts as $post)
                            <div class="col-md-6 col-lg-4">
                                <article class="vs-page-content h-100">
                                    @if($post->categories->isNotEmpty())
                                        <div class="small text-uppercase fw-semibold text-muted mb-2" style="letter-spacing:0.04em;">
                                            {{ $post->categories->pluck('name')->join(' · ') }}
                                        </div>
                                    @endif
                                    <h3 class="h5">
                                        <a href="{{ valpress_get_permalink($post) }}" class="text-decoration-none">{{ $post->post_title }}</a>
                                    </h3>
                                    <p class="text-muted small mb-2">{{ valpress_format_date($post->created_at) }}</p>
                                    <p class="mb-3">{{ valpress_get_post_excerpt($post, 24) }}</p>
                                    <a href="{{ valpress_get_permalink($post) }}" class="btn btn-sm btn-outline-primary">{{ __('Read more') }}</a>
                                </article>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 vs-pagination">
                        {{ $posts->links() }}
                    </div>
                </div>
            @endif

            @if($query !== '' && $totalResults === 0)
                <div class="vs-page-content text-center py-5">
                    <i class="bi bi-search display-4 text-muted d-block mb-3"></i>
                    <h2 class="h4 fw-bold">{{ __('Oops! No results found') }}</h2>
                    <p class="text-muted mb-4">{{ __('We couldn\'t find any products or posts matching your search. Try different keywords.') }}</p>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            @include('valpress-storefront::partials.search-form', ['query' => $query])
                        </div>
                    </div>
                </div>
            @elseif($query === '')
                <div class="vs-page-content text-center py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            @include('valpress-storefront::partials.search-form')
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
