<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Category.php';
requireAdmin('../login.php');

$categoryModel = new Category();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['category_name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        if ($name !== '') {
            $categoryModel->create($name, $desc);
            $_SESSION['flash_success'] = 'Category added successfully.';
        }
    } elseif ($action === 'update') {
        $id   = (int) ($_POST['category_id'] ?? 0);
        $name = trim($_POST['category_name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        if ($id > 0 && $name !== '') {
            $categoryModel->update($id, $name, $desc);
            $_SESSION['flash_success'] = 'Category updated successfully.';
        }
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['category_id'] ?? 0);
        $categoryModel->delete($id);
        $_SESSION['flash_success'] = 'Category deleted.';
    }
    header('Location: categories.php');
    exit;
}

$categories = $categoryModel->getAll();
$pageTitle = 'Categories';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="fw-bold mb-0">Categories</h3>
  <button class="btn btn-primehub" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
    <i class="bi bi-plus-lg me-1"></i>Add Category
  </button>
</div>

<div class="card shadow-sm p-3">
  <table class="table align-middle">
    <thead class="table-light"><tr><th>Name</th><th>Description</th><th style="width:160px;"></th></tr></thead>
    <tbody>
      <?php foreach ($categories as $cat): ?>
        <tr>
          <td><?= e($cat['category_name']) ?></td>
          <td><?= e($cat['description']) ?></td>
          <td>
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= (int)$cat['category_id'] ?>">Edit</button>
            <form action="categories.php" method="post" class="d-inline" onsubmit="return confirm('Delete this category? Products in it will also be deleted.');">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="category_id" value="<?= (int)$cat['category_id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </td>
        </tr>

        <div class="modal fade" id="editModal<?= (int)$cat['category_id'] ?>" tabindex="-1">
          <div class="modal-dialog">
            <form method="post" action="categories.php" class="modal-content">
              <div class="modal-header"><h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
              <div class="modal-body">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="category_id" value="<?= (int)$cat['category_id'] ?>">
                <div class="mb-3">
                  <label class="form-label">Name</label>
                  <input type="text" name="category_name" class="form-control" value="<?= e($cat['category_name']) ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Description</label>
                  <textarea name="description" class="form-control"><?= e($cat['description']) ?></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primehub">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" action="categories.php" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Add Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="action" value="create">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="category_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primehub">Add Category</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
