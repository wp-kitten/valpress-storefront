<nav class="nav nav-pills flex-column flex-md-row gap-2 mb-4 vs-account-nav">
    <a class="nav-link @if(request()->routeIs('shop.account.index')) active @endif" href="{{ route('shop.account.index') }}">{{ __('valpress-shop::messages.my_account') }}</a>
    <a class="nav-link @if(request()->routeIs('shop.account.orders*')) active @endif" href="{{ route('shop.account.orders') }}">{{ __('valpress-shop::messages.my_orders') }}</a>
    <a class="nav-link @if(request()->routeIs('shop.account.downloads')) active @endif" href="{{ route('shop.account.downloads') }}">{{ __('valpress-shop::messages.my_downloads') }}</a>
    <a class="nav-link @if(request()->routeIs('shop.account.subscriptions*')) active @endif" href="{{ route('shop.account.subscriptions') }}">{{ __('valpress-shop::messages.my_subscriptions') }}</a>
    <a class="nav-link @if(request()->routeIs('shop.account.addresses')) active @endif" href="{{ route('shop.account.addresses') }}">{{ __('valpress-shop::messages.addresses') }}</a>
</nav>
