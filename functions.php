<?php

use App\Core\MenuManager;
use App\Core\ScriptManager;
use App\Core\ValPress;
use App\Models\Menu;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Themes\ValpressStorefront\StorefrontSettings;

ValPress::registerViews( __DIR__ . '/views', 'valpress-storefront' );
ValPress::registerTranslations( __DIR__ . '/lang', 'valpress-storefront' );

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

if ( !function_exists( 'valpress_storefront_shop_plugin_class' ) ) {
	/**
	 * @return class-string<\Plugins\ValPressShop\Plugin>|null
	 */
	function valpress_storefront_shop_plugin_class(): ?string
	{
		$class = 'Plugins\ValPressShop\Plugin';

		return class_exists( $class ) ? $class : null;
	}
}

if ( !function_exists( 'valpress_storefront_shop_available' ) ) {
	function valpress_storefront_shop_available(): bool
	{
		if ( !is_plugin_active( 'valpress-shop' ) ) {
			return false;
		}

		$pluginClass = valpress_storefront_shop_plugin_class();
		if ( $pluginClass === null ) {
			return false;
		}

		if ( !method_exists( $pluginClass, 'isEnabled' ) || !call_user_func( [ $pluginClass, 'isEnabled' ] ) ) {
			return false;
		}

		return Route::has( 'shop.index' );
	}
}

if ( !function_exists( 'valpress_storefront_shop_archive_type' ) ) {
	function valpress_storefront_shop_archive_type(): ?string
	{
		if ( !valpress_storefront_shop_available() ) {
			return null;
		}

		$class = 'Plugins\ValPressShop\Bootstrap\PageArchiveIntegration';
		if ( class_exists( $class ) ) {
			return $class::ARCHIVE_TYPE;
		}

		return apply_filters( 'valpress_storefront_shop_archive_type', 'product' );
	}
}

if ( !function_exists( 'valpress_storefront_is_home_shop_archive' ) ) {
	function valpress_storefront_is_home_shop_archive(): bool
	{
		$archiveType = valpress_storefront_shop_archive_type();
		if ( $archiveType === null ) {
			return false;
		}

		$homePageId = Setting::get( 'home_page' );
		if ( !$homePageId ) {
			return false;
		}

		$page = Post::query()->find( $homePageId );
		if ( !$page || $page->post_status !== 'publish' ) {
			return false;
		}

		return (bool)$page->getMeta( '_is_archive' )
			&& $page->getMeta( '_archive_post_type' ) === $archiveType;
	}
}

if ( !function_exists( 'valpress_storefront_catalog_url' ) ) {
	function valpress_storefront_catalog_url(): string
	{
		if ( !valpress_storefront_shop_available() ) {
			return route( 'home' );
		}

		if ( valpress_storefront_is_home_shop_archive() ) {
			return route( 'home' );
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

		$cartServiceClass = 'Plugins\ValPressShop\Services\CartService';
		if ( !class_exists( $cartServiceClass ) ) {
			return 0;
		}

		try {
			$cart = app( $cartServiceClass )->getOrCreateCart();

			return (int)$cart->items->sum( 'quantity' );
		} catch ( Throwable $e ) {
			return 0;
		}
	}
}

if ( !function_exists( 'valpress_storefront_shop_categories' ) ) {
	/**
	 * @return Collection<int, object>
	 */
	function valpress_storefront_shop_categories(): Collection
	{
		if ( !valpress_storefront_shop_available() ) {
			return collect();
		}

		$categoryClass = 'Plugins\ValPressShop\Models\Category';
		if ( !class_exists( $categoryClass ) ) {
			return collect();
		}

		$query = $categoryClass::query()->orderBy( 'sort_order' )->orderBy( 'name' );

		if ( class_exists( \Plugins\ValPressShop\Support\ShopI18n::class ) ) {
			$query = \Plugins\ValPressShop\Support\ShopI18n::applyStorefrontCategoryLocaleFilter( $query );
		}

		return $query->get();
	}
}

if ( !function_exists( 'valpress_storefront_featured_products' ) ) {
	/**
	 * @return Collection<int, object>
	 */
	function valpress_storefront_featured_products(): Collection
	{
		if ( !valpress_storefront_shop_available() || !valpress_storefront_setting_bool( 'show_featured_products' ) ) {
			return collect();
		}

		$productClass = 'Plugins\ValPressShop\Models\Product';
		if ( !class_exists( $productClass ) ) {
			return collect();
		}

		$query = $productClass::query()
			->published()
			->with( [ 'defaultVariant', 'categories' ] )
			->latest()
			->limit( (int)valpress_storefront_setting( 'featured_products_count', 8 ) );

		if ( class_exists( \Plugins\ValPressShop\Support\ShopI18n::class ) ) {
			$query = \Plugins\ValPressShop\Support\ShopI18n::applyStorefrontLocaleFilter( $query );
		}

		return $query->get();
	}
}

if ( !function_exists( 'valpress_storefront_search_products' ) ) {
	/**
	 * Search published shop products when the shop plugin is available.
	 */
	function valpress_storefront_search_products( string $query, ?int $perPage = null ): ?\Illuminate\Contracts\Pagination\LengthAwarePaginator
	{
		$query = trim( $query );
		if ( $query === '' || !valpress_storefront_shop_available() ) {
			return null;
		}

		$searchServiceClass = 'Plugins\ValPressShop\Services\ProductSearchService';
		if ( !class_exists( $searchServiceClass ) ) {
			return null;
		}

		$perPage ??= valpress_storefront_products_per_page();

		return app( $searchServiceClass )->search( [
			'q' => $query,
			'per_page' => $perPage,
		] );
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

if ( !function_exists( 'valpress_storefront_has_nav_menu' ) ) {
	function valpress_storefront_has_nav_menu( string $location ): bool
	{
		if ( !class_exists( Menu::class ) ) {
			return false;
		}

		$menu = Menu::query()->where( 'location', $location )->with( 'items' )->first();
		if ( !$menu || $menu->items->isEmpty() ) {
			return false;
		}

		return true;
	}
}

if ( !function_exists( 'valpress_storefront_render_primary_nav' ) ) {
	function valpress_storefront_render_primary_nav(): string
	{
		if ( class_exists( MenuManager::class ) ) {
			$html = trim( MenuManager::renderMenu( 'primary', [
				'container_class' => 'navbar-nav me-auto mb-2 mb-lg-0',
				'item_class' => 'nav-item',
				'link_class' => 'nav-link',
			] ) );

			if ( $html !== '' ) {
				return $html;
			}
		}

		return view( 'valpress-storefront::partials.nav-fallback' )->render();
	}
}

if ( !function_exists( 'valpress_storefront_render_footer_menu' ) ) {
	function valpress_storefront_render_footer_menu( string $location, ?string $fallbackView = null ): string
	{
		$args = apply_filters( 'valpress_storefront_footer_menu_args', [
			'container' => 'ul',
			'container_class' => 'vs-footer-links',
			'item_class' => '',
			'link_class' => '',
		], $location );

		$html = '';
		if ( class_exists( MenuManager::class ) ) {
			$html = trim( MenuManager::renderMenu( $location, $args ) );
		}

		if ( $html !== '' ) {
			return $html;
		}

		if ( $fallbackView !== null ) {
			return view( $fallbackView )->render();
		}

		return '';
	}
}

add_action( 'after_setup_theme', function (): void {
	StorefrontSettings::ensureInstalled();

	if ( class_exists( MenuManager::class ) ) {
		MenuManager::registerMenuLocation( 'storefront_store', __( 'Store Footer Navigation' ) );
	}
} );

add_action( 'valpress_enqueue_scripts', function (): void {
	ScriptManager::enqueueScript(
		'bootstrap5',
		asset( '3rd-party/bootstrap/js/bootstrap.bundle.min.js' ),
		[],
		'5.3.3',
		true
	);

	ScriptManager::enqueueStyle(
		'valpress-storefront-fonts',
		'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap',
		[],
		'1.2.0'
	);

	ScriptManager::enqueueStyle(
		'valpress-storefront',
		asset( 'themes/valpress-storefront/res/css/storefront.css' ),
		[ 'valpress-storefront-fonts' ],
		'1.2.0'
	);

	if ( valpress_storefront_should_load_shop_styles() ) {
		ScriptManager::enqueueStyle(
			'valpress-storefront-shop',
			asset( 'themes/valpress-storefront/res/css/shop.css' ),
			[ 'valpress-storefront' ],
			'1.2.0'
		);
	}

	ScriptManager::enqueueScript(
		'valpress-storefront',
		asset( 'themes/valpress-storefront/res/js/storefront.js' ),
		[],
		'1.2.0',
		true
	);
} );

add_filter( 'body_classes', function ( array $classes ): array {
	$classes[] = 'valpress-storefront-theme';

	if ( valpress_storefront_shop_available() && request()->routeIs( 'shop.*', 'cart.*', 'checkout.*', 'shop.account.*' ) ) {
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
	if ( !valpress_storefront_shop_available() || !Route::has( 'cart.index' ) ) {
		return $html;
	}

	$count = valpress_storefront_cart_count();

	$html .= view( 'valpress-storefront::partials.nav-shop-links', compact( 'count' ) )->render();

	return $html;
} );

add_filter( 'valpress_admin_menu_items', function ( array $items ): array {
	if ( !Route::has( 'admin.storefront.settings' ) ) {
		return $items;
	}

	$items[ 'settings-storefront' ] = [
		'id' => 'settings-storefront',
		'title' => __( 'valpress-storefront::messages.settings_title' ),
		'url' => fn () => route( 'admin.storefront.settings' ),
		'icon' => 'bi-circle',
		'order' => 40,
		'parent' => 'settings',
		'permission' => 'manage_options',
	];

	return $items;
} );

if ( !function_exists( 'valpress_storefront_products_per_page' ) ) {
	function valpress_storefront_products_per_page(): int
	{
		if ( valpress_storefront_shop_available() ) {
			$pluginClass = valpress_storefront_shop_plugin_class();
			if ( $pluginClass !== null && method_exists( $pluginClass, 'productsPerPage' ) ) {
				return $pluginClass::productsPerPage();
			}
		}

		return max( 1, min( 100, (int)apply_filters( 'valpress_shop_products_per_page', 15 ) ) );
	}
}

add_action( 'valpress_head', function (): void {
	if ( is_admin() ) {
		return;
	}

	$settings = StorefrontSettings::all();

	echo '<style id="valpress-storefront-theme-vars">:root {'
		. '--vs-accent:' . e( $settings[ 'accent_color' ] ) . ';'
		. '--vs-accent-dark:' . e( $settings[ 'accent_dark_color' ] ) . ';'
		. '--vs-accent-soft:' . e( $settings[ 'accent_soft_color' ] ) . ';'
		. '--vs-hero-start:' . e( $settings[ 'hero_gradient_start' ] ) . ';'
		. '--vs-hero-mid:' . e( $settings[ 'hero_gradient_mid' ] ) . ';'
		. '--vs-hero-end:' . e( $settings[ 'hero_gradient_end' ] ) . ';'
		. '}</style>';
}, 5 );
