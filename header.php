<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../classes/Cart.php';
$cart = new Cart();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' - PrimeHub' : 'PrimeHub - Online Sports Equipment Store' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark primehub-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-trophy-fill me-1"></i>PrimeHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Shop</a></li>
        <?php if (isLoggedIn() && !isAdmin()): ?>
          <li class="nav-item"><a class="nav-link" href="orders.php">My Orders</a></li>
        <?php endif; ?>
      </ul>
      <form class="d-flex me-2" role="search" action="products.php" method="get">
        <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Search products..." value="<?= e($_GET['q'] ?? '') ?>">
        <button class="btn btn-sm btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
      </form>
      <ul class="navbar-nav align-items-lg-center">
        <li class="nav-item">
          <a class="nav-link position-relative" href="cart.php">
            <i class="bi bi-cart3 fs-5"></i>
            <?php if ($cart->getItemCount() > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $cart->getItemCount() ?>
              </span>
            <?php endif; ?>
          </a>
        </li>
        <?php if (isLoggedIn()): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i><?= e($_SESSION['full_name']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
              <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
              <?php if (isAdmin()): ?>
                <li><a class="dropdown-item" href="admin/index.php">Admin Dashboard</a></li>
              <?php endif; ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="btn btn-sm btn-warning ms-lg-2 fw-semibold" href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<?php if (!empty($_SESSION['flash_success'])): ?>
  <div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= e($_SESSION['flash_success']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
  <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= e($_SESSION['flash_error']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
  <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>
