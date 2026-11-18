<?php
require_once __DIR__ . '/classes/Product.php';

$productModel = new Product();
$id = (int) ($_GET['id'] ?? 0);
$product = $productModel->getById($id);

if (!$product) {
    header('Location: products.php');
    exit;
}

$related = $productModel->getByCategory((int) $product['category_id']);

$pageTitle = $product['product_name'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="products.php">Shop</a></li>
      <li class="breadcrumb-item"><a href="products.php?category=<?= (int)$product['category_id'] ?>"><?= e($product['category_name']) ?></a></li>
      <li class="breadcrumb-item active"><?= e($product['product_name']) ?></li>
    </ol>
  </nav>

  <div class="row g-4">
    <div class="col-md-5">
      <img src="assets/img/<?= e($product['image']) ?>" onerror="this.src='assets/img/no-image.png'"
           class="img-fluid rounded shadow-sm w-100" style="max-height:420px;object-fit:cover;" alt="<?= e($product['product_name']) ?>">
    </div>
    <div class="col-md-7">
      <span class="badge bg-light text-dark mb-2"><?= e($product['category_name']) ?></span>
      <h2 class="fw-bold"><?= e($product['product_name']) ?></h2>
      <h4 class="mb-3" style="color:var(--ph-primary);">Rs. <?= number_format($product['price'], 2) ?></h4>
      <p class="text-muted"><?= nl2br(e($product['description'])) ?></p>

      <p>
        <span class="badge <?= $product['stock_qty'] > 5 ? 'badge-stock-ok' : 'badge-stock-low' ?>">
          <?= $product['stock_qty'] > 0 ? $product['stock_qty'] . ' in stock' : 'Out of stock' ?>
        </span>
      </p>

      <?php if ($product['stock_qty'] > 0): ?>
        <form action="cart_action.php" method="post" class="d-flex align-items-center gap-3 mt-3">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="product_id" value="<?= (int)$product['product_id'] ?>">
          <input type="number" name="qty" value="1" min="1" max="<?= (int)$product['stock_qty'] ?>" class="form-control" style="width:100px;">
          <button type="submit" class="btn btn-warning fw-semibold px-4"><i class="bi bi-cart-plus me-1"></i>Add to Cart</button>
        </form>
      <?php else: ?>
        <button class="btn btn-secondary mt-3" disabled>Out of Stock</button>
      <?php endif; ?>
    </div>
  </div>

  <?php if (count($related) > 1): ?>
    <div class="mt-5">
      <h5 class="fw-bold mb-3">More in <?= e($product['category_name']) ?></h5>
      <div class="row g-4">
        <?php foreach ($related as $r):
          if ((int)$r['product_id'] === (int)$product['product_id']) continue; ?>
          <div class="col-6 col-md-3">
            <div class="card product-card">
              <img src="assets/img/<?= e($r['image']) ?>" onerror="this.src='assets/img/no-image.png'" alt="<?= e($r['product_name']) ?>">
              <div class="card-body d-flex flex-column">
                <h6 class="card-title mb-1"><?= e($r['product_name']) ?></h6>
                <p class="fw-bold mb-2" style="color:var(--ph-primary);">Rs. <?= number_format($r['price'], 2) ?></p>
                <a href="product.php?id=<?= (int)$r['product_id'] ?>" class="btn btn-primehub btn-sm mt-auto">View</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
