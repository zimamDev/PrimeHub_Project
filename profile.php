<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/User.php';

requireLogin();

$userModel = new User();
$user = $userModel->getById(currentUserId());
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');

    if ($fullName === '' || $phone === '' || $address === '') {
        $errors[] = 'All fields are required.';
    } else {
        $userModel->updateProfile(currentUserId(), $fullName, $phone, $address);
        $_SESSION['full_name'] = $fullName;
        $success = 'Profile updated successfully.';
        $user = $userModel->getById(currentUserId());
    }
}

$pageTitle = 'My Profile';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
  <div class="card auth-card p-4">
    <h3 class="fw-bold text-center mb-4"><i class="bi bi-person-circle me-1"></i>My Profile</h3>

    <?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <form method="post" action="profile.php">
      <div class="mb-3">
        <label class="form-label">Email (cannot be changed)</label>
        <input type="email" class="form-control" value="<?= e($user['email']) ?>" disabled>
      </div>
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control" value="<?= e($user['full_name']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= e($user['phone']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="3" required><?= e($user['address']) ?></textarea>
      </div>
      <button type="submit" class="btn btn-primehub w-100 fw-semibold">Update Profile</button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
