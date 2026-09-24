<?php
require_once __DIR__ . '/includes/auth.php';
$notification_count = 0;
$stmt = $conn->prepare(
  'SELECT COUNT(*) AS total 
  FROM notifications 
  WHERE user_id=? AND is_read=0
');

$stmt->bind_param('i', $farmer_id);
$stmt->execute();
$notification_count = (int)($stmt->get_result()->fetch_assoc()['total'] ?? 0);
?>


<aside class="sidebar farmer-sidebar">
  <div class="brand harvestly-brand">
    <a href="<?= farmerRoute('dashboard') ?>" class="farmer-brand-link">
      <img src="<?= e(baseUrl('assets/images/harvestly_logo.jpg')) ?>" alt="Harvestly Logo" class="site-logo">
    </a>
    <div class="farmer-brand-subtitle">Farmer Console</div>
  </div>

  <nav class="nav" id="farmerNav">
    <a href="<?= farmerRoute('dashboard') ?>" data-page="dashboard" class="<?= (($GLOBALS['activePage'] ?? '') === 'dashboard') ? 'active' : '' ?>">Dashboard</a>
    <a href="<?= farmerRoute('products') ?>" data-page="products" class="<?= (($GLOBALS['activePage'] ?? '') === 'products') ? 'active' : '' ?>">Products</a>
    <a href="<?= farmerRoute('inventory') ?>" data-page="inventory" class="<?= (($GLOBALS['activePage'] ?? '') === 'inventory') ? 'active' : '' ?>">Inventory</a>
    <a href="<?= farmerRoute('orders') ?>" data-page="orders" class="<?= (($GLOBALS['activePage'] ?? '') === 'orders') ? 'active' : '' ?>">Orders</a>
    <a href="<?= farmerRoute('harvest_soon') ?>" data-page="harvest-soon" class="<?= (($GLOBALS['activePage'] ?? '') === 'harvest-soon') ? 'active' : '' ?>">Pre-Listings / Harvest Soon</a>
    <a href="<?= farmerRoute('sales') ?>" data-page="sales" class="<?= (($GLOBALS['activePage'] ?? '') === 'sales') ? 'active' : '' ?>">Sales / Order History</a>
    <a href="<?= farmerRoute('earnings') ?>" data-page="earnings" class="<?= (($GLOBALS['activePage'] ?? '') === 'earnings') ? 'active' : '' ?>">Earnings</a>
    <a href="<?= farmerRoute('reviews') ?>" data-page="reviews" class="<?= (($GLOBALS['activePage'] ?? '') === 'reviews') ? 'active' : '' ?>">Reviews</a>
    <a href="<?= farmerRoute('report_issue') ?>" data-page="report-issue" class="<?= (($GLOBALS['activePage'] ?? '') === 'report-issue') ? 'active' : '' ?>">Report Issue</a>
    <a href="<?= farmerRoute('notifications') ?>" data-page="notifications" class="<?= (($GLOBALS['activePage'] ?? '') === 'notifications') ? 'active' : '' ?>">Notifications<?php if($notification_count > 0): ?><span class="nav-count"><?= $notification_count ?></span><?php endif; ?></a>
    <a href="<?= farmerRoute('profile') ?>" data-page="profile" class="<?= (($GLOBALS['activePage'] ?? '') === 'profile') ? 'active' : '' ?>">Profile</a>
  </nav>
  <div class="bottom-nav nav"><a href="<?= farmerRoute('logout') ?>">Logout</a></div>
</aside>
