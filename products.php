<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Product.php';
requireAdmin('../login.php');

$productModel = new Product();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $productModel->delete((int) $_POST['product_id']);
    $_SESSION['flash_success'] = 'Product deleted.';
    header('Location: products.php');
    exit;
}

$products = $productModel->getAll();
$pageTitle = 'Products';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="fw-bold mb-0">Products</h3>
  <a href="add_product.php" class="btn btn-primehub"><i class="bi bi-plus-lg me-1"></i>Add Product</a>
</div>

<div class="card shadow-sm p-3">
  <table class="table align-middle">
    <thead class="table-light">
      <tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th style="width:160px;"></th></tr>
    </thead>
    <tbody>
      <?php foreach ($products as $p): ?>
        <tr>
          <td><img src="../assets/img/<?= e($p['image']) ?>" onerror="this.src='../assets/img/no-image.png'" style="width:45px;height:45px;object-fit:cover;border-radius:6px;"></td>
          <td><?= e($p['product_name']) ?></td>
          <td><?= e($p['category_name']) ?></td>
          <td>Rs. <?= number_format($p['price'], 2) ?></td>
          <td><span class="badge <?= $p['stock_qty'] > 5 ? 'badge-stock-ok' : 'badge-stock-low' ?>"><?= (int)$p['stock_qty'] ?></span></td>
          <td>
            <a href="edit_product.php?id=<?= (int)$p['product_id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
            <form action="products.php" method="post" class="d-inline" onsubmit="return confirm('Delete this product?');">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="product_id" value="<?= (int)$p['product_id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
