# AGENTS.md

## Project
Laravel 11 ecommerce platform ("Atica") — women's clothing store. PHP 8.2, MySQL, Tailwind CSS + Vite (Blade + vanilla JS + Alpine.js), MercadoPago payments, Facebook Ads Conversion API.

## Commands

```bash
composer install          # PHP deps
npm install && npm run build  # Frontend deps + build
cp .env.example .env && php artisan key:generate

# Database (MySQL required, not SQLite in prod)
php artisan migrate --seed

# Dev
php artisan serve          # Laravel dev server
npm run dev                # Vite dev server (for HMR)

# Lint
./vendor/bin/pint          # Laravel Pint (no pint.json; use defaults)

# Test
php artisan test           # Runs phpunit (Unit + Feature suites)

# Queue worker (needed for async jobs — emails, FB events, stock liberation)
php artisan queue:work
```

## Architecture

### Admin panel
Routes under `/admin` (`routes/web.php:88`). Controllers in `app/Http/Controllers/Admin/`. Uses Yajra DataTables for product/category/order listings. Chart.js for dashboard stats. Log viewer at `/admin/logs`.

### Cart & checkout
Session-based cart stored in database (`SESSION_DRIVER=database`). `CartTrait` (`app/Traits/CartTrait.php`) provides shared cart logic. Coupons validated by `CouponService`. Payment flow: MercadoPago preference → webhook notification → order status update.

### Queue / jobs
Queue driver: `database`. Scheduled job `LiberateStockFromExpiredOrdersJob` runs daily at 05:00 (ARG time). All transactional emails and Facebook events are dispatched as jobs. Run `php artisan queue:work` to process them.

### Middleware aliases (registered in `bootstrap/app.php`)
- `set-cookie-unique-visitant` → `VisitCookie` (used on index and product pages)
- `cart-empty` → `CartEmptyMiddleware`
- `order-success` → `OrderSuccessMiddleware`
- `capture-visitor` → `CaptureVisitor`
- `register-unique-visitant` → `TrackUniqueVisit`

### Config files worth knowing
- `config/mercadopago.php` — `MERCADOPAGO_ACCESS_TOKEN`
- `config/facebook.php` — `FACEBOOK_ACCESS_TOKEN` + `FACEBOOK_PIXEL_ID`
- `config/ga.php` — `GTM_ID`
- `config/user.php` — custom user settings config

### Key services
- `MercadoPagoService` — creates payment preferences, handles IPN
- `FacebookAdsService` — sends purchase events to Facebook CAPI
- `CartService`, `CheckoutService`, `OrderService`, `CouponService` — core checkout pipeline

## Conventions
- Timezone: `America/Argentina/Buenos_Aires` (hardcoded in `config/app.php`)
- Indentation: 4 spaces (`.editorconfig`)
- Line endings: LF
- Tailwind content scan includes `./storage/framework/views/*.php` for compiled Blade views
- Vite entry points: `resources/css/app.css`, `resources/js/app.js`, plus `getSalesInfo.js`, `getVisitorsInfo.js`, `timeSelectListener.js`
- Puppeteer is installed as an npm dependency (likely for PDF/order export)

## Testing
- `phpunit.xml` does **not** override `DB_CONNECTION` (lines are commented out). Tests use whatever `.env` specifies. Use SQLite `:memory:` for speed if needed by uncommenting those lines.
- `phpunit.xml` already sets `QUEUE_CONNECTION=sync` and `SESSION_DRIVER=array` for tests.
