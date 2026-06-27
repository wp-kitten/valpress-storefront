@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <section class="vs-section">
        <div class="container">
            <div class="vs-section-header">
                <div>
                    <h1 class="vs-section-title">{{ __('Blog') }}</h1>
                    <p class="vs-section-subtitle">{{ __('News, updates, and stories.') }}</p>
                </div>
            </div>

            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-md-6 col-lg-4">
                        <article class="vs-page-content h-100">
                            <h2 class="h5">
                                <a href="{{ valpress_get_permalink($post) }}" class="text-decoration-none">{{ $post->post_title }}</a>
                            </h2>
                            <p class="text-muted small mb-2">{{ $post->created_at?->format('F j, Y') }}</p>
                            <p class="mb-3">{{ valpress_get_post_excerpt($post, 24) }}</p>
                            <a href="{{ valpress_get_permalink($post) }}" class="btn btn-sm btn-outline-primary">{{ __('Read more') }}</a>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">{{ __('No posts found.') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4 vs-pagination">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
@endsection
