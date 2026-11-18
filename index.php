<?php
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/Product.php';

$categoryModel = new Category();
$productModel  = new Product();

$categories    = $categoryModel->getAll();
$allProducts   = $productModel->getAll();
$featured      = array_slice($allProducts, 0, 8);

$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero-section">
  <div class="container text-center">
    <h1 class="display-5 fw-bold mb-3">Gear Up with PrimeHub</h1>
    <p class="lead mb-4">Your one-stop online store for cricket, football, badminton, fitness &amp; athletics equipment.</p>
    <a href="products.php" class="btn btn-warning btn-lg fw-semibold px-4">Shop Now <i class="bi bi-arrow-right"></i></a>
  </div>
</section>

<div class="container my-5">
  <h3 class="fw-bold mb-4">Shop by Category</h3>
  <div class="row g-3">
    <?php foreach ($categories as $cat): ?>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="products.php?category=<?= (int)$cat['category_id'] ?>" class="text-decoration-none">
          <div class="card text-center py-4 category-pill h-100 border-0 shadow-sm">
            <i class="bi bi-dribbble fs-2 mb-2" style="color:var(--ph-primary);"></i>
            <div class="fw-semibold text-dark"><?= e($cat['category_name']) ?></div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Featured Products</h3>
    <a href="products.php" class="btn btn-sm btn-outline-secondary">View All</a>
  </div>
  <div class="row g-4">
    <?php foreach ($featured as $p): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <div class="card product-card">
          <img src="assets/img/<?= e($p['image']) ?>" alt="<?= e($p['product_name']) ?>" onerror="this.src='assets/img/no-image.png'">
          <div class="card-body d-flex flex-column">
            <span class="badge bg-light text-dark mb-1 align-self-start"><?= e($p['category_name']) ?></span>
            <h6 class="card-title mb-1"><?= e($p['product_name']) ?></h6>
            <p class="fw-bold mb-2" style="color:var(--ph-primary);">Rs. <?= number_format($p['price'], 2) ?></p>
            <a href="product.php?id=<?= (int)$p['product_id'] ?>" class="btn btn-primehub btn-sm mt-auto">View Details</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
