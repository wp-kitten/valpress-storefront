@extends('valpress-storefront::layouts.storefront')

@section('storefront_content')
    <div class="vps-shop">
        <h1 class="h2 mb-4">{{ __('valpress-shop::messages.my_downloads') }}</h1>

        @include('valpress-shop::storefront.account._nav')

        <div class="table-responsive">
            <table class="table vps-cart-table">
                <thead>
                    <tr>
                        <th>{{ __('valpress-shop::messages.product') }}</th>
                        <th>{{ __('valpress-shop::messages.order') }}</th>
                        <th>{{ __('valpress-shop::messages.downloads') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                        @php
                            $download = $permission->download;
                            $order = $permission->order;
                            $available = !$permission->isExpired() && $permission->hasDownloadsRemaining();
                        @endphp
                        <tr>
                            <td>{{ $download?->filename ?: ($download?->product?->name ?? __('valpress-shop::messages.product')) }}</td>
                            <td>{{ $order?->order_number ?: '—' }}</td>
                            <td>{{ $permission->downloads_remaining ?? '∞' }}</td>
                            <td class="text-end">
                                @if($available)
                                    <a href="{{ app(\Plugins\ValPressShop\Services\DownloadService::class)->signedUrl($permission) }}" class="btn btn-sm btn-primary">{{ __('valpress-shop::messages.download') }}</a>
                                @else
                                    <span class="text-muted">{{ __('valpress-shop::messages.download_unavailable') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">{{ __('valpress-shop::messages.no_downloads_yet') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
