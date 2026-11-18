<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/classes/User.php';

if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? 'admin/index.php' : 'index.php'));
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Please enter both email and password.';
    } else {
        $userModel = new User();
        $user = $userModel->login($email, $password);

        if ($user) {
            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role']      = $user['role'];

            header('Location: ' . ($user['role'] === 'admin' ? 'admin/index.php' : 'index.php'));
            exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}

$pageTitle = 'Login';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="card auth-card p-4">
    <h3 class="fw-bold text-center mb-4"><i class="bi bi-box-arrow-in-right me-1"></i>Login</h3>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="login.php">
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primehub w-100 fw-semibold">Login</button>
    </form>
    <p class="text-center mt-3 mb-0 small">Don't have an account? <a href="register.php">Register here</a></p>
    <p class="text-center mt-2 mb-0 small text-muted">Admin? Log in with your admin email &amp; password here too.</p>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
