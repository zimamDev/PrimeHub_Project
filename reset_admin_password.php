<?php
/**
 * reset_admin_password.php
 *
 * Run this ONCE in your browser after importing primehub.sql to safely set
 * the admin password hash for your PHP environment, e.g.:
 *   http://localhost/primehub/admin/reset_admin_password.php
 *
 * Default admin login after running this script:
 *   Email:    admin@primehub.com
 *   Password: admin123
 *
 * DELETE THIS FILE after use for security.
 */
require_once __DIR__ . '/../config/database.php';

$conn = Database::getInstance()->getConnection();

$email = 'admin@primehub.com';
$newPassword = 'admin123';
$hash = password_hash($newPassword, PASSWORD_BCRYPT);

$stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email AND role = 'admin'");
$stmt->execute([':password' => $hash, ':email' => $email]);

if ($stmt->rowCount() > 0) {
    echo "Admin password has been reset successfully.<br>";
    echo "Email: {$email}<br>Password: {$newPassword}<br><br>";
    echo "<strong>Please delete this file (reset_admin_password.php) now for security.</strong>";
} else {
    echo "No admin account found with email {$email}. Check that primehub.sql was imported correctly.";
}
