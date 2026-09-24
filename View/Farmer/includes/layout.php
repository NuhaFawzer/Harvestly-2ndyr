<?php
require_once __DIR__ . '/auth.php';

if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('money')) {
    function money($value): string {
        return 'Rs. ' . number_format((float)$value, 2);
    }
}

if (!function_exists('flash')) {
    function flash(string $type, string $message): void {
        $_SESSION['_farmer_flash'] = ['type' => $type, 'message' => $message];
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void {
        $routes = [
            'dashboard.php' => 'dashboard',
            'products.php' => 'products',
            'add-product.php' => 'add_product',
            'edit-product.php' => 'edit_product',
            'inventory.php' => 'inventory',
            'orders.php' => 'orders',
            'order-details.php' => 'order_details',
            'product-details.php' => 'product_details',
            'harvest-soon.php' => 'harvest_soon',
            'sales.php' => 'sales',
            'earnings.php' => 'earnings',
            'reviews.php' => 'reviews',
            'report-issue.php' => 'report_issue',
            'notifications.php' => 'notifications',
            'profile.php' => 'profile',
        ];
        $file = basename((string)parse_url($path, PHP_URL_PATH));
        if (isset($routes[$file])) {
            $query = (string)(parse_url($path, PHP_URL_QUERY) ?? '');
            header('Location: ' . farmerRoute($routes[$file], $query));
            exit();
        }
        header('Location: ' . $path);
        exit();
    }
}

if (!function_exists('page_top')) {
    function page_top(string $title, string $active = ''): void {
        global $conn, $farmer_id, $farmer_user;
        $GLOBALS['activePage'] = $active;
        $displayName = e($GLOBALS['farmer_user']['full_name'] ?? ($_SESSION['user_name'] ?? 'Farmer'));
        $flashData = $_SESSION['_farmer_flash'] ?? null;
        unset($_SESSION['_farmer_flash']);
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?= e($title) ?> | Harvestly</title>
            <link rel="stylesheet" href="<?= e(baseUrl('css/Farmer/style.css?v=admin-green-20260924')) ?>">
        </head>
        <body>
        <div class="app">
            <?php require __DIR__ . '/../sidebar.php'; ?>
            <main class="main">
                <header class="topbar">
                    <div class="toplinks">
                        <a href="<?= farmerRoute('dashboard') ?>">Dashboard</a>
                        <a href="<?= farmerRoute('products') ?>">Products</a>
                        <a href="<?= farmerRoute('orders') ?>">Orders</a>
                    </div>
                    <div class="user">
                        <div class="avatar"><?= e(strtoupper(substr($displayName, 0, 1))) ?></div>
                        <div><strong><?= $displayName ?></strong><div style="font-size:11px;color:#69736a">Farmer</div></div>
                    </div>
                </header>
                <section class="content">
                    <?php if ($flashData): ?>
                        <div class="card" style="margin-bottom:18px;border-left:4px solid <?= ($flashData['type'] ?? '') === 'error' ? '#b84b4b' : '#315f3b' ?>;padding:14px 18px;">
                            <?= e($flashData['message'] ?? '') ?>
                        </div>
                    <?php endif; ?>
        <?php
    }
}

if (!function_exists('page_bottom')) {
    function page_bottom(): void {
        ?>
                </section>
            </main>
        </div>
        <script src="<?= e(baseUrl('js/Farmer/app.js')) ?>"></script>
        </body>
        </html>
        <?php
    }
}
