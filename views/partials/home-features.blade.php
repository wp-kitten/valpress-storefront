@if(valpress_storefront_shop_available())
<section class="vs-features">
    <div class="container">
        <div class="vs-features-grid">
            <div class="vs-feature-item">
                <div class="vs-feature-icon"><i class="bi bi-truck" aria-hidden="true"></i></div>
                <div>
                    <p class="vs-feature-title">{{ __('Fast delivery') }}</p>
                    <p class="vs-feature-text">{{ __('Reliable shipping on every order.') }}</p>
                </div>
            </div>
            <div class="vs-feature-item">
                <div class="vs-feature-icon"><i class="bi bi-shield-check" aria-hidden="true"></i></div>
                <div>
                    <p class="vs-feature-title">{{ __('Secure checkout') }}</p>
                    <p class="vs-feature-text">{{ __('Your payment details are protected.') }}</p>
                </div>
            </div>
            <div class="vs-feature-item">
                <div class="vs-feature-icon"><i class="bi bi-arrow-repeat" aria-hidden="true"></i></div>
                <div>
                    <p class="vs-feature-title">{{ __('Easy returns') }}</p>
                    <p class="vs-feature-text">{{ __('Hassle-free returns on eligible items.') }}</p>
                </div>
            </div>
            <div class="vs-feature-item">
                <div class="vs-feature-icon"><i class="bi bi-headset" aria-hidden="true"></i></div>
                <div>
                    <p class="vs-feature-title">{{ __('Friendly support') }}</p>
                    <p class="vs-feature-text">{{ __('We are here to help when you need us.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
