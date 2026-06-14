<?php

namespace Themes\ValpressStorefront\Tests;

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

		Role::firstOrCreate(
			[ 'slug' => 'administrator' ],
			[ 'name' => 'Administrator' ]
		);
	}

	public function test_defaults_include_catalog_settings(): void
	{
		$defaults = StorefrontSettings::defaults();

		$this->assertSame( 15, $defaults[ 'products_per_page' ] );
		$this->assertSame( 8, $defaults[ 'featured_products_count' ] );
		$this->assertSame( 6, $defaults[ 'footer_categories_count' ] );
	}

	public function test_settings_can_be_saved_and_retrieved(): void
	{
		StorefrontSettings::save( [
			'products_per_page' => 20,
			'featured_products_count' => 4,
		] );

		$this->assertSame( 20, StorefrontSettings::get( 'products_per_page' ) );
		$this->assertSame( 4, StorefrontSettings::get( 'featured_products_count' ) );
		$this->assertSame( 6, StorefrontSettings::get( 'footer_categories_count' ) );
	}

	public function test_admin_can_open_storefront_settings_when_theme_active(): void
	{
		\App\Core\ValPress::registerRoutes( public_path( 'themes/valpress-storefront/routes/admin.php' ) );

		$user = User::factory()->create();
		$user->assignRole( 'administrator' );

		$this->actingAs( $user )
			->get( route( 'admin.storefront.settings' ) )
			->assertOk()
			->assertSee( 'Products per page' );
	}

	public function test_admin_can_save_storefront_settings(): void
	{
		\App\Core\ValPress::registerRoutes( public_path( 'themes/valpress-storefront/routes/admin.php' ) );

		$user = User::factory()->create();
		$user->assignRole( 'administrator' );

		$payload = array_merge( StorefrontSettings::defaults(), [
			'products_per_page' => 30,
			'show_home_hero' => '1',
			'show_featured_products' => '1',
			'show_hero_blog_button' => '0',
		] );

		$this->actingAs( $user )
			->post( route( 'admin.storefront.settings.store' ), $payload )
			->assertRedirect( route( 'admin.storefront.settings' ) );

		$this->assertSame( 30, StorefrontSettings::get( 'products_per_page' ) );
		$this->assertFalse( StorefrontSettings::bool( 'show_hero_blog_button' ) );
	}
}
