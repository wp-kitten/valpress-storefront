<?php

namespace Themes\ValpressStorefront\Tests\Feature;

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

	protected function bootStorefrontTheme(): void
	{
		$manager = app( ThemeManager::class );
		$manager->setActiveTheme( 'valpress-storefront' );
		$manager->bootTheme();
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

	public function test_cms_reading_settings_are_not_shadowed_by_theme_admin_view(): void
	{
		$this->bootStorefrontTheme();

		$user = User::factory()->create();
		$user->assignRole( 'administrator' );

		$this->actingAs( $user )
			->get( route( 'admin.settings.index', [ 'tab' => 'reading' ] ) )
			->assertOk()
			->assertSee( 'Reading Settings' )
			->assertDontSee( 'Customize the ValPress Storefront theme' )
			->assertDontSee( 'Content max width' );
	}

	public function test_storefront_settings_menu_is_under_settings(): void
	{
		$this->bootStorefrontTheme();

		$user = User::factory()->create();
		$user->assignRole( 'administrator' );

		$this->actingAs( $user );

		$items = \App\Core\AdminMenu::getItems();

		$this->assertArrayHasKey( 'settings', $items );
		$childIds = collect( $items[ 'settings' ][ 'children' ] ?? [] )->pluck( 'id' )->all();

		$this->assertContains( 'settings-storefront', $childIds );
		$this->assertArrayNotHasKey( 'storefront', $items );
	}
}
