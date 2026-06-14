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
			'products_per_page' => 15,
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
		$stored = get_option( self::OPTION_KEY, [] );

		if ( !is_array( $stored ) ) {
			$stored = [];
		}

		return array_merge( self::defaults(), $stored );
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
		$merged = array_merge( self::all(), $values );

		update_option( self::OPTION_KEY, $merged );
	}

	public static function bool( string $key ): bool
	{
		return filter_var( self::get( $key ), FILTER_VALIDATE_BOOLEAN );
	}
}
