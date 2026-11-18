<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/Order.php';

requireLogin();

$orderModel = new Order();
$orders = $orderModel->getOrdersByUser(currentUserId());

$statusColors = [
    'Pending'    => 'secondary',
    'Processing' => 'info',
    'Shipped'    => 'primary',
    'Delivered'  => 'success',
    'Cancelled'  => 'danger',
];

$pageTitle = 'My Orders';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
  <h3 class="fw-bold mb-4"><i class="bi bi-receipt me-2"></i>My Orders</h3>

  <?php if (empty($orders)): ?>
    <div class="alert alert-info">You haven't placed any orders yet. <a href="products.php">Start shopping</a>.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table bg-white shadow-sm align-middle">
        <thead class="table-light">
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td>#<?= (int)$o['order_id'] ?></td>
              <td><?= date('d M Y, h:i A', strtotime($o['order_date'])) ?></td>
              <td>Rs. <?= number_format($o['total_amount'], 2) ?></td>
              <td><span class="badge bg-<?= $statusColors[$o['status']] ?? 'secondary' ?>"><?= e($o['status']) ?></span></td>
              <td><a href="order_detail.php?id=<?= (int)$o['order_id'] ?>" class="btn btn-sm btn-outline-secondary">View</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
