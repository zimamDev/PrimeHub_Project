<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/Order.php';

requireLogin();

$orderModel = new Order();
$orderId = (int) ($_GET['id'] ?? 0);
$order = $orderModel->getOrderById($orderId);

// Ensure the order belongs to the logged-in customer (unless admin)
if (!$order || ((int)$order['user_id'] !== currentUserId() && !isAdmin())) {
    header('Location: orders.php');
    exit;
}

$items = $orderModel->getOrderItems($orderId);

$statusSteps = ['Pending', 'Processing', 'Shipped', 'Delivered'];
$currentStep = array_search($order['status'], $statusSteps);

$pageTitle = 'Order #' . $orderId;
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
  <h3 class="fw-bold mb-4"><i class="bi bi-receipt me-2"></i>Order #<?= (int)$order['order_id'] ?></h3>

  <?php if ($order['status'] === 'Cancelled'): ?>
    <div class="alert alert-danger">This order has been cancelled.</div>
  <?php else: ?>
    <div class="d-flex justify-content-between mb-4 position-relative">
      <?php foreach ($statusSteps as $i => $step): ?>
        <div class="text-center flex-fill">
          <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center
                      <?= $i <= $currentStep ? 'bg-success text-white' : 'bg-light text-muted border' ?>"
               style="width:36px;height:36px;">
            <?= $i + 1 ?>
          </div>
          <small class="d-block mt-1 <?= $i <= $currentStep ? 'fw-semibold' : 'text-muted' ?>"><?= $step ?></small>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card shadow-sm p-4">
        <h5 class="fw-bold mb-3">Items</h5>
        <table class="table align-middle">
          <thead class="table-light">
            <tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
          </thead>
          <tbody>
            <?php foreach ($items as $it): ?>
              <tr>
                <td class="d-flex align-items-center gap-2">
                  <img src="assets/img/<?= e($it['image']) ?>" onerror="this.src='assets/img/no-image.png'" style="width:45px;height:45px;object-fit:cover;border-radius:6px;">
                  <?= e($it['product_name']) ?>
                </td>
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
        <h5 class="fw-bold mb-3">Order Info</h5>
        <p class="mb-1"><strong>Status:</strong> <?= e($order['status']) ?></p>
        <p class="mb-1"><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></p>
        <p class="mb-1"><strong>Shipping Address:</strong><br><?= nl2br(e($order['shipping_address'])) ?></p>
        <hr>
        <p class="fw-bold fs-5">Total: Rs. <?= number_format($order['total_amount'], 2) ?></p>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
