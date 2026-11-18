<?php
require_once __DIR__ . '/Product.php';

/**
 * Cart.php
 * Session-based shopping cart. Cart contents persist in $_SESSION['cart']
 * as [product_id => quantity].
 */
class Cart
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function add(int $productId, int $qty = 1): void
    {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $qty;
        } else {
            $_SESSION['cart'][$productId] = $qty;
        }
    }

    public function updateQty(int $productId, int $qty): void
    {
        if ($qty <= 0) {
            $this->remove($productId);
            return;
        }
        $_SESSION['cart'][$productId] = $qty;
    }

    public function remove(int $productId): void
    {
        unset($_SESSION['cart'][$productId]);
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }

    public function getItems(): array
    {
        return $_SESSION['cart'];
    }

    public function isEmpty(): bool
    {
        return empty($_SESSION['cart']);
    }

    public function getItemCount(): int
    {
        return array_sum($_SESSION['cart']);
    }

    /** Returns full cart details joined with product info: [[product => .., qty => .., subtotal => ..], ...] */
    public function getDetailedItems(): array
    {
        $productModel = new Product();
        $details = [];

        foreach ($_SESSION['cart'] as $productId => $qty) {
            $product = $productModel->getById((int) $productId);
            if ($product) {
                $details[] = [
                    'product'  => $product,
                    'qty'      => $qty,
                    'subtotal' => $product['price'] * $qty,
                ];
            }
        }
        return $details;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getDetailedItems() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }
}
