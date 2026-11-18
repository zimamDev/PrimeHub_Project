<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/Cart.php';
require_once __DIR__ . '/classes/Order.php';
require_once __DIR__ . '/classes/User.php';

requireLogin();

$cart  = new Cart();
$items = $cart->getDetailedItems();

if (empty($items)) {
    header('Location: cart.php');
    exit;
}

$userModel = new User();
$user = $userModel->getById(currentUserId());

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shippingAddress = trim($_POST['shipping_address'] ?? '');

    if ($shippingAddress === '') {
        $errors[] = 'Please provide a shipping address.';
    } else {
        $orderModel = new Order();
        $orderId = $orderModel->placeOrder(currentUserId(), $items, $shippingAddress);

        if ($orderId) {
            $cart->clear();
            $_SESSION['flash_success'] = 'Order placed successfully! Your order ID is #' . $orderId . '.';
            header('Location: order_detail.php?id=' . $orderId);
            exit;
        } else {
            $errors[] = 'Sorry, one or more items in your cart are no longer available in the requested quantity.';
        }
    }
}

$total = $cart->getTotal();
$pageTitle = 'Checkout';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
  <h3 class="fw-bold mb-4"><i class="bi bi-bag-check me-2"></i>Checkout</h3>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0"><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-lg-7">
      <div class="card shadow-sm p-4">
        <h5 class="fw-bold mb-3">Shipping Details</h5>
        <form method="post" action="checkout.php">
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" value="<?= e($user['full_name']) ?>" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" value="<?= e($user['phone']) ?>" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Shipping Address</label>
            <textarea name="shipping_address" class="form-control" rows="3" required><?= e($user['address']) ?></textarea>
          </div>
          <div class="alert alert-secondary small">
            <i class="bi bi-info-circle me-1"></i>Payment is collected via Cash on Delivery / bank transfer confirmation with our sales team (online payment gateway is out of scope for this project).
          </div>
          <button type="submit" class="btn btn-warning w-100 fw-semibold">Place Order</button>
        </form>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card shadow-sm p-4">
        <h5 class="fw-bold mb-3">Order Summary</h5>
        <ul class="list-group list-group-flush mb-3">
          <?php foreach ($items as $item): ?>
            <li class="list-group-item d-flex justify-content-between">
              <span><?= e($item['product']['product_name']) ?> &times; <?= (int)$item['qty'] ?></span>
              <span>Rs. <?= number_format($item['subtotal'], 2) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="d-flex justify-content-between fw-bold fs-5">
          <span>Total</span>
          <span>Rs. <?= number_format($total, 2) ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
