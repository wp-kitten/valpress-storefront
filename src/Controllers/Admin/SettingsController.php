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
		AdminMenu::setActive( 'settings-storefront' );

		return view( 'valpress-storefront::admin.storefront-settings', [
			'storefrontSettings' => StorefrontSettings::all(),
		] );
	}

	public function store( Request $request ): RedirectResponse
	{
		$data = $request->validate( [
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

		StorefrontSettings::save( $data );

		return redirect()
			->route( 'admin.storefront.settings' )
			->with( 'success', __( 'valpress-storefront::messages.settings_saved' ) );
	}
}
