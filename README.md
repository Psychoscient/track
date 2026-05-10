# School Events Tracker

This repository is currently a PHP/MySQL user access system for the School Events Tracker project. The codebase includes authentication, role-based permissions, admin user management, password reset by email, and a Tailwind-styled frontend.

It does not currently include implemented event management. The `views/events.php` page exists, but it is empty, and there is no event CRUD wired into the controller flow.

## Current Scope

Implemented today:

- User signup
- User login/logout
- Session-based access control
- Role/permission lookup during login
- Admin-only dashboard
- Admin user create/update/delete
- Year level lookup for user records
- Password reset email flow
- Error logging to the database
- Tailwind CSS build pipeline for the PHP views

Not implemented yet:

- Event listing UI
- Event creation/update/delete
- Public landing page or root `index.php`
- Automated tests
- Database migrations or seed scripts

## Stack

- PHP
- MySQL
- PDO
- PHPMailer
- `vlucas/phpdotenv`
- jQuery
- SweetAlert2
- Chart.js
- Tailwind CSS
- PostCSS + Autoprefixer

## Project Layout

```text
Track/
|-- bl/                  Business-layer managers
|-- config/              Mail configuration
|-- controllers/         AJAX/form controller entrypoint
|-- database/            Present, but no migrations are currently checked in
|-- helper/              Email helper
|-- model/               PDO-backed data access layer
|   `-- config/Database.php
|-- public/              Compiled frontend assets
|-- script/              Browser-side JavaScript
|-- vendor/              Composer dependencies
|-- views/               PHP pages
|   |-- login.php
|   |-- signup.php
|   |-- home.php
|   |-- dashboard.php
|   |-- forgot-password.php
|   |-- reset-password.php
|   `-- unauthorized.php
|-- .env                 Mail credentials
|-- composer.json
|-- package.json
|-- tailwind.config.js
`-- README.md
```

## Key Entry Points

- Login page: `/Track/views/login.php`
- Signup page: `/Track/views/signup.php`
- Auth/controller endpoint: `/Track/controllers/controller.php`
- Admin dashboard: `/Track/views/dashboard.php`
- Authenticated home page: `/Track/views/home.php`

There is no root router or front controller in the repository right now, so accessing the app starts from the `views/` pages directly.

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

The database connection is hardcoded in [model/config/Database.php](/C:/xampp/htdocs/Track/model/config/Database.php:1):

```php
host: localhost
database: track_db
username: root
password: ''
```

Update that file if your local MySQL credentials differ.

The repository does not currently include SQL migrations or a schema dump. Based on the code, the app expects at least these tables:

- `tbl_users`
- `tbl_roles`
- `tbl_permissions`
- `tbl_role_permissions`
- `tbl_year_lvl`
- `tbl_error_logs`

`tbl_users` also needs reset-password fields used by the current flow:

- `reset_token`
- `reset_token_expiry`

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

The frontend build watches `views/css/input.css` and writes the compiled stylesheet to `public/output.css`.

```bash
npm run build
```

Note: the current `build` script runs Tailwind in `--watch` mode, so it stays running until you stop it.

### 5. Run the app

If the project folder is inside `C:\xampp\htdocs\Track`, start Apache and MySQL, then open:

```text
http://localhost/Track/views/login.php
```

## Permission Model

Permissions are loaded at login and stored in `$_SESSION['permissions']`.

Current page guards:

- `views/dashboard.php` requires the `manage_users` permission
- `views/home.php` requires an authenticated session with permissions present
- `views/unauthorized.php` is the fallback page for blocked access

The frontend redirect logic currently sends:

- role `1` -> admin dashboard
- role `2` -> home page
- role `3` -> home page

See [script/utils.js](/C:/xampp/htdocs/Track/script/utils.js:1).

## Frontend Notes

- Styling is compiled with Tailwind from [views/css/input.css](/C:/xampp/htdocs/Track/views/css/input.css:1) into [public/output.css](/C:/xampp/htdocs/Track/public/output.css:1).
- The UI also depends on CDN-hosted jQuery, SweetAlert2, Chart.js, Font Awesome, and Google Fonts.
- `views/events.php` is currently blank and should be treated as unfinished.

## Known Gaps

- README claims from older revisions about event CRUD are no longer accurate for the checked-in code.
- The database schema is not versioned in the repo.
- The database connection is not environment-driven.
- No test suite or lint script is configured.
- Some user-facing strings and validation behavior still need cleanup.
