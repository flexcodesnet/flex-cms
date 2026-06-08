FlexCMS
===============

Open-source [FlexCMS](https://cms.flexcodes.net/) — a flexible Laravel CMS with a role-based admin panel.

## Requirements

- PHP **8.3+**
- Composer **2.x**
- MySQL **5.7+** / MariaDB **10.3+**
- Node.js **18+** and npm (for Vite front-end assets)

## Quick Start

1. Install [PHP](https://www.php.net/downloads.php/) and [Composer](https://getcomposer.org/download/).
2. Clone or [download](https://github.com/flexcodesnet/flex-cms/archive/main.zip) this repository.
3. From the project root, run:

```bash
composer install
cp .env.example .env   # Windows: copy .env.example .env
php artisan key:generate
```

4. Configure your database, timezone, and seed passwords in `.env`:

```env
APP_TIMEZONE=UTC

DB_DATABASE=flex_cms_db
DB_USERNAME=root
DB_PASSWORD=

SEED_ROOT_PASSWORD=your-strong-root-password
SEED_ADMIN_PASSWORD=your-strong-admin-password
```

`APP_TIMEZONE` sets the application default timezone (PHP timezone identifier, e.g. `UTC`, `Europe/London`, `Asia/Dubai`). Defaults to `UTC` if omitted.

5. Install front-end dependencies and build assets:

```bash
npm install
npm run build
```

6. Create the database, then migrate and seed:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

7. Start the application:

```bash
php artisan serve
```

8. Open the admin panel at [http://localhost:8000/panel/login](http://localhost:8000/panel/login) and sign in with:

| Account | Email / username        | Password                          |
|---------|-------------------------|-----------------------------------|
| Root    | `root@flexcodes.net`    | value of `SEED_ROOT_PASSWORD`     |
| Admin   | `admin@flexcodes.net`   | value of `SEED_ADMIN_PASSWORD`    |

You can log in with either the email or the username. If `SEED_*` variables are not set, `db:seed` generates random passwords and prints them in the console.

> **Laragon / virtual host:** If the app is served from a subdirectory (e.g. `http://localhost/flexcms/public/`), set `APP_URL` in `.env` accordingly and run `npm run build` so Vite assets resolve correctly.

## Development

Panel CSS and JS are built with Vite from:

- `resources/css/panel.css`
- `resources/js/panel.js`

For hot reload during development, run both:

```bash
php artisan serve
npm run dev
```

Compiled production assets are written to `public/build/` (gitignored). Run `npm run build` before deploying or whenever those source files change.

## Tech Stack

- **Laravel 12**
- **Vite 6** for panel CSS/JS
- **AdminLTE 3** admin theme
- **Laravel Sanctum** for API authentication
- **Yajra DataTables** for admin listings
- Role & permission-based access control

## Features

- Modern admin panel with RTL support
- Multi-language ready
- Dashboard home with permission-aware module cards
- Users, roles, and permissions management
- Settings management
- Speed and page-optimization middleware

## Production Checklist

Before deploying, ensure:

- `APP_DEBUG=false`
- `APP_ENV=production`
- `APP_TIMEZONE` matches your deployment region
- Strong `SEED_*` passwords are set and default seeded accounts are reviewed
- `SESSION_SECURE_COOKIE=true` when using HTTPS
- `npm run build` has been run (assets in `public/build/` are not committed)
- `php artisan config:cache`, `route:cache`, and `view:cache`

## About FlexCMS

FlexCMS is a multipurpose Laravel CMS for individuals and businesses who need a manageable web presence without writing code. The admin panel is designed for clarity and speed — build and maintain your site through an intuitive interface.

Admin panel and frontend support RTL layouts. Multi-language support is built in. The stack is built on Laravel and AdminLTE.

## Contributing

[Flexcodes](https://flexcodes.net/) welcomes contributions to the open-source community.

If you find FlexCMS helpful, consider supporting the project:

[!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://www.buymeacoffee.com/flexcodes)

## Support

Report bugs or feature ideas to [support@flexcodes.net](mailto:support@flexcodes.net).

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md) for details.
