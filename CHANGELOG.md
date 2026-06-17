# Changelog

## 1.2.1 — 2026-06-15

### Navigation
- Primary navbar uses the **Primary Navigation** menu location (`primary`) via `MenuManager`, matching the default theme pattern
- Footer **Site** column uses the core **Footer Navigation** location (`footer`)
- Registered theme-specific **Store Footer Navigation** location (`storefront_store`) for the store footer column
- Sensible fallbacks (Home, Shop, Blog, cart links) when no menu is assigned to a location
- Helpers: `valpress_storefront_has_nav_menu()`, `valpress_storefront_render_primary_nav()`, `valpress_storefront_render_footer_menu()`

## 1.2.0 — 2026-06-15

Visual refresh and UX improvements across the storefront.

### Design system
- **DM Sans** typography via Google Fonts
- Refined color palette, shadows, button styles, and hover transitions
- Updated `storefront.css` and `shop.css` with expanded layout and component styles
- Theme design tokens (accent, radius, hero gradient) still driven by Storefront settings

### Home page
- Redesigned hero with improved layout and background treatment
- Trust feature strip below the hero (shipping, security, returns, support)
- **Shop by category** card grid
- Featured products section with clearer hierarchy
- Bottom **CTA banner** linking to the full catalog
- New partials: `home-features`, `home-categories`, `home-cta`

### Shop & catalog
- Catalog **hero banner** on the main shop index
- Product cards: square images, hover zoom, overlay CTA, sale badges, compare-at pricing
- Improved empty state spanning the full product grid
- Product detail page: prominent price block, trust signals, separated description section
- Shop archive hero eyebrow uses category/context name instead of a hardcoded label
- Search results: fixed duplicate product pagination

### Cart & checkout
- Card-based cart layout with product thumbnails and variant labels
- Stepped checkout sections with numbered cards
- **Same as billing address** toggle with mirrored shipping fields (`storefront.js`)
- Sticky order summary with line-item thumbnails
- Stripe scripts moved to document head via `@push('storefront_head')`
- Thank-you page: success icon, order summary styling, correct catalog continue link
- Reusable `checkout-address-fields` partial

### Assets
- Added `res/js/storefront.js` for checkout helpers
- Bumped asset versions to `1.2.0` in `functions.php`

### Fixes
- Google Fonts enqueue now passes a string version to `ScriptManager::enqueueStyle()` (fixes home page `TypeError`)

## 1.1.0 — 2026-06-14

- Initial polished storefront theme release
- ValPress Shop view overrides (catalog, product, cart, checkout, account)
- Homepage hero, featured products, and category navigation
- Admin Storefront settings (colors, hero, catalog options)
- Bootstrap 5 layout extending core `layouts.frontend`
