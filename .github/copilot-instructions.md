# Copilot Instructions for WOX Barbershop

## Project Overview

This is a Laravel 10.x application for managing a modern barbershop, featuring online booking, loyalty program, payment integration (Midtrans), and a comprehensive admin dashboard. The frontend uses Tailwind CSS, Vite, and Chart.js.

## Architecture & Key Components

-   **app/Http/Controllers/**: Route handlers, split into `Admin/`, `Auth/`, and booking logic.
-   **app/Models/**: Core data models (`User`, `Booking`, `Service`, `Transaction`, `Loyalty`).
-   **app/Services/**: Business logic abstractions.
-   **resources/views/**: Blade templates, organized by feature (admin, auth, profile).
-   **routes/web.php & api.php**: Web and API route definitions.
-   **config/**: Service integrations (Midtrans, mail, permissions, etc).
-   **database/migrations/**: Schema evolution.

## Developer Workflows

-   **Install dependencies**: `composer install` (PHP), `npm install` (Node)
-   **Environment setup**: Copy `.env.example` to `.env`, run `php artisan key:generate`
-   **Database**: `php artisan migrate` and `php artisan db:seed` (optional)
-   **Build assets**: `npm run dev` (development), `npm run build` (production)
-   **Run servers**: `php artisan serve` (Laravel), `npm run dev` (Vite)
-   **Testing**: `php artisan test` (all), `php artisan test --filter BookingTest` (specific)
-   **Troubleshooting**: See README for common issues (CORS, permissions, memory, logs)

## Patterns & Conventions

-   **Controllers**: Use feature-based subfolders (e.g., `Admin/`, `Auth/`).
-   **Models**: Eloquent ORM, relationships defined in model classes.
-   **Services**: Place reusable business logic in `app/Services/`.
-   **Views**: Blade templates, grouped by feature.
-   **API**: RESTful endpoints in `routes/api.php`, see README for endpoint list.
-   **Testing**: Feature and unit tests in `tests/Feature/` and `tests/Unit/`.
-   **Exports**: Data export logic in `app/Exports/` (Excel, PDF, CSV).
-   **Notifications**: Use Laravel notifications for user/admin alerts.

## Integrations

-   **Midtrans**: Payment gateway, configured in `.env` and `config/midtrans.php`.
-   **Mail**: SMTP setup in `.env` and `config/mail.php`.
-   **DataTables**: For admin data tables, see `app/DataTables/` and related JS.
-   **Chart.js**: Analytics in admin dashboard views.
-   **SweetAlert2**: UI notifications.

## Examples

-   **Booking flow**: See `BookingController.php`, `routes/api.php`, and related Blade views.
-   **Loyalty logic**: `Loyalty.php` model, points awarded per booking.
-   **Export**: `app/Exports/` for Excel/PDF/CSV logic.

## Tips for AI Agents

-   Always check for feature-specific folders (e.g., `Admin/`, `Auth/`, `Services/`).
-   Reference config files for integration details.
-   Use migrations and seeders for DB setup.
-   Follow PSR-12 coding standards and add comments for complex logic.
-   See README for troubleshooting and workflow commands.

---

For questions or unclear conventions, ask the user for clarification or examples from their workflow.
