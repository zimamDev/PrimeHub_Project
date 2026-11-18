<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/Cart.php';
require_once __DIR__ . '/classes/Product.php';

$cart = new Cart();
$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $productId = (int) ($_POST['product_id'] ?? 0);
    $qty       = max(1, (int) ($_POST['qty'] ?? 1));

    $productModel = new Product();
    $product = $productModel->getById($productId);

    if ($product && $product['stock_qty'] > 0) {
        $cart->add($productId, $qty);
        $_SESSION['flash_success'] = $product['product_name'] . ' added to cart.';
    } else {
        $_SESSION['flash_error'] = 'This product is currently out of stock.';
    }
} elseif ($action === 'update') {
    foreach ($_POST['qty'] ?? [] as $productId => $qty) {
        $cart->updateQty((int) $productId, (int) $qty);
    }
    $_SESSION['flash_success'] = 'Cart updated.';
} elseif ($action === 'remove') {
    $productId = (int) ($_POST['product_id'] ?? 0);
    $cart->remove($productId);
    $_SESSION['flash_success'] = 'Item removed from cart.';
} elseif ($action === 'clear') {
    $cart->clear();
    $_SESSION['flash_success'] = 'Cart cleared.';
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'cart.php'));
exit;
