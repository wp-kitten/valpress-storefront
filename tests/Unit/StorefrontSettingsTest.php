<?php

namespace Themes\ValpressStorefront\Tests\Unit;

use App\Core\ThemeManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Themes\ValpressStorefront\StorefrontSettings;

class StorefrontSettingsTest extends TestCase
{
	use RefreshDatabase;

	protected function setUp(): void
	{
		parent::setUp();

		app( ThemeManager::class )->bootThemeResources( 'valpress-storefront' );
	}

	public function test_defaults_include_theme_color_settings(): void
	{
		$defaults = StorefrontSettings::defaults();

		$this->assertSame( '#0d9488', $defaults[ 'accent_color' ] );
		$this->assertSame( 90, $defaults[ 'product_excerpt_length' ] );
		$this->assertArrayNotHasKey( 'products_per_page', $defaults );
		$this->assertArrayNotHasKey( 'container_max_width', $defaults );
		$this->assertArrayNotHasKey( 'product_grid_min_width', $defaults );
	}

	public function test_settings_can_be_saved_and_retrieved(): void
	{
		StorefrontSettings::save( [
			'product_excerpt_length' => 120,
			'accent_color' => '#112233',
		] );

		$this->assertSame( 120, StorefrontSettings::get( 'product_excerpt_length' ) );
		$this->assertSame( '#112233', StorefrontSettings::get( 'accent_color' ) );
		$this->assertSame( 6, StorefrontSettings::get( 'footer_categories_count' ) );
	}

	public function test_partial_stored_settings_are_merged_with_defaults(): void
	{
		update_option( StorefrontSettings::OPTION_KEY, [
			'accent_color' => '#111111',
		] );

		$settings = StorefrontSettings::all();

		$this->assertSame( 90, $settings[ 'product_excerpt_length' ] );
		$this->assertSame( '#111111', $settings[ 'accent_color' ] );
		$this->assertArrayNotHasKey( 'container_max_width', $settings );
	}

	public function test_ensure_installed_seeds_defaults(): void
	{
		StorefrontSettings::ensureInstalled();

		$this->assertSame( 6, StorefrontSettings::get( 'footer_categories_count' ) );
		$this->assertSame( '#0d9488', StorefrontSettings::get( 'accent_color' ) );
	}

	public function test_homepage_runtime_defaults_remain_available(): void
	{
		StorefrontSettings::save( [
			'accent_color' => '#123456',
		] );

		$this->assertTrue( StorefrontSettings::bool( 'show_home_hero' ) );
		$this->assertSame( 8, StorefrontSettings::get( 'featured_products_count' ) );
	}
}
