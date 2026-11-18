# PrimeHub – Online Sports Equipment Store

A full-stack final-year ICT project (HNDIT4052) built with **PHP (OOP)**, **MySQL**, and **Bootstrap 5**.
No frameworks (no Laravel/CodeIgniter) — plain PHP using object-oriented classes and PDO.

## Tech Stack
- Frontend: HTML, CSS, Bootstrap 5, Bootstrap Icons, JavaScript
- Backend: PHP 8 (OOP, PDO)
- Database: MySQL (MySQL Server / XAMPP / WAMP)

## Folder Structure
```
primehub/
├── config/
│   └── database.php        # PDO Singleton connection class
├── classes/
│   ├── User.php             # Registration, login, profile
│   ├── Category.php         # Category CRUD
│   ├── Product.php          # Product catalog, search, stock, CRUD
│   ├── Cart.php             # Session-based shopping cart
│   └── Order.php            # Checkout, order tracking, admin order mgmt
├── includes/
│   ├── auth.php              # Session guards (requireLogin/requireAdmin)
│   ├── header.php / footer.php        # Customer-facing layout
│   └── admin_header.php / admin_footer.php  # Admin layout
├── admin/                    # Admin dashboard, products, categories, orders, customers
├── assets/css/style.css
├── assets/img/                # Product images (uploaded here)
├── database/primehub.sql      # Full schema + seed data
├── index.php, products.php, product.php
├── register.php, login.php, logout.php, profile.php
├── cart.php, cart_action.php, checkout.php
└── orders.php, order_detail.php
```

## Setup Instructions (XAMPP / WAMP)

1. Copy the `primehub` folder into your server's web root:
   - XAMPP: `C:\xampp\htdocs\primehub`
   - WAMP:  `C:\wamp64\www\primehub`

2. Start **Apache** and **MySQL** from your XAMPP/WAMP control panel.

3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`), create the database by
   importing `database/primehub.sql` (this also creates `primehub_db` and seeds
   sample categories/products).

4. Check `config/database.php` — default settings work for a standard
   XAMPP install (`host=localhost`, `user=root`, `password=''`). Update if
   your MySQL credentials differ.

5. **Important — set the admin password once:**
   Visit `http://localhost/primehub/admin/reset_admin_password.php` in your
   browser. This safely creates the correct bcrypt hash for the admin account
   on your PHP version. Then **delete that file**.

   Default admin login after this step:
   - Email: `admin@primehub.com`
   - Password: `admin123`

6. Visit `http://localhost/primehub/` to view the storefront, or
   `http://localhost/primehub/admin/` for the admin dashboard (log in with
   the admin credentials above via the normal `login.php` page).

## Features Implemented (per SRS)

**Customer side**
- Registration & login (bcrypt password hashing)
- Product catalog browsing by category
- Product search (keyword + category filter)
- Product detail view with stock display
- Shopping cart (add/update qty/remove/clear) — session based
- Checkout with shipping address, automatic stock deduction
- Order placement & order tracking with visual status stepper
- Profile management

**Admin side**
- Dashboard with key stats (products, orders, customers, revenue) and low-stock alerts
- Category management (CRUD)
- Product management (CRUD with image upload)
- Order management (view details, update status: Pending → Processing → Shipped → Delivered / Cancelled)
- Customer records view

## Notes
- Online payment gateway integration is explicitly **out of scope** (per SRS section 1.8.2) — checkout records
  the order for Cash on Delivery / manual payment confirmation.
- All database access goes through prepared statements (PDO) to prevent SQL injection.
- All output is escaped with `htmlspecialchars()` (the `e()` helper) to prevent XSS.
