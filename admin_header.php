<?php
require_once __DIR__ . '/auth.php';
requireAdmin('../login.php');

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' - PrimeHub Admin' : 'PrimeHub Admin' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <div class="admin-sidebar p-3" style="width:240px;">
    <h5 class="text-white fw-bold mb-4"><i class="bi bi-trophy-fill me-1"></i>PrimeHub Admin</h5>
    <a href="index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="categories.php" class="<?= $currentPage === 'categories.php' ? 'active' : '' ?>"><i class="bi bi-tags me-2"></i>Categories</a>
    <a href="products.php" class="<?= in_array($currentPage, ['products.php','add_product.php','edit_product.php']) ? 'active' : '' ?>"><i class="bi bi-box-seam me-2"></i>Products</a>
    <a href="orders.php" class="<?= in_array($currentPage, ['orders.php','order_view.php']) ? 'active' : '' ?>"><i class="bi bi-receipt me-2"></i>Orders</a>
    <a href="customers.php" class="<?= $currentPage === 'customers.php' ? 'active' : '' ?>"><i class="bi bi-people me-2"></i>Customers</a>
    <hr class="border-secondary">
    <a href="../index.php"><i class="bi bi-globe me-2"></i>View Store</a>
    <a href="../logout.php" class="text-warning"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
  </div>

  <div class="flex-grow-1 p-4">
    <?php if (!empty($_SESSION['flash_success'])): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <?= e($_SESSION['flash_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= e($_SESSION['flash_error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
