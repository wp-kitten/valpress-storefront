<?php

use Illuminate\Support\Facades\Route;
use Plugins\ValPressI18n\Middleware\SetLocale;
use Themes\ValpressStorefront\Controllers\FrontendController;

$middleware = [ 'web' ];
if ( class_exists( SetLocale::class ) ) {
    $middleware[] = 'set_locale';
}

Route::middleware( $middleware )->group( function () {
    Route::get( '/', [ FrontendController::class, 'index' ] )->name( 'home' );
    Route::get( '/blog', [ FrontendController::class, 'renderPostsPage' ] )->name( 'blog' );
    Route::get( '/search', [ FrontendController::class, 'search' ] )->name( 'search' );
    Route::get( '/category/{slug}', [ FrontendController::class, 'category' ] )->name( 'category.show' );
    Route::get( '/tag/{slug}', [ FrontendController::class, 'tag' ] )->name( 'tag.show' );
    Route::get( '/blog/{slug}', [ FrontendController::class, 'show' ] )->name( 'post.show' );
    Route::post( '/admin/blog-settings/save', [ FrontendController::class, 'saveBlogSettings' ] )->name( 'admin.blog.settings.save' );

    // This must be the last route
    if ( !is_admin() ) {
        // We move the page.show route registration to a filter so that plugins
        // can register their routes BEFORE this catch-all route.
        // We use valpress_init, which is fired after plugins and theme are loaded.
        add_action( 'valpress_init', function () {
            Route::get( '/{slug}', [ FrontendController::class, 'index' ] )
                ->middleware( 'web' )
                ->where( 'slug', '^(?!login|register|password|admin|maintenance).*$' )
                ->name( 'page.show' );
        }, 1000 );
    }
} );
