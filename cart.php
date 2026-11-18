<?php
require_once __DIR__ . '/classes/Cart.php';

$cart  = new Cart();
$items = $cart->getDetailedItems();
$total = $cart->getTotal();

$pageTitle = 'Shopping Cart';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
  <h3 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Shopping Cart</h3>

  <?php if (empty($items)): ?>
    <div class="alert alert-info">
      Your cart is empty. <a href="products.php">Continue shopping</a>.
    </div>
  <?php else: ?>
    <form method="post" action="cart_action.php">
      <input type="hidden" name="action" value="update">
      <div class="table-responsive">
        <table class="table align-middle bg-white shadow-sm">
          <thead class="table-light">
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th style="width:120px;">Quantity</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): $p = $item['product']; ?>
              <tr>
                <td class="d-flex align-items-center gap-2">
                  <img src="assets/img/<?= e($p['image']) ?>" onerror="this.src='assets/img/no-image.png'" style="width:55px;height:55px;object-fit:cover;border-radius:6px;">
                  <span><?= e($p['product_name']) ?></span>
                </td>
                <td>Rs. <?= number_format($p['price'], 2) ?></td>
                <td>
                  <input type="number" name="qty[<?= (int)$p['product_id'] ?>]" value="<?= (int)$item['qty'] ?>"
                         min="1" max="<?= (int)$p['stock_qty'] ?>" class="form-control form-control-sm">
                </td>
                <td>Rs. <?= number_format($item['subtotal'], 2) ?></td>
                <td>
                  <button type="submit" form="remove-<?= (int)$p['product_id'] ?>" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primehub">Update Cart</button>
        <h5 class="fw-bold">Total: Rs. <?= number_format($total, 2) ?></h5>
      </div>
    </form>

    <?php foreach ($items as $item): ?>
      <form id="remove-<?= (int)$item['product']['product_id'] ?>" method="post" action="cart_action.php" class="d-none">
        <input type="hidden" name="action" value="remove">
        <input type="hidden" name="product_id" value="<?= (int)$item['product']['product_id'] ?>">
      </form>
    <?php endforeach; ?>

    <div class="d-flex justify-content-end gap-2 mt-3">
      <form method="post" action="cart_action.php">
        <input type="hidden" name="action" value="clear">
        <button type="submit" class="btn btn-outline-secondary">Clear Cart</button>
      </form>
      <a href="checkout.php" class="btn btn-warning fw-semibold px-4">Proceed to Checkout <i class="bi bi-arrow-right"></i></a>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
