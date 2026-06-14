<?php

use App\Core\ScriptManager;
use App\Core\ValPress;
use App\Models\Post;
use App\Models\Setting;
use Plugins\ValPressShop\Bootstrap\PageArchiveIntegration;
use Plugins\ValPressShop\Models\Category;
use Plugins\ValPressShop\Plugin;
use Plugins\ValPressShop\Services\CartService;
use Themes\ValpressStorefront\StorefrontSettings;

ValPress::registerViews( __DIR__ . '/views', 'valpress-storefront' );

if ( !function_exists( 'valpress_storefront_setting' ) ) {
	function valpress_storefront_setting( string $key, mixed $default = null ): mixed
	{
		return StorefrontSettings::get( $key, $default );
	}
}

if ( !function_exists( 'valpress_storefront_setting_bool' ) ) {
	function valpress_storefront_setting_bool( string $key ): bool
	{
		return StorefrontSettings::bool( $key );
	}
}

if ( !function_exists( 'valpress_storefront_shop_available' ) ) {
	function valpress_storefront_shop_available(): bool
	{
		return class_exists( Plugin::class ) && Plugin::isEnabled();
	}
}

if ( !function_exists( 'valpress_storefront_catalog_url' ) ) {
	function valpress_storefront_catalog_url(): string
	{
		if ( !valpress_storefront_shop_available() ) {
			return route( 'home' );
		}

		$homePageId = Setting::get( 'home_page' );

		if ( $homePageId ) {
			$page = Post::query()->find( $homePageId );

			if (
				$page
				&& $page->post_status === 'publish'
				&& $page->getMeta( '_is_archive' )
				&& $page->getMeta( '_archive_post_type' ) === PageArchiveIntegration::ARCHIVE_TYPE
			) {
				return route( 'home' );
			}
		}

		return route( 'shop.index' );
	}
}

if ( !function_exists( 'valpress_storefront_should_load_shop_styles' ) ) {
	function valpress_storefront_should_load_shop_styles(): bool
	{
		if ( !valpress_storefront_shop_available() ) {
			return false;
		}

		if ( request()->routeIs( 'shop.*', 'cart.*', 'checkout.*', 'shop.account.*' ) ) {
			return true;
		}

		// Homepage renders the product catalog on route "home", not shop.*.
		if ( is_home() ) {
			return true;
		}

		return false;
	}
}

if ( !function_exists( 'valpress_storefront_cart_count' ) ) {
	function valpress_storefront_cart_count(): int
	{
		if ( !valpress_storefront_shop_available() ) {
			return 0;
		}

		try {
			$cart = app( CartService::class )->getOrCreateCart();

			return (int)$cart->items->sum( 'quantity' );
		} catch ( Throwable $e ) {
			return 0;
		}
	}
}

if ( !function_exists( 'valpress_storefront_shop_categories' ) ) {
	/**
	 * @return \Illuminate\Support\Collection<int, Category>
	 */
	function valpress_storefront_shop_categories()
	{
		if ( !valpress_storefront_shop_available() || !class_exists( Category::class ) ) {
			return collect();
		}

		return Category::query()->orderBy( 'sort_order' )->orderBy( 'name' )->get();
	}
}

if ( !function_exists( 'valpress_storefront_favicon' ) ) {
	function valpress_storefront_favicon(): string
	{
		$favicon = Setting::get( 'favicon', 'favicon.svg' );
		$path = public_path( $favicon );
		$version = is_file( $path ) ? filemtime( $path ) : time();

		return sprintf(
			'<link rel="icon" href="%s?v=%s" type="%s"/>',
			e( asset( $favicon ) ),
			$version,
			str_ends_with( $favicon, '.svg' ) ? 'image/svg+xml' : 'image/png'
		);
	}
}

add_action( 'valpress_enqueue_scripts', function (): void {
	ScriptManager::enqueueScript(
		'bootstrap5',
		asset( '3rd-party/bootstrap/js/bootstrap.bundle.min.js' ),
		[],
		'5.3.3',
		true
	);

	ScriptManager::enqueueStyle(
		'valpress-storefront',
		asset( 'themes/valpress-storefront/res/css/storefront.css' ),
		[],
		'1.0.1'
	);

	if ( valpress_storefront_should_load_shop_styles() ) {
		ScriptManager::enqueueStyle(
			'valpress-storefront-shop',
			asset( 'themes/valpress-storefront/res/css/shop.css' ),
			[ 'valpress-storefront' ],
			'1.0.1'
		);
	}
} );

add_filter( 'body_classes', function ( array $classes ): array {
	$classes[] = 'valpress-storefront-theme';

	if ( request()->routeIs( 'shop.*', 'cart.*', 'checkout.*', 'shop.account.*' ) ) {
		$classes[] = 'vs-shop-route';
	}

	if ( is_home() ) {
		$classes[] = 'vs-home';
	}

	return $classes;
} );

add_filter( 'valpress_show_blog_footer', fn () => false );

add_action( 'valpress_render_footer', function (): void {
	echo view( 'valpress-storefront::partials.footer' )->render();
} );

add_filter( 'valpress_after_navbar_menu', function ( string $html ): string {
	if ( !valpress_storefront_shop_available() ) {
		return $html;
	}

	$count = valpress_storefront_cart_count();

	$html .= view( 'valpress-storefront::partials.nav-shop-links', compact( 'count' ) )->render();

	return $html;
} );

add_filter( 'valpress_admin_menu_items', function ( array $items ): array {
	$items[ 'storefront' ] = [
		'id' => 'storefront',
		'title' => __( 'Storefront' ),
		'url' => fn () => route( 'admin.storefront.settings' ),
		'icon' => 'bi-circle',
		'order' => 5,
		'parent' => 'themes',
		'permission' => 'manage_themes',
	];

	return $items;
} );

add_filter( 'valpress_shop_products_per_page', function (): int {
	return max( 1, min( 100, (int)valpress_storefront_setting( 'products_per_page', 15 ) ) );
} );

add_action( 'valpress_head', function (): void {
	if ( is_admin() ) {
		return;
	}

	$settings = StorefrontSettings::all();

	echo '<style id="valpress-storefront-theme-vars">:root {'
		. '--vs-accent:' . e( $settings[ 'accent_color' ] ) . ';'
		. '--vs-accent-dark:' . e( $settings[ 'accent_dark_color' ] ) . ';'
		. '--vs-accent-soft:' . e( $settings[ 'accent_soft_color' ] ) . ';'
		. '--vs-radius:' . e( $settings[ 'border_radius' ] ) . ';'
		. '--vs-container:' . (int)$settings[ 'container_max_width' ] . 'px;'
		. '--vs-product-grid-min:' . (int)$settings[ 'product_grid_min_width' ] . 'px;'
		. '--vs-hero-start:' . e( $settings[ 'hero_gradient_start' ] ) . ';'
		. '--vs-hero-mid:' . e( $settings[ 'hero_gradient_mid' ] ) . ';'
		. '--vs-hero-end:' . e( $settings[ 'hero_gradient_end' ] ) . ';'
		. '}</style>';
}, 5 );
