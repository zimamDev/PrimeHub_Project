<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Order.php';
requireAdmin('../login.php');

$orderModel = new Order();
$id = (int) ($_GET['id'] ?? 0);
$order = $orderModel->getOrderById($id);

if (!$order) {
    header('Location: orders.php');
    exit;
}

$items = $orderModel->getOrderItems($id);
$pageTitle = 'Order #' . $id;
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h3 class="fw-bold mb-4">Order #<?= (int)$order['order_id'] ?></h3>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="card shadow-sm p-4">
      <h6 class="fw-bold mb-3">Items</h6>
      <table class="table align-middle">
        <thead class="table-light"><tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr></thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?= e($it['product_name']) ?></td>
              <td><?= (int)$it['quantity'] ?></td>
              <td>Rs. <?= number_format($it['unit_price'], 2) ?></td>
              <td>Rs. <?= number_format($it['unit_price'] * $it['quantity'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card shadow-sm p-4">
      <h6 class="fw-bold mb-3">Order Info</h6>
      <p class="mb-1"><strong>Status:</strong> <?= e($order['status']) ?></p>
      <p class="mb-1"><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></p>
      <p class="mb-1"><strong>Shipping Address:</strong><br><?= nl2br(e($order['shipping_address'])) ?></p>
      <hr>
      <p class="fw-bold fs-5">Total: Rs. <?= number_format($order['total_amount'], 2) ?></p>
    </div>
  </div>
</div>

<a href="orders.php" class="btn btn-outline-secondary mt-3">Back to Orders</a>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
