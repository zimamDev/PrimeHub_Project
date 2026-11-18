-- =========================================================
-- PrimeHub - Online Sports Equipment Store
-- Database Schema (MySQL)
-- =========================================================

CREATE DATABASE IF NOT EXISTS primehub_db;
USE primehub_db;

-- ---------------------------------------------------------
-- Table: users  (customers + admin)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Table: categories
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Table: products
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_qty INT NOT NULL DEFAULT 0,
    image VARCHAR(255) DEFAULT 'no-image.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Table: orders
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    shipping_address VARCHAR(255) NOT NULL,
    status ENUM('Pending','Processing','Shipped','Delivered','Cancelled')
        NOT NULL DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Table: order_items
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- SEED DATA
-- =========================================================

-- Default admin account -> email: admin@primehub.com  password: admin123
-- (Hash is regenerated safely by running admin/reset_admin_password.php once
--  after import - recommended, since bcrypt hashes are version sensitive.)
INSERT INTO users (full_name, email, password, phone, address, role) VALUES
('System Admin', 'admin@primehub.com',
 '$2y$10$Rk1cV0j3Zt5G8yqYV0e0YOZQ2sHqU8V6bF1nQdKfC1sVzYt8cQZ0e',
 '0771234567', 'PrimeHub HQ, Anuradhapura', 'admin');

INSERT INTO categories (category_name, description) VALUES
('Cricket', 'Cricket bats, balls, pads and accessories'),
('Football', 'Footballs, boots, shin guards and kits'),
('Badminton', 'Rackets, shuttlecocks and nets'),
('Fitness', 'Gym and home fitness equipment'),
('Athletics', 'Track and field equipment');

INSERT INTO products (category_id, product_name, description, price, stock_qty, image) VALUES
(1, 'SG Cricket Bat', 'Premium English willow cricket bat.', 15000.00, 20, 'no-image.png'),
(1, 'Cricket Helmet', 'Lightweight protective cricket helmet.', 8500.00, 15, 'no-image.png'),
(2, 'Nike Football', 'Size 5 professional match football.', 6500.00, 30, 'no-image.png'),
(2, 'Football Boots', 'Studded football boots, all sizes.', 12000.00, 25, 'no-image.png'),
(3, 'Yonex Badminton Racket', 'Carbon fiber lightweight racket.', 9500.00, 18, 'no-image.png'),
(3, 'Shuttlecock Pack (12)', 'Feather shuttlecocks, tournament grade.', 3500.00, 40, 'no-image.png'),
(4, 'Adjustable Dumbbell Set', '2x20kg adjustable dumbbells.', 22000.00, 10, 'no-image.png'),
(4, 'Yoga Mat', 'Non-slip 6mm yoga mat.', 2500.00, 50, 'no-image.png'),
(5, 'Running Spikes', 'Lightweight track spikes.', 11000.00, 12, 'no-image.png'),
(5, 'Javelin', 'Standard competition javelin.', 18000.00, 8, 'no-image.png');
