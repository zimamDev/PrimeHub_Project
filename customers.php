<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/User.php';
requireAdmin('../login.php');

$userModel = new User();
$customers = $userModel->getAllCustomers();

$pageTitle = 'Customers';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h3 class="fw-bold mb-4">Customers</h3>

<div class="card shadow-sm p-3">
  <table class="table align-middle">
    <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Joined</th></tr></thead>
    <tbody>
      <?php foreach ($customers as $c): ?>
        <tr>
          <td><?= e($c['full_name']) ?></td>
          <td><?= e($c['email']) ?></td>
          <td><?= e($c['phone']) ?></td>
          <td><?= e($c['address']) ?></td>
          <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
