<?php

namespace Themes\ValpressStorefront;

class StorefrontSettings
{
	public const OPTION_KEY = 'valpress_storefront_settings';

	/**
	 * @return array<string, mixed>
	 */
	public static function defaults(): array
	{
		return [
			'featured_products_count' => 8,
			'footer_categories_count' => 6,
			'product_excerpt_length' => 90,
			'product_grid_min_width' => 240,
			'container_max_width' => 1200,
			'accent_color' => '#0d9488',
			'accent_dark_color' => '#0f766e',
			'accent_soft_color' => '#ccfbf1',
			'border_radius' => '1rem',
			'hero_gradient_start' => '#0f172a',
			'hero_gradient_mid' => '#134e4a',
			'hero_gradient_end' => '#0d9488',
			'show_home_hero' => true,
			'show_featured_products' => true,
			'show_hero_blog_button' => true,
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	public static function all(): array
	{
		$settings = array_replace( self::defaults(), self::storedSettings() );

		unset( $settings[ 'products_per_page' ] );

		return $settings;
	}

	/**
	 * @return array<string, mixed>
	 */
	protected static function storedSettings(): array
	{
		$stored = get_option( self::OPTION_KEY, [] );

		if ( $stored instanceof \stdClass ) {
			$stored = (array)$stored;
		}

		if ( !is_array( $stored ) ) {
			return [];
		}

		return $stored;
	}

	/**
	 * Seed defaults on first use so admin forms always have every key.
	 */
	public static function ensureInstalled(): void
	{
		if ( get_option( self::OPTION_KEY, false ) === false ) {
			update_option( self::OPTION_KEY, self::defaults() );
		}
	}

	public static function get( string $key, mixed $default = null ): mixed
	{
		$settings = self::all();

		if ( array_key_exists( $key, $settings ) ) {
			return $settings[ $key ];
		}

		return $default;
	}

	/**
	 * @param  array<string, mixed>  $values
	 */
	public static function save( array $values ): void
	{
		$persisted = array_intersect_key(
			array_merge( self::all(), $values ),
			array_flip( self::adminKeys() )
		);

		$runtime = array_diff_key( self::defaults(), array_flip( self::adminKeys() ) );

		update_option( self::OPTION_KEY, array_merge( $runtime, $persisted ) );
	}

	/**
	 * Settings exposed in the Storefront admin settings form.
	 *
	 * @return list<string>
	 */
	public static function adminKeys(): array
	{
		return [
			'footer_categories_count',
			'product_excerpt_length',
			'product_grid_min_width',
			'container_max_width',
			'accent_color',
			'accent_dark_color',
			'accent_soft_color',
			'border_radius',
			'hero_gradient_start',
			'hero_gradient_mid',
			'hero_gradient_end',
		];
	}

	public static function bool( string $key ): bool
	{
		return filter_var( self::get( $key ), FILTER_VALIDATE_BOOLEAN );
	}
}
