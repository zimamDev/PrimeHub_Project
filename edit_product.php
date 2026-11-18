<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Category.php';
requireAdmin('../login.php');

$productModel  = new Product();
$categoryModel = new Category();

$id = (int) ($_GET['id'] ?? $_POST['product_id'] ?? 0);
$product = $productModel->getById($id);

if (!$product) {
    header('Location: products.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId  = (int) ($_POST['category_id'] ?? 0);
    $name        = trim($_POST['product_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = (float) ($_POST['price'] ?? 0);
    $stock       = (int) ($_POST['stock_qty'] ?? 0);
    $imageName   = null;

    if ($categoryId <= 0 || $name === '' || $price <= 0) {
        $errors[] = 'Please fill in all required fields with valid values.';
    }

    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($ext, $allowed)) {
            $imageName = 'prod_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../assets/img/' . $imageName);
        } else {
            $errors[] = 'Invalid image format. Allowed: jpg, jpeg, png, webp, gif.';
        }
    }

    if (empty($errors)) {
        $productModel->update($id, $categoryId, $name, $description, $price, $stock, $imageName);
        $_SESSION['flash_success'] = 'Product updated successfully.';
        header('Location: products.php');
        exit;
    }
}

$categories = $categoryModel->getAll();
$pageTitle = 'Edit Product';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<h3 class="fw-bold mb-4">Edit Product</h3>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card shadow-sm p-4" style="max-width:700px;">
  <form method="post" action="edit_product.php" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?= (int)$product['product_id'] ?>">
    <div class="mb-3">
      <img src="../assets/img/<?= e($product['image']) ?>" onerror="this.src='../assets/img/no-image.png'" style="width:100px;height:100px;object-fit:cover;border-radius:8px;">
    </div>
    <div class="mb-3">
      <label class="form-label">Category</label>
      <select name="category_id" class="form-select" required>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= (int)$cat['category_id'] ?>" <?= (int)$cat['category_id'] === (int)$product['category_id'] ? 'selected' : '' ?>>
            <?= e($cat['category_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Product Name</label>
      <input type="text" name="product_name" class="form-control" value="<?= e($product['product_name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"><?= e($product['description']) ?></textarea>
    </div>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Price (Rs.)</label>
        <input type="number" step="0.01" min="0" name="price" class="form-control" value="<?= e($product['price']) ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Stock Quantity</label>
        <input type="number" min="0" name="stock_qty" class="form-control" value="<?= (int)$product['stock_qty'] ?>" required>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Replace Image (optional)</label>
      <input type="file" name="image" class="form-control" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primehub fw-semibold px-4">Save Changes</button>
    <a href="products.php" class="btn btn-outline-secondary">Cancel</a>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
