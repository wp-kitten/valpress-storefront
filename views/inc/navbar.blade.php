<nav class="vs-navbar navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand vs-brand" href="{{ route('home') }}">
            <span class="vs-brand-mark" aria-hidden="true"></span>
            <span>{{ App\Models\Setting::get('site_title', 'ValPress') }}</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#vsNavbar" aria-controls="vsNavbar" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="vsNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @if(is_home()) active @endif" href="{{ route('home') }}">{{ __('Home') }}</a>
                </li>
                @if(valpress_storefront_shop_available())
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('shop.*') && !request()->routeIs('shop.account.*')) active @endif" href="{{ valpress_storefront_catalog_url() }}">{{ __('Shop') }}</a>
                    </li>
                @endif
                @if(class_exists('App\Core\MenuManager'))
                    {!! App\Core\MenuManager::renderMenu('primary', ['container_class' => 'navbar-nav']) !!}
                @else
                    <li class="nav-item">
                        <a class="nav-link @if(is_blog()) active @endif" href="{{ route('blog') }}">{{ __('Blog') }}</a>
                    </li>
                @endif
            </ul>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                @include('valpress-storefront::partials.search-form', ['class' => 'vs-nav-search'])

                <ul class="navbar-nav flex-row align-items-center gap-1 mb-0">
                    {!! apply_filters('valpress_after_navbar_menu', '') !!}
                </ul>

                @guest
                    @if(Route::has('login'))
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('login') }}">{{ __('Login') }}</a>
                    @endif
                @else
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(valpress_storefront_shop_available() && Route::has('shop.account.index'))
                                <li><a class="dropdown-item" href="{{ route('shop.account.index') }}">{{ __('valpress-shop::messages.my_account') }}</a></li>
                            @endif
                            @if(auth()->user()->hasRole('administrator'))
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('vs-logout-form').submit();">{{ __('Logout') }}</a>
                            </li>
                        </ul>
                        <form id="vs-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
