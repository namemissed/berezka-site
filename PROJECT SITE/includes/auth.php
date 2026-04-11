<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function startSessionIfNeeded(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function isAdminLoggedIn(): bool
{
    startSessionIfNeeded();
    return isset($_SESSION['admin_id']);
}

function adminLogin(string $username, string $password): bool
{
    startSessionIfNeeded();

    $statement = db()->prepare('SELECT id, username, password_hash FROM admins WHERE username = :username LIMIT 1');
    $statement->execute(['username' => $username]);
    $admin = $statement->fetch();

    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }

    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_username'] = $admin['username'];

    return true;
}

function requireAdmin(): void
{
    if (!isAdminLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function logoutAdmin(): void
{
    startSessionIfNeeded();
    $_SESSION = [];
    session_destroy();
}

function csrfToken(): string
{
    startSessionIfNeeded();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool
{
    startSessionIfNeeded();
    return is_string($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
