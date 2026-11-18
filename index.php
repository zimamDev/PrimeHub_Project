<?php
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Order.php';
require_once __DIR__ . '/../classes/User.php';

$productModel = new Product();
$orderModel   = new Order();
$userModel    = new User();

$totalProducts  = $productModel->countAll();
$totalOrders    = $orderModel->countAll();
$totalCustomers = $userModel->countCustomers();
$totalRevenue   = $orderModel->totalRevenue();
$lowStock       = $productModel->lowStock(5);
$recentOrders   = array_slice($orderModel->getAllOrders(), 0, 5);

$statusColors = [
    'Pending' => 'secondary', 'Processing' => 'info',
    'Shipped' => 'primary', 'Delivered' => 'success', 'Cancelled' => 'danger',
];

$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h3 class="fw-bold mb-4">Dashboard</h3>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card stat-card p-3">
      <div class="text-muted small">Total Products</div>
      <div class="fs-3 fw-bold"><?= $totalProducts ?></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card stat-card p-3">
      <div class="text-muted small">Total Orders</div>
      <div class="fs-3 fw-bold"><?= $totalOrders ?></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card stat-card p-3">
      <div class="text-muted small">Total Customers</div>
      <div class="fs-3 fw-bold"><?= $totalCustomers ?></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card stat-card p-3">
      <div class="text-muted small">Total Revenue</div>
      <div class="fs-3 fw-bold">Rs. <?= number_format($totalRevenue, 2) ?></div>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="card p-3 shadow-sm">
      <h6 class="fw-bold mb-3">Recent Orders</h6>
      <?php if (empty($recentOrders)): ?>
        <p class="text-muted small mb-0">No orders yet.</p>
      <?php else: ?>
        <table class="table table-sm align-middle">
          <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
          <tbody>
            <?php foreach ($recentOrders as $o): ?>
              <tr>
                <td><a href="order_view.php?id=<?= (int)$o['order_id'] ?>">#<?= (int)$o['order_id'] ?></a></td>
                <td><?= e($o['full_name']) ?></td>
                <td>Rs. <?= number_format($o['total_amount'], 2) ?></td>
                <td><span class="badge bg-<?= $statusColors[$o['status']] ?? 'secondary' ?>"><?= e($o['status']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card p-3 shadow-sm">
      <h6 class="fw-bold mb-3">Low Stock Alerts (&le;5 units)</h6>
      <?php if (empty($lowStock)): ?>
        <p class="text-muted small mb-0">All products are well stocked.</p>
      <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($lowStock as $p): ?>
            <li class="list-group-item d-flex justify-content-between">
              <?= e($p['product_name']) ?>
              <span class="badge badge-stock-low"><?= (int)$p['stock_qty'] ?> left</span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
