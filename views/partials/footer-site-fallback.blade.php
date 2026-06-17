<ul class="vs-footer-links">
    <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    @if(Route::has('blog'))
        <li><a href="{{ route('blog') }}">{{ __('Blog') }}</a></li>
    @endif
</ul>
