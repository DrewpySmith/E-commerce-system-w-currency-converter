# E-commerce System (CodeIgniter 4)

This project is a simple E-commerce app with:
- Product catalog with currency conversion (via ExchangeRate API)
- User authentication (register, login, logout)
- Cart and checkout (creates orders & order items)
- Admin panel (Employees CRUD, Products CRUD, Sales Dashboard with charts)
- Print-ready admin pages (buttons + stylesheet)

## Prerequisites
- PHP 8.1+
- MySQL/MariaDB
- Composer
- Optional: Node not required

## 1) Install dependencies
```
composer install
```

## 2) Configure environment
Copy `.env` example if needed and configure DB credentials:
```
cp .env.example .env   # If applicable
```
Edit `.env`:
```
database.default.hostname = localhost
database.default.database = your_db_name
database.default.username = your_user
database.default.password = your_pass
database.default.DBDriver = MySQLi
```

## 3) Create database tables
Run migrations (employees, orders, order_items, etc.):
```
php spark migrate
```

(Optional) Seed an admin user:
```
php spark db:seed AdminUserSeeder
```
Credentials (change in seeder if needed):
- email: admin@example.com
- password: admin123
- role: admin

## 4) Serve the app
Option A: CI dev server
```
php spark serve
```
Open: http://localhost:8080

Option B: XAMPP/Apache
Point DocumentRoot to the `public/` directory or access with `/public`:
- http://localhost/E-commerce/public

## 5) Key URLs
- Storefront: `/` (catalog)
- Cart: `/cart`
- Checkout: `/checkout`
- Login: `/login` / Register: `/register`
- Admin Dashboard: `/admin`
- Admin Employees: `/admin/employees`
- Admin Products: `/admin/products`

## Notes
- Admin pages include Print buttons and a print stylesheet at `public/css/print.css`.
- Currency conversion uses `https://api.exchangerate-api.com/v4/latest/USD`.
- Orders are created via checkout (status `paid`), and the dashboard aggregates real data by day.

## Troubleshooting
- If `/admin` 404s, ensure routes include either a group empty-path or an explicit `/admin` mapping.
- If using Apache without vhost, include `/public` in URLs or change DocumentRoot.
- Run `php spark routes` to inspect registered routes.
