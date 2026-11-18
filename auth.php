<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Returns true if a customer or admin is logged in. */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool
{
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

/** Redirect customers to login if not authenticated. Call at top of protected pages. */
function requireLogin(string $redirectTo = 'login.php'): void
{
    if (!isLoggedIn()) {
        header("Location: {$redirectTo}");
        exit;
    }
}

/** Redirect to admin login if not authenticated as admin. Call at top of admin pages. */
function requireAdmin(string $redirectTo = 'login.php'): void
{
    if (!isAdmin()) {
        header("Location: {$redirectTo}");
        exit;
    }
}

function currentUserId(): int
{
    return (int) ($_SESSION['user_id'] ?? 0);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
