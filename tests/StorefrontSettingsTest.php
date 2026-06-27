<?php

namespace Themes\ValpressStorefront\Tests;

use App\Core\ThemeManager;
use App\Models\Role;
use App\Models\User;
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

		Role::firstOrCreate(
			[ 'slug' => 'administrator' ],
			[ 'name' => 'Administrator' ]
		);
	}

	public function test_defaults_include_theme_layout_settings(): void
	{
		$defaults = StorefrontSettings::defaults();

		$this->assertSame( 1200, $defaults[ 'container_max_width' ] );
		$this->assertSame( '#0d9488', $defaults[ 'accent_color' ] );
		$this->assertArrayNotHasKey( 'products_per_page', $defaults );
	}

	public function test_settings_can_be_saved_and_retrieved(): void
	{
		StorefrontSettings::save( [
			'container_max_width' => 1280,
			'accent_color' => '#112233',
		] );

		$this->assertSame( 1280, StorefrontSettings::get( 'container_max_width' ) );
		$this->assertSame( '#112233', StorefrontSettings::get( 'accent_color' ) );
		$this->assertSame( 6, StorefrontSettings::get( 'footer_categories_count' ) );
	}

	public function test_partial_stored_settings_are_merged_with_defaults(): void
	{
		update_option( StorefrontSettings::OPTION_KEY, [
			'accent_color' => '#111111',
		] );

		$settings = StorefrontSettings::all();

		$this->assertSame( 1200, $settings[ 'container_max_width' ] );
		$this->assertSame( '#111111', $settings[ 'accent_color' ] );
	}

	public function test_ensure_installed_seeds_defaults(): void
	{
		StorefrontSettings::ensureInstalled();

		$this->assertSame( 1200, StorefrontSettings::get( 'container_max_width' ) );
	}

	public function test_admin_can_open_storefront_settings_when_theme_active(): void
	{
		$user = User::factory()->create();
		$user->assignRole( 'administrator' );

		$this->actingAs( $user )
			->get( route( 'admin.storefront.settings' ) )
			->assertOk()
			->assertSee( 'Content max width' )
			->assertSee( 'Accent color' )
			->assertDontSee( 'Homepage' )
			->assertDontSee( 'Home Page' )
			->assertDontSee( 'Posts per page' )
			->assertDontSee( 'Show homepage hero' );
	}

	public function test_admin_can_save_storefront_settings(): void
	{
		$user = User::factory()->create();
		$user->assignRole( 'administrator' );

		$payload = array_intersect_key( StorefrontSettings::defaults(), array_flip( StorefrontSettings::adminKeys() ) );
		$payload[ 'container_max_width' ] = 1400;
		$payload[ 'accent_color' ] = '#123456';

		$this->actingAs( $user )
			->post( route( 'admin.storefront.settings.store' ), $payload )
			->assertRedirect( route( 'admin.storefront.settings' ) );

		$this->assertSame( 1400, StorefrontSettings::get( 'container_max_width' ) );
		$this->assertSame( '#123456', StorefrontSettings::get( 'accent_color' ) );
	}

	public function test_homepage_runtime_defaults_remain_available(): void
	{
		StorefrontSettings::save( [
			'container_max_width' => 1400,
		] );

		$this->assertTrue( StorefrontSettings::bool( 'show_home_hero' ) );
		$this->assertSame( 8, StorefrontSettings::get( 'featured_products_count' ) );
	}
}
