@php
    $items = $items ?? [];
@endphp

@if(!empty($items))
    <nav class="vs-shop-breadcrumb" aria-label="{{ __('Breadcrumb') }}">
        <ol class="breadcrumb mb-2">
            @foreach($items as $item)
                @if($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
