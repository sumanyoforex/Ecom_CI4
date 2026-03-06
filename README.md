# ShopCI4 — CodeIgniter 4 Multi-Item Ecommerce

A beginner-friendly ecommerce site built with CodeIgniter 4, SQLite, JWT auth, sessions, and cookies.

---

## 🚀 Quick Start (Step-by-Step)

### Step 1 — Install Dependencies

You need **PHP 8.1+** and **Composer** installed.

> If using XAMPP, add `C:\xampp\php` to your System PATH.

```bash
cd C:\Users\YoForexTech\Documents\Ecom_CI4

# Install CodeIgniter 4 and JWT library
composer install
```

### Step 2 — Run Database Migrations

This creates the SQLite database file and all tables.

```bash
php spark migrate
```

### Step 3 — Seed Sample Data

This inserts 6 categories and 18 products with online images.

```bash
php spark db:seed DatabaseSeeder
```

### Step 4 — Start the Dev Server

```bash
php spark serve
```

Open your browser: **http://localhost:8080**

---

## 🔐 Credentials

| Role  | Email           | Password   | Where stored |
|-------|-----------------|------------|--------------|
| Admin | admin@shop.com  | Admin@1234 | `.env` file  |
| User  | (register yourself) | — | SQLite DB |

> **Admin login** is at `/admin/login`
> Admin credentials are checked against `.env` FIRST — never hits the database.

---

## 📁 Folder Structure

```
Ecom_CI4/
├── app/
│   ├── Config/
│   │   ├── App.php          ← App settings & sessions
│   │   ├── Database.php     ← SQLite configuration
│   │   ├── Filters.php      ← Register auth/admin filters
│   │   └── Routes.php       ← All URL routes
│   │
│   ├── Controllers/
│   │   ├── AuthController.php      ← Register / Login / Logout
│   │   ├── HomeController.php      ← Homepage
│   │   ├── ShopController.php      ← Product listing & detail
│   │   ├── CartController.php      ← Cart (add/update/remove)
│   │   ├── CheckoutController.php  ← Place order
│   │   ├── UserController.php      ← My account / orders
│   │   └── Admin/
│   │       ├── AuthController.php      ← Admin login (.env only)
│   │       ├── DashboardController.php ← Stats + recent orders
│   │       ├── ProductController.php   ← Product CRUD
│   │       ├── CategoryController.php  ← Category CRUD
│   │       ├── OrderController.php     ← Order management
│   │       └── UserController.php      ← Customer list
│   │
│   ├── Filters/
│   │   ├── AuthFilter.php   ← Checks JWT cookie + session for customers
│   │   └── AdminFilter.php  ← Checks is_admin session flag
│   │
│   ├── Helpers/
│   │   └── jwt_helper.php   ← jwt_generate() and jwt_verify()
│   │
│   ├── Models/
│   │   ├── UserModel.php     ← Customer accounts
│   │   ├── ProductModel.php  ← Products with search/filter
│   │   ├── CategoryModel.php ← Product categories
│   │   ├── CartModel.php     ← Shopping cart
│   │   └── OrderModel.php    ← Orders + line items
│   │
│   ├── Database/
│   │   ├── Migrations/       ← Creates tables in SQLite
│   │   └── Seeds/            ← Inserts sample data
│   │
│   └── Views/
│       ├── layouts/
│       │   ├── main.php      ← Storefront layout (Bootstrap 5)
│       │   └── admin.php     ← Admin sidebar layout
│       ├── shop/             ← home, listing, detail, cart, checkout...
│       ├── auth/             ← login, register
│       └── admin/            ← dashboard, products, categories, orders...
│
├── public/
│   └── index.php             ← Web entry point
│
├── writable/
│   └── database.sqlite       ← Created by `php spark migrate`
│
├── .env                      ← DB config, admin credentials, JWT secret
└── composer.json             ← PHP dependencies
```

---

## ✨ Key Features Explained (for Interview)

### 1. JWT Authentication
- On login, a JWT is generated with `jwt_generate()` in `jwt_helper.php`
- Stored as an **HttpOnly cookie** named `auth_token` (JS cannot read it)
- On protected pages, `AuthFilter` verifies the JWT with `jwt_verify()`

### 2. Sessions
- After JWT validation, user data is stored in `$_SESSION` for fast access
- Session files stored in `writable/session/`

### 3. Cookies
- `auth_token` — HttpOnly JWT cookie (1 day)
- `remember_token` — 30-day JWT cookie when "Remember Me" is checked

### 4. Admin .env Check
```php
// In AuthController::login()
if ($email === $_ENV['ADMIN_EMAIL'] && $password === $_ENV['ADMIN_PASSWORD']) {
    // Set admin session → redirect to /admin
    // DB is NEVER queried for admin login
}
```

### 5. SQLite Database
- Single file at `writable/database.sqlite`
- No MySQL server needed — great for development
- 6 tables: categories, users, products, orders, order_items, cart

### 6. Online Images
- All product/category images use `https://picsum.photos/seed/{name}/600/400`
- Different seed string = different image per product
- No local uploads needed
