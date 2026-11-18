<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Order.php';
requireAdmin('../login.php');

$orderModel = new Order();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_status') {
    $orderModel->updateStatus((int) $_POST['order_id'], $_POST['status']);
    $_SESSION['flash_success'] = 'Order status updated.';
    header('Location: orders.php');
    exit;
}

$orders = $orderModel->getAllOrders();
$statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
$statusColors = [
    'Pending' => 'secondary', 'Processing' => 'info',
    'Shipped' => 'primary', 'Delivered' => 'success', 'Cancelled' => 'danger',
];

$pageTitle = 'Orders';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h3 class="fw-bold mb-4">Orders</h3>

<div class="card shadow-sm p-3">
  <table class="table align-middle">
    <thead class="table-light">
      <tr><th>ID</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th><th style="width:220px;">Update Status</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td>#<?= (int)$o['order_id'] ?></td>
          <td><?= e($o['full_name']) ?><br><small class="text-muted"><?= e($o['email']) ?></small></td>
          <td><?= date('d M Y, h:i A', strtotime($o['order_date'])) ?></td>
          <td>Rs. <?= number_format($o['total_amount'], 2) ?></td>
          <td><span class="badge bg-<?= $statusColors[$o['status']] ?? 'secondary' ?>"><?= e($o['status']) ?></span></td>
          <td>
            <form method="post" action="orders.php" class="d-flex gap-1">
              <input type="hidden" name="action" value="update_status">
              <input type="hidden" name="order_id" value="<?= (int)$o['order_id'] ?>">
              <select name="status" class="form-select form-select-sm">
                <?php foreach ($statuses as $s): ?>
                  <option value="<?= $s ?>" <?= $s === $o['status'] ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
              <button type="submit" class="btn btn-sm btn-primehub">Save</button>
            </form>
          </td>
          <td><a href="order_view.php?id=<?= (int)$o['order_id'] ?>" class="btn btn-sm btn-outline-secondary">View</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
