<?php

namespace Themes\ValpressStorefront\Controllers\Admin;

use App\Core\AdminMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Themes\ValpressStorefront\StorefrontSettings;

class SettingsController extends Controller
{
	public function index(): View
	{
		AdminMenu::setActive( 'storefront' );

		return view( 'valpress-storefront::admin.settings', [
			'settings' => StorefrontSettings::all(),
		] );
	}

	public function store( Request $request ): RedirectResponse
	{
		$data = $request->validate( [
			'products_per_page' => 'required|integer|min:1|max:100',
			'featured_products_count' => 'required|integer|min:0|max:48',
			'footer_categories_count' => 'required|integer|min:0|max:24',
			'product_excerpt_length' => 'required|integer|min:20|max:500',
			'product_grid_min_width' => 'required|integer|min:160|max:400',
			'container_max_width' => 'required|integer|min:960|max:1600',
			'accent_color' => [ 'required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/' ],
			'accent_dark_color' => [ 'required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/' ],
			'accent_soft_color' => [ 'required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/' ],
			'border_radius' => 'required|string|max:20',
			'hero_gradient_start' => [ 'required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/' ],
			'hero_gradient_mid' => [ 'required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/' ],
			'hero_gradient_end' => [ 'required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/' ],
		] );

		StorefrontSettings::save( array_merge( $data, [
			'show_home_hero' => $request->boolean( 'show_home_hero' ),
			'show_featured_products' => $request->boolean( 'show_featured_products' ),
			'show_hero_blog_button' => $request->boolean( 'show_hero_blog_button' ),
		] ) );

		return redirect()
			->route( 'admin.storefront.settings' )
			->with( 'success', __( 'Storefront settings saved.' ) );
	}
}
