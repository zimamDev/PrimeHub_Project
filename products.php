<?php
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/Product.php';

$categoryModel = new Category();
$productModel  = new Product();
$categories    = $categoryModel->getAll();

$keyword    = trim($_GET['q'] ?? '');
$categoryId = (int) ($_GET['category'] ?? 0);

if ($keyword !== '' || $categoryId > 0) {
    $products = $productModel->search($keyword, $categoryId);
} else {
    $products = $productModel->getAll();
}

$pageTitle = 'Shop';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
  <div class="row">
    <div class="col-lg-3 mb-4">
      <div class="card p-3 shadow-sm">
        <h6 class="fw-bold mb-3">Categories</h6>
        <div class="list-group">
          <a href="products.php" class="list-group-item list-group-item-action <?= $categoryId === 0 ? 'active' : '' ?>">All Products</a>
          <?php foreach ($categories as $cat): ?>
            <a href="products.php?category=<?= (int)$cat['category_id'] ?>"
               class="list-group-item list-group-item-action <?= $categoryId === (int)$cat['category_id'] ? 'active' : '' ?>">
              <?= e($cat['category_name']) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-9">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">
          <?= $keyword !== '' ? 'Search results for "' . e($keyword) . '"' : 'All Products' ?>
        </h4>
        <span class="text-muted small"><?= count($products) ?> item(s) found</span>
      </div>

      <?php if (empty($products)): ?>
        <div class="alert alert-info">No products found matching your criteria.</div>
      <?php else: ?>
        <div class="row g-4">
          <?php foreach ($products as $p): ?>
            <div class="col-6 col-md-4">
              <div class="card product-card">
                <img src="assets/img/<?= e($p['image']) ?>" alt="<?= e($p['product_name']) ?>" onerror="this.src='assets/img/no-image.png'">
                <div class="card-body d-flex flex-column">
                  <span class="badge bg-light text-dark mb-1 align-self-start"><?= e($p['category_name']) ?></span>
                  <h6 class="card-title mb-1"><?= e($p['product_name']) ?></h6>
                  <p class="fw-bold mb-1" style="color:var(--ph-primary);">Rs. <?= number_format($p['price'], 2) ?></p>
                  <span class="badge <?= $p['stock_qty'] > 5 ? 'badge-stock-ok' : 'badge-stock-low' ?> mb-2 align-self-start">
                    <?= $p['stock_qty'] > 0 ? $p['stock_qty'] . ' in stock' : 'Out of stock' ?>
                  </span>
                  <a href="product.php?id=<?= (int)$p['product_id'] ?>" class="btn btn-primehub btn-sm mt-auto">View Details</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
