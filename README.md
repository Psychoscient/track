# School Events Tracker

TRACK is a PHP/MySQL school events tracker for UST-style campus event access. The current codebase includes authentication, role-based permissions, user management, event publishing, organizer applications, normalized event venues, password reset email, and a Tailwind-styled frontend.

There is no root `index.php` or router. The application is accessed directly through the PHP files in `views/`.

## Current Scope

Implemented:

- User signup, login, logout, and session-based access control
- Role and permission lookup at login
- Admin user create/update/delete workflow
- Admin dashboard with user metrics, charts, and organizer application review
- Organizer application submission from regular users
- Organizer approval/rejection by admins
- Event listing for authenticated users
- Event create/update/delete for users with `manage_events`
- Admin event management workspace with event analytics charts
- Organizer-owned event management
- Published-only event browsing for users without event-management permission
- Event categories, statuses, and normalized venue lookup
- Venue conflict validation for event schedules
- Password reset by email through PHPMailer
- Database error logging
- Tailwind CSS build pipeline

Not implemented/configured:

- Root landing page or front controller
- Automated tests
- Full base database schema migrations for auth/user tables
- Environment-driven database connection

## Stack

- PHP with PDO
- MySQL or MariaDB
- PHPMailer
- `vlucas/phpdotenv`
- jQuery
- SweetAlert2
- Chart.js
- Font Awesome
- Tailwind CSS
- PostCSS + Autoprefixer

## Project Layout

```text
Track/
|-- bl/                     Business-layer managers
|-- config/                 Mail configuration
|-- controllers/            AJAX/form controller endpoint
|-- database/migrations/    Event, venue, and organizer-application SQL migrations
|-- helper/                 Email helper
|-- model/                  PDO-backed data access layer
|   `-- config/Database.php Database connection
|-- public/                 Compiled CSS and public images
|-- references/             Local project notes/design references
|-- script/                 Browser-side JavaScript
|-- vendor/                 Composer dependencies
|-- views/                  PHP pages and partials
|   |-- dashboard.php
|   |-- event-management.php
|   |-- events.php
|   |-- forgot-password.php
|   |-- home.php
|   |-- login.php
|   |-- reset-password.php
|   |-- signup.php
|   `-- unauthorized.php
|-- composer.json
|-- package.json
|-- postcss.config.js
|-- tailwind.config.js
`-- README.md
```

## Key Entry Points

- Login: `/Track/views/login.php`
- Signup: `/Track/views/signup.php`
- Authenticated home: `/Track/views/home.php`
- Events: `/Track/views/events.php`
- Admin dashboard and user management: `/Track/views/dashboard.php`
- Admin event management: `/Track/views/event-management.php`
- Controller endpoint: `/Track/controllers/controller.php`

## Setup

### Requirements

- PHP 8+
- MySQL or MariaDB
- Composer
- Node.js and npm
- A local web server such as XAMPP

### 1. Install dependencies

```bash
composer install
npm install
```

### 2. Configure the database

The database connection is currently hardcoded in [model/config/Database.php](/C:/xampp/htdocs/Track/model/config/Database.php:1):

```text
host: localhost
database: track_db
username: root
password: empty string
```

Update that file if your local MySQL credentials differ.

The repository now includes feature migrations in `database/migrations/`, but it still does not include a complete base schema for the original auth/user tables. The app expects these base tables to exist:

- `tbl_users`
- `tbl_roles`
- `tbl_permissions`
- `tbl_role_permissions`
- `tbl_year_lvl`
- `tbl_error_logs`

`tbl_users` is expected to include password-reset columns:

- `reset_token`
- `reset_token_expiry`

Feature migrations currently checked in:

- `2026_05_10_events.sql` creates event categories, event statuses, events, and the `manage_events` permission assignments for roles `1` and `3`.
- `2026_05_10_event_status_normalization.sql` migrates older event status data into `tbl_event_status`.
- `2026_05_10_organizer_applications.sql` creates organizer application statuses and organizer applications.
- `2026_05_17_event_venues_normalization.sql` creates normalized event venues and links events to venues.

For a fresh database, create the base auth/user tables first, then apply the relevant feature migrations. The status-normalization migration is intended for older databases that had pre-normalized event status data.

### 3. Configure mail

Password reset uses Gmail SMTP through PHPMailer and reads credentials from `.env`.

Expected variables:

```env
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-app-password
MAIL_FROM=your-email@example.com
```

These values are loaded in [config/mail.php](/C:/xampp/htdocs/Track/config/mail.php:1).

### 4. Build Tailwind CSS

Tailwind reads PHP and JavaScript files through [tailwind.config.js](/C:/xampp/htdocs/Track/tailwind.config.js:1), then compiles [views/css/input.css](/C:/xampp/htdocs/Track/views/css/input.css:1) into [public/output.css](/C:/xampp/htdocs/Track/public/output.css:1).

```bash
npm run build
```

The current `build` script runs Tailwind in `--watch` mode, so it stays running until stopped.

### 5. Run the app

If the project folder is inside `C:\xampp\htdocs\Track`, start Apache and MySQL in XAMPP, then open:

```text
http://localhost/Track/views/login.php
```

## Roles And Permissions

The code currently treats these role IDs as meaningful:

- `1`: Admin
- `2`: Regular user
- `3`: Organizer

Permissions are loaded into `$_SESSION['permissions']` during login and refreshed on some authenticated pages.

Current permission behavior:

- `manage_users` allows access to `views/dashboard.php` and organizer application review.
- `manage_events` allows event creation and event update/delete actions.
- Admin role `1` can manage all events.
- Organizer role `3` can manage events they created.
- Users without `manage_events` can browse published events only.

Frontend login redirects are defined in [script/utils.js](/C:/xampp/htdocs/Track/script/utils.js:1):

- role `1` -> `views/dashboard.php`
- role `2` -> `views/home.php`
- role `3` -> `views/home.php`

## Controller Actions

All AJAX/form actions post to [controllers/controller.php](/C:/xampp/htdocs/Track/controllers/controller.php:1).

Supported actions:

- `signup`
- `login`
- `logout`
- `create`
- `update`
- `delete`
- `event-create`
- `event-update`
- `event-delete`
- `organizer-apply`
- `organizer-approve`
- `organizer-reject`
- `forgot-password`
- `reset-password`

## Event Workflow

- `views/events.php` is the shared authenticated event page.
- Users without `manage_events` see published events only.
- Organizers with `manage_events` can create events and manage their own events.
- Admins can access `views/event-management.php` for the full event registry and event analytics.
- Events require a category, venue, start/end date, status, and description.
- New event start times cannot be in the past.
- Event descriptions are limited to 300 characters during creation.
- Venue/time conflicts are rejected before creating or updating an event.

## Organizer Workflow

- Regular users apply from `views/home.php`.
- Pending applications are listed in the admin dashboard.
- Admins approve or reject applications from `views/dashboard.php`.
- Approval promotes the applicant to role `3`.
- Rejected users can submit a new application.

## Frontend Notes

- Main styles are compiled to `public/output.css`.
- Auth pages use shared partials in `views/partials/auth/`.
- The app depends on CDN-hosted jQuery, SweetAlert2, Chart.js, Font Awesome, and Google Fonts.
- Public auth imagery is stored under `public/images/auth/`.
- `views/css/output.css` also exists, but the PHP pages currently link to `public/output.css`.

## Known Gaps

- The base auth/user database schema is not fully versioned in this repository.
- Database credentials are hardcoded in PHP instead of `.env`.
- `composer.lock`, `package.json`, `package-lock.json`, `public/output.css`, and `references/` are listed in `.gitignore` even though some are present in this checkout.
- No automated test suite or lint script is configured.
- The password-reset controller builds a lowercase `/track/...` reset URL while the documented local path is `/Track/...`.
