<?php

use Illuminate\Support\Facades\Route;
use Themes\ValpressStorefront\Controllers\Admin\SettingsController;

Route::middleware( [ 'web', 'auth', 'permission:manage_themes' ] )
	->prefix( 'admin/storefront' )
	->name( 'admin.storefront.' )
	->group( function () {
		Route::get( '/settings', [ SettingsController::class, 'index' ] )->name( 'settings' );
		Route::post( '/settings', [ SettingsController::class, 'store' ] )->name( 'settings.store' );
	} );
