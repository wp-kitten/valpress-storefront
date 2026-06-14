<?php

namespace Themes\ValpressStorefront\Tests;

use App\Core\ThemeManager;
use Tests\TestCase;

class ThemeTest extends TestCase
{
	public function test_storefront_theme_is_valid(): void
	{
		$manager = app( ThemeManager::class );
		$result = $manager->validate( public_path( 'themes/valpress-storefront' ) );

		$this->assertSame( true, $result );
	}

	public function test_storefront_theme_config_has_required_fields(): void
	{
		$config = require public_path( 'themes/valpress-storefront/config.php' );

		foreach ( [ 'name', 'short_description', 'long_description', 'version', 'author', 'compat_min', 'screenshot', 'thumbnail' ] as $field ) {
			$this->assertArrayHasKey( $field, $config );
			$this->assertNotEmpty( $config[ $field ] );
		}
	}
}
