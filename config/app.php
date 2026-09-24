<?php
/**
 * Harvestly shared application helpers for the integrated project.
 * Core PHP + MySQLi only.
 */

declare(strict_types=1);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!defined('APP_NAME')) {
    define('APP_NAME', 'Harvestly');
}
if (!defined('BASE_URL')) {
    $scriptName = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
    $basePath = preg_replace(
        '#/(?:Controller|View|auth|admin|farmer|courier|buyer|config|includes)(?:/.*)?$#i',
        '',
        $scriptName
    );
    $basePath = preg_replace('#/index\.php$#i', '', (string)$basePath);
    define('BASE_URL', rtrim($basePath ?: '/Harvestly', '/'));
}

function db(): mysqli
{
    return getDBConnection();
}

function url(string $path = ''): string
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function redirect(string $path): void
{
    $target = preg_match('#^https?://#i', $path) ? $path : url($path);
    header('Location: ' . $target);
    exit();
}

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function post_string(string $key, string $default = ''): string
{
    return trim((string)($_POST[$key] ?? $default));
}

function buyerRoute(string $controller, string $query = ''): string
{
    $path = 'Controller/Buyer/' . ltrim($controller, '/');
    return url($path . ($query !== '' ? ('?' . ltrim($query, '?')) : ''));
}

function currentBuyerId(): int
{
    if (isset($_SESSION['user_id'], $_SESSION['role']) && strtolower((string)$_SESSION['role']) === 'buyer') {
        return (int)$_SESSION['user_id'];
    }
    return 0;
}

function requireBuyerAuth(): void
{
    if (currentBuyerId() <= 0) {
        header('Location: ' . url('index.php?page=login&error=' . urlencode('Please login as a Buyer to continue.')));
        exit();
    }
}

function currentFarmerId(): int
{
    if (isset($_SESSION['user_id'], $_SESSION['role']) && strtolower((string)$_SESSION['role']) === 'farmer') {
        return (int)$_SESSION['user_id'];
    }
    return 0;
}

function currentCourierId(): int
{
    $role = strtolower((string)($_SESSION['role'] ?? ''));
    if (isset($_SESSION['user_id']) && in_array($role, ['courier', 'courier_partner'], true)) {
        return (int)$_SESSION['user_id'];
    }
    return 0;
}

function db_fetch_one(string $sql, string $types = '', array $params = []): ?array
{
    $conn = db();
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return null;
    }
    if ($types !== '' && $params) {
        $bind = [$types];
        foreach ($params as $key => $value) {
            $bind[] = &$params[$key];
        }
        $stmt->bind_param(...$bind);
    }
    if (!$stmt->execute()) {
        $stmt->close();
        return null;
    }
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $stmt->close();
    return $row ?: null;
}

function db_fetch_all(string $sql, string $types = '', array $params = []): array
{
    $conn = db();
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    if ($types !== '' && $params) {
        $bind = [$types];
        foreach ($params as $key => $value) {
            $bind[] = &$params[$key];
        }
        $stmt->bind_param(...$bind);
    }
    if (!$stmt->execute()) {
        $stmt->close();
        return [];
    }
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    return $rows;
}
