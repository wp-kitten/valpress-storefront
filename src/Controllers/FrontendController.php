<?php

namespace Themes\ValpressStorefront\Controllers;

use App\Core\ScriptManager;
use App\Core\ThemeManager;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostType;
use App\Models\Setting;
use App\Models\Tag;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class FrontendController extends Controller
{
	protected ThemeManager $themeManager;

	public function __construct( ThemeManager $themeManager )
	{
		$this->themeManager = $themeManager;
	}

	public function category( $slug ): \Illuminate\Contracts\View\View|Factory|View
	{
		$category = Category::query()->where( 'slug', $slug )->firstOrFail();
		$postsPerPage = (int)Setting::get( 'posts_per_page', 10 );
		$locale = App::getLocale();

		$query = Post::with( [ 'author', 'categories', 'tags' ] )
			->where( 'post_type', 'post' )
			->where( 'post_status', 'publish' )
			->where( function ( $q ) {
				$q->whereNull( 'published_at' )
					->orWhere( 'published_at', '<=', now() );
			} )
			->whereHas( 'categories', function ( $q ) use ( $category ) {
				$q->where( 'categories.id', $category->id );
			} );

		$query = apply_filters( 'valpress_filter_by_locale', $query, 'post', $locale );

		$posts = $query->latest()->paginate( $postsPerPage );
		$allCategories = Category::all();

		$template = $this->themeManager->getTemplate( [ 'category', 'archive', 'index', 'frontend.category' ] );
		return view( $template ?: 'frontend.category', compact( 'posts', 'category', 'allCategories' ) );
	}

	public function tag( $slug ): \Illuminate\Contracts\View\View|Factory|View
	{
		$tag = Tag::query()->where( 'slug', $slug )->firstOrFail();
		$postsPerPage = (int)Setting::get( 'posts_per_page', 10 );
		$locale = App::getLocale();

		$query = Post::with( [ 'author', 'categories', 'tags' ] )
			->where( 'post_type', 'post' )
			->where( 'post_status', 'publish' )
			->where( function ( $q ) {
				$q->whereNull( 'published_at' )
					->orWhere( 'published_at', '<=', now() );
			} )
			->whereHas( 'tags', function ( $q ) use ( $tag ) {
				$q->where( 'tags.id', $tag->id );
			} );

		$query = apply_filters( 'valpress_filter_by_locale', $query, 'post', $locale );

		$posts = $query->latest()->paginate( $postsPerPage );
		$allCategories = Category::all();

		$template = $this->themeManager->getTemplate( [ 'tag', 'archive', 'index', 'frontend.tag' ] );
		return view( $template ?: 'frontend.tag', compact( 'posts', 'tag', 'allCategories' ) );
	}

	public function index( $slug = null )
	{
		$allCategories = Category::all();

		// If a slug is provided, check if it's a page
		if ( $slug ) {
			$post = Post::query()->where( 'post_slug', $slug )->where( 'post_type', 'page' )->first();

			// Check if this slug matches the designated Blog page
			if ( is_blog() ) {
				return $this->renderPostsPage();
			}

			return $this->show( $slug );
		}

		// Home route (/)
		if ( is_home() ) {
			if ( is_blog() ) {
				return $this->renderPostsPage( true );
			}

			$homePageId = Setting::get( 'home_page' );
			if ( $homePageId ) {
				$post = Post::query()->find( $homePageId );
				if ( $post && $post->post_status === 'publish' ) {
					return $this->renderSingle( $post, true );
				}
			}

			// Fallback if home page not found, but we are on the home route
			return $this->renderPostsPage( true );
		}
	}

	public function renderPostsPage(): \Illuminate\Contracts\View\View|Factory|View
	{
		$postsPerPage = (int)Setting::get( 'posts_per_page', 10 );
		$locale = App::getLocale();

		// Execute query directly (no caching)
		$query = Post::with( 'author' )
			->where( 'post_type', 'post' )
			->where( 'post_status', 'publish' )
			->where( function ( $q ) {
				$q->whereNull( 'published_at' )
					->orWhere( 'published_at', '<=', now() );
			} );

		$query = apply_filters( 'valpress_filter_by_locale', $query, 'post', $locale );

		$posts = $query->latest()
			->paginate( $postsPerPage );

		$allCategories = Category::all();

		$templates = [ 'frontend.blog', 'frontend.home', 'index', 'frontend.index' ];
		if ( is_home() ) {
			array_unshift( $templates, 'frontend.front-page' );
		}
		else {
			// If it's the blog page but not home, we might want the blog to have higher priority
			array_unshift( $templates, 'frontend.blog' );
		}

		// Clean up duplicates
		$templates = array_unique( $templates );

		$template = $this->themeManager->getTemplate( $templates );
		return view( $template ?: 'frontend.index', compact( 'posts', 'allCategories' ) );
	}

	public function archiveCPT( Request $request ): \Illuminate\Contracts\View\View|Factory|View
	{
		$postType = null;
		$path = $request->path();

		// Try to get post type from route name first (e.g., books.archive)
		$routeName = $request->route()->getName();
		if ( $routeName && str_contains( $routeName, '.archive' ) ) {
			$postType = str_replace( '.archive', '', $routeName );
		}

		// Fallback to path parsing
		if ( !$postType ) {
			$pathSegments = explode( '/', trim( $path, '/' ) );
			$postType = $pathSegments[ 0 ] ?? null;
		}

		$cpt = PostType::query()->where( 'slug', $postType )->firstOrFail();
		$postsPerPage = (int)Setting::get( 'posts_per_page', 10 );
		$locale = App::getLocale();

		$query = Post::with( [ 'author', 'categories', 'tags' ] )
			->where( 'post_type', $postType )
			->where( 'post_status', 'publish' )
			->where( function ( $q ) {
				$q->whereNull( 'published_at' )
					->orWhere( 'published_at', '<=', now() );
			} );

		$query = apply_filters( 'valpress_filter_by_locale', $query, 'post', $locale );

		$posts = $query->latest()->paginate( $postsPerPage );
		$allCategories = Category::all();

		$templates = [
			"archive-{$postType}",
			"frontend.archive-{$postType}",
			"archive-cpt",
			"frontend.archive-cpt",
			"archive",
			"frontend.archive",
			"blog",
			"frontend.blog",
			"index",
			"frontend.index",
		];

		$template = $this->themeManager->getTemplate( $templates );
		return view( $template ?: 'frontend.archive-cpt', compact( 'posts', 'cpt', 'allCategories', 'postType' ) );
	}

	public function show( $slug ): \Illuminate\Contracts\View\View|Factory|View|RedirectResponse
	{
		$post = $this->findPostBySlug( $slug, 'post' );
		return $this->renderPost( $post );
	}

	public function showCPT( Request $request, $slug ): \Illuminate\Contracts\View\View|Factory|View|RedirectResponse
	{
		$postType = null;
		$path = $request->path();

		// Try to get post type from route name first (e.g., books.show)
		$routeName = $request->route()->getName();
		if ( $routeName && str_contains( $routeName, '.show' ) ) {
			$postType = str_replace( '.show', '', $routeName );
		}

		// Fallback to path parsing if route name doesn't match
		if ( !$postType ) {
			$pathSegments = explode( '/', trim( $path, '/' ) );
			$postType = $pathSegments[ 0 ] ?? null;
		}

		$post = $this->findPostBySlug( $slug, $postType );
		return $this->renderPost( $post );
	}

	protected function findPostBySlug( string $slug, ?string $postType = null ): Post
	{
		$query = Post::query()->where( 'post_slug', $slug );

		if ( $postType ) {
			$query->where( 'post_type', $postType );
		}

		$posts = $query->get();

		if ( $posts->isEmpty() ) {
			abort( 404 );
		}

		if ( $posts->count() === 1 ) {
			return $posts->first();
		}

		$locale = App::getLocale();
		$defaultLocale = get_option( 'site_language', config( 'app.locale' ) );

		foreach ( $posts as $post ) {
			if ( apply_filters( 'valpress_get_locale', $defaultLocale, 'post', $post->id ) === $locale ) {
				return $post;
			}
		}

		foreach ( $posts as $post ) {
			if ( apply_filters( 'valpress_get_locale', $defaultLocale, 'post', $post->id ) === $defaultLocale ) {
				return $post;
			}
		}

		return $posts->first();
	}

	protected function renderPost( Post $post ): \Illuminate\Contracts\View\View|Factory|View|RedirectResponse
	{
		$currentLocale = App::getLocale();
		$defaultLocale = get_option( 'site_language', config( 'app.locale' ) );

		// i18n logic
		$translations = apply_filters( 'valpress_get_translations', [], 'post', $post->id );
		$postLocale = apply_filters( 'valpress_get_locale', null, 'post', $post->id );

		// 1. Redirect to the translated version if it exists and the current post is not in the current locale
		if ( $postLocale && $postLocale !== $currentLocale && isset( $translations[ $currentLocale ] ) ) {
			$translatedPost = Post::find( $translations[ $currentLocale ] );
			if ( $translatedPost && $translatedPost->post_status === 'publish' ) {
				return redirect()->to( valpress_get_permalink( $translatedPost ) );
			}
		}

		elseif ( $postLocale !== $currentLocale ) {
			if ( isset( $translations[ $currentLocale ] ) ) {
				$translatedPost = Post::find( $translations[ $currentLocale ] );
				if ( $translatedPost && $translatedPost->post_status === 'publish' ) {
					return redirect()->to( valpress_get_permalink( $translatedPost ) );
				}
			}
			elseif ( $postLocale !== $defaultLocale && isset( $translations[ $defaultLocale ] ) ) {
				$translatedPost = Post::find( $translations[ $defaultLocale ] );
				if ( $translatedPost && $translatedPost->post_status === 'publish' ) {
					return redirect()->to( valpress_get_permalink( $translatedPost ) );
				}
			}
		}

		// If post is not published or scheduled for the future, check if the user is allowed to view it
		$isFuture = $post->published_at && $post->published_at->isFuture();
		if ( $post->post_status !== 'publish' || $isFuture ) {
			$user = auth()->user();
			$canViewDraft = false;

			if ( $user ) {
				if ( $user->hasRole( 'administrator' ) || $user->hasRole( 'editor' ) ) {
					$canViewDraft = true;
				}
				elseif ( $user->id === $post->author_id ) {
					$canViewDraft = true;
				}
			}

			if ( !$canViewDraft ) {
				abort( 404 );
			}
		}

		// Increment view count
		$post->increment( 'post_views' );

		return $this->renderSingle( $post );
	}

	public function renderSingle( $post, $isFrontPage = false ): \Illuminate\Contracts\View\View|Factory|View
	{
		$allCategories = Category::all();

		// Check if page is an archive
		$isArchive = $post->getMeta( '_is_archive' );
		$archiveType = $post->getMeta( '_archive_post_type' ) ?: 'post';

		$templates = [];

		if ( $isArchive ) {
			$archiveView = apply_filters( 'valpress_page_archive_render', null, $post, $archiveType );

			if ( $archiveView instanceof \Illuminate\Contracts\View\View ) {
				return $archiveView;
			}

			ScriptManager::enqueueStyle( 'valpress-category', asset( 'themes/valpress-default/res/css/category.css' ), [ 'valpress-blog-common' ], '1.0.0' );
			$templates = [
				"archive-{$archiveType}",
				"frontend.archive-{$archiveType}",
				"archive",
				"frontend.archive",
			];
		}

		if ( $post->post_type === 'page' ) {
			if ( $isFrontPage ) {
				$templates[] = 'frontend.home';
				$templates[] = 'frontend.front-page';
			}

			$templates = array_merge( $templates, [
				"frontend.page-{$post->post_slug}",
				"frontend.page-{$post->id}",
				"frontend.page",
				"page-{$post->post_slug}",
				"page-{$post->id}",
			] );

			$templates = array_merge( $templates, [
				"page",
				"frontend.single",
				"single",
				"index",
			] );
		}
		else {
			$templates = array_merge( $templates, [
				"single-{$post->post_type}-{$post->post_slug}",
				"single-{$post->post_type}",
				"single",
				"frontend.single-{$post->post_type}-{$post->post_slug}",
				"frontend.single-{$post->post_type}",
				"frontend.single",
				"index",
			] );
		}

		if ( $isFrontPage ) {
			array_unshift( $templates, 'frontend.front-page' );
		}

		$template = $this->themeManager->getTemplate( $templates );
		$postType = $post->post_type === 'page' ? 'blog' : $post->post_type;
		$sidebar_pos = Setting::get( $postType . '_sidebar_position', Setting::get( 'blog_sidebar_position', 'right' ) );
		$layout = Setting::get( $postType . '_layout', Setting::get( 'blog_layout', 'grid' ) );

		return view( $template ?: 'frontend.single', compact( 'post', 'allCategories', 'sidebar_pos', 'layout' ) );
	}

	public function search( Request $request ): \Illuminate\Contracts\View\View|Factory|View
	{
		$query = $request->get( 's' );
		$postsPerPage = (int)Setting::get( 'posts_per_page', 10 );
		$locale = App::getLocale();

		$postsQuery = Post::with( [ 'author', 'categories', 'tags' ] )
			->where( 'post_status', 'publish' )
			->where( function ( $q ) {
				$q->whereNull( 'published_at' )
					->orWhere( 'published_at', '<=', now() );
			} )
			->where( function ( $q ) use ( $query ) {
				$q->where( 'post_title', 'like', "%{$query}%" )
					->orWhere( 'post_content', 'like', "%{$query}%" );
			} );

		$postsQuery = apply_filters( 'valpress_filter_by_locale', $postsQuery, 'post', $locale );

		$posts = $postsQuery->latest()->paginate( $postsPerPage );
		$allCategories = Category::all();

		$template = $this->themeManager->getTemplate( [ 'search', 'index', 'frontend.search' ] );
		return view( $template ?: 'frontend.search', compact( 'posts', 'query', 'allCategories' ) );
	}

	public function saveBlogSettings( Request $request )
	{
		if ( !auth()->check() || !auth()->user()->hasRole( 'administrator' ) ) {
			return response()->json( [ 'success' => false, 'message' => 'Unauthorized' ], 403 );
		}

		$post_type = $request->get( 'post_type', 'blog' );

		$settings = $request->only( [
			'layout',
			'sidebar_position',
			'show_search',
			'show_categories',
			'show_latest_posts',
			'show_tags',
			'show_newsletter',
		] );

		foreach ( $settings as $key => $value ) {
			Setting::set( $post_type . '_' . $key, $value, 'theme_settings' );
		}

		return response()->json( [ 'success' => true ] );
	}
}
