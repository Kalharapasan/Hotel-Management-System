# LuxeStay — Hotel Management System

A PHP MVC web application for managing hotel bookings, rooms, flights, trips, a restaurant menu, and customers, with a full admin dashboard.

## Features

**Public site**
- Browse and search hotels by name/location
- View and book rooms
- Browse flights and trips
- Order from the restaurant menu
- User registration, login, and profile (with booking/order history)
- About and Contact pages

**Admin dashboard** (`/admin`)
- Manage hotels, rooms, categories, customers, and employees
- Manage flights and trips
- Manage restaurant menu items and order statuses
- View bookings, generate bills, and track billing/payments
- Edit homepage/about/contact site content

## Tech Stack

- PHP 8.x (no framework — custom lightweight MVC)
- MySQL / MariaDB (via `mysqli`)
- Tailwind CSS (via CDN) for styling
- Vanilla JS

## Requirements

- PHP 8.0+
- MySQL or MariaDB
- Apache with `mod_rewrite` enabled (XAMPP/WAMP/MAMP all work)

## Installation

1. **Place the project** in your web server's document root, e.g. for XAMPP:
   ```
   C:/xampp/htdocs/Hotel-Management-System/
   ```
   You can run it either at the domain root (`http://localhost/`) or inside a subfolder (`http://localhost/Hotel-Management-System/`) — the app auto-detects its own base path, so both work without extra configuration.

2. **Enable `mod_rewrite`** in Apache (`httpd.conf`):
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
   and make sure `AllowOverride All` is set for your `htdocs` directory so the included `.htaccess` is respected.

3. **Configure the database connection** in `config/db.php` if your MySQL setup differs from the defaults:
   ```php
   $host   = 'localhost';
   $user   = 'root';
   $pass   = '';
   $dbname = 'hotel_management_db';
   ```

4. **Start Apache and MySQL**, then visit the site in your browser:
   ```
   http://localhost/Hotel-Management-System/
   ```
   On first load, `config/db.php` automatically **creates the database, all tables, and seed data** (sample hotels, rooms, flights, trips, menu items, gallery images, and an admin account) — no manual SQL import needed.

## Default Admin Login

```
URL:      /admin  (or /login, then sign in as admin)
Email:    admin@hotel.com
Password: admin123
```
**Change this password** before deploying anywhere public.

## Project Structure

```
Hotel-Management-System/
├── index.php              # Front controller: routing, autoloading, BASE_URL setup
├── .htaccess               # Rewrites all requests through index.php
├── config/
│   └── db.php               # DB connection + auto-create schema & seed data
├── Core/
│   ├── Router.php           # Simple route matcher
│   ├── Controller.php       # Base controller (view rendering, redirects)
│   └── Database.php         # mysqli singleton wrapper
├── Controllers/             # One controller per feature area
├── Models/                  # Hotel, Category, User, BaseModel
├── Views/                   # PHP templates (public pages + Views/admin, Views/auth)
├── css/ · js/ · assets/      # Static front-end assets
└── uploads/                 # User-uploaded images (hotels, rooms, employees, customers)
```

### Routing

Routes are registered in `index.php`, e.g.:
```php
$router->add('GET',  '/rooms',        'RoomController@index');
$router->add('POST', '/admin/save-hotel', 'AdminController@saveHotel');
```
The app computes a `BASE_URL` constant at runtime from the request path, so every link, form action, and redirect in the codebase is generated relative to wherever the project is actually deployed (root or subfolder) — you don't need to hardcode or configure a base path manually.

## Notes & Troubleshooting

- **Blank hero / missing homepage content:** the homepage hero text and image are read from the `site_settings` table. If you ever wipe and recreate the database, reloading the homepage will reseed default values automatically.
- **404 on every page:** make sure `mod_rewrite` is enabled and `AllowOverride All` is set, otherwise `.htaccess` won't route requests through `index.php`.
- **Database connection errors:** double check the credentials in `config/db.php` match your local MySQL setup, and that MySQL is running.
- **Uploaded images not showing:** ensure the `uploads/` subfolders (`hotels`, `rooms`, `employees`, `customers`) are writable by the web server.

## License

For personal/educational use.
