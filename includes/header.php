<?php
/**
 * Shared Header Component
 */
if (!defined('HARVESTLY_INC')) {
    define('HARVESTLY_INC', true);
}
$currentPage = isset($_GET['page']) ? sanitize($_GET['page']) : 'landing';
$isAdminPage = (strpos($currentPage, 'admin_') === 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvestly - Direct Farm-to-Doorstep Marketplace</title>
    <link rel="stylesheet" href="css/style.css?v=green-theme-20260923">
</head>
<body>

<?php if (!$isAdminPage): ?>
    <!-- Public Landing Page Header -->
    <header class="public-header no-print">
        <div class="public-header-inner">
            <a href="index.php" class="logo-brand">
                <img src="assets/images/harvestly_logo.jpg" alt="Harvestly Logo" style="height: 44px; width: auto; border-radius: var(--radius-md); object-fit: contain;">
                <div class="logo-text-group">
                    <span class="logo-title">Harvestly</span>
                    <span class="logo-subtitle">Fresh Local Market</span>
                </div>
            </a>

            <nav class="nav-links">
                <a href="index.php" class="nav-pill <?= ($currentPage === 'landing') ? 'active' : ''; ?>">Home</a>
                <a href="index.php?page=products" class="nav-pill <?= ($currentPage === 'products') ? 'active' : ''; ?>">🌱 Browse Products</a>
                <a href="index.php#how-it-works" class="nav-pill">How It Works</a>
                <a href="index.php#about-us" class="nav-pill">About Us</a>
                <!-- <a href="index.php#trust-pillars" class="nav-pill">Why Harvestly</a> -->
            </nav>

            <div class="header-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="badge badge-info">Logged in as <?= sanitize($_SESSION['role']); ?></span>
                    <?php if (strtolower((string)($_SESSION['role'] ?? '')) === 'buyer'): ?>
                        <a href="Controller/Buyer/DashboardController.php" class="btn btn-primary btn-sm">Buyer Dashboard</a>
                    <?php endif; ?>
                    <form action="index.php?action=logout" method="POST" style="display:inline;">
                        <?= csrfField(); ?>
                        <button type="submit" class="btn btn-outline btn-sm">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="index.php?page=login" class="btn btn-outline">Login</a>
                    <a href="index.php?page=role_select" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
<?php else: ?>
    <!-- Admin Top Bar -->
    <div class="topbar no-print">
        <div class="topbar-title">Harvestly Administrative Operations Console</div>
        <div class="topbar-actions">
            <span class="badge badge-success">Administrator Mode</span>
            <form action="index.php?action=logout" method="POST" style="display:inline;">
                <?= csrfField(); ?>
                <button type="submit" class="btn btn-outline btn-sm">Logout</button>
            </form>
        </div>
    </div>
<?php endif; ?>
