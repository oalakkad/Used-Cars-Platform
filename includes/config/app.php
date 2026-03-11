<?php
/**
 * Application Configuration
 */

define('APP_NAME', 'AutoMarket');
define('APP_URL', 'http://localhost/Used-Cars-Platform/public');
define('UPLOAD_DIR', __DIR__ . '/../../public/assets/uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper functions
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function currentUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

function currentUserName(): ?string
{
    return $_SESSION['user_name'] ?? null;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function sanitize(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string
{
    return APP_URL . '/assets/' . ltrim($path, '/');
}

function baseUrl(string $path = ''): string
{
    return APP_URL . '/' . ltrim($path, '/');
}
