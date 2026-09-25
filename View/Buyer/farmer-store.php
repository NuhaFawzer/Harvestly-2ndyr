<?php
$store = $store ?? [];
$products = $store['products'] ?? [];
$name = $store['name'] ?? 'Farmer';
$baseUrl = $baseUrl ?? url('');
$rating = (float)($store['rating'] ?? 0);
$initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 2));
if ($initials === '') { $initials = 'FA'; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($name); ?> - Farmer Store | Harvestly</title>
    <link rel="stylesheet" href="<?php echo e($baseUrl); ?>/css/Buyer/farmer-store.css">
            <link rel="stylesheet" href="<?= e($baseUrl) ?>/css/Buyer/buyer-header-polish.css">

<script src="<?php echo e($baseUrl); ?>/js/icon-fallback.js" defer></script>
</head>
<body>
<nav class="navbar">
    <div class="navbar-inner">
        <a href="<?php echo e(buyerRoute('DashboardController.php')); ?>" class="logo">
            <img src="<?php echo e($baseUrl); ?>/assets/harvestly-logo-horizontal.png" alt="Harvestly">
        </a>
        <div class="nav-links">
            <a href="<?php echo e(buyerRoute('DashboardController.php')); ?>">Home</a>
            <a class="active" href="<?php echo e(buyerRoute('ProductController.php')); ?>">Products</a>
        </div>
        <div class="nav-actions">
            <?php include __DIR__ . '/header-icons.php'; ?>
            <a href="<?php echo e(buyerRoute('ProfileController.php')); ?>" class="profile-link"><span class="material-symbols-outlined">person</span></a>
        </div>
    </div>
</nav>

<main class="store-page">
    <div class="breadcrumb">
        <a href="<?php echo e(buyerRoute('ProductController.php')); ?>">Browse Products</a>
        <span class="material-symbols-outlined">chevron_right</span>
        <span><?php echo e($name); ?></span>
    </div>

    <section class="store-hero">
        <div class="farmer-avatar"><?php echo e($initials); ?></div>
        <div class="farmer-main">
            <div class="eyebrow"><span class="material-symbols-outlined">verified</span> Harvestly Farmer</div>
            <h1><?php echo e($name); ?></h1>
            <div class="farmer-meta">
                <span><span class="material-symbols-outlined">star</span> <?php echo number_format($rating, 1); ?> Farmer rating</span>
                <span><span class="material-symbols-outlined">location_on</span> <?php echo e($store['district']); ?></span>
                <span><span class="material-symbols-outlined">agriculture</span> <?php echo e($store['experience']); ?></span>
            </div>
        </div>
        <a class="back-button" href="<?php echo e(buyerRoute('ProductController.php')); ?>"><span class="material-symbols-outlined">arrow_back</span> Browse Products</a>
    </section>

    <section class="store-info">
        <div class="info-card"><span class="material-symbols-outlined">location_on</span><div><small>Farm / Location</small><strong><?php echo e($store['farm'] ?: $store['district']); ?></strong></div></div>
        <?php if (!empty($store['address'])): ?><div class="info-card"><span class="material-symbols-outlined">home</span><div><small>Pickup Address</small><strong><?php echo e($store['address']); ?></strong></div></div><?php endif; ?>
        <div class="info-card"><span class="material-symbols-outlined">local_shipping</span><div><small>Delivery</small><strong><?php echo e($store['delivery']); ?></strong></div></div>
        <div class="info-card"><span class="material-symbols-outlined">inventory_2</span><div><small>Available Products</small><strong><?php echo count($products); ?> listings</strong></div></div>
    </section>

    <section class="products-section">
        <div class="section-heading">
            <div><p class="section-kicker">FRESH FROM THE FARM</p><h2>Products by <?php echo e($name); ?></h2></div>
            <span class="product-count"><?php echo count($products); ?> products</span>
        </div>

        <?php if (!$products): ?>
            <div class="empty-state"><span class="material-symbols-outlined">inventory_2</span><h3>No products available</h3><p>This farmer has no active listings at the moment.</p></div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <article class="product-card">
                        <div class="product-image-wrap">
                            <img src="<?php echo e($product['image'] ?: url('assets/coconut-sri-lanka.jpeg')); ?>" alt="<?php echo e($product['name']); ?>">
                            <?php if (!empty($product['organic'])): ?><span class="badge organic">Organic</span><?php endif; ?>
                            <?php if (!empty($product['fresh'])): ?><span class="badge fresh">Fresh</span><?php endif; ?>
                        </div>
                        <div class="product-body">
                            <div class="product-title-row"><h3><?php echo e($product['name']); ?></h3><span class="rating"><span class="material-symbols-outlined">star</span><?php echo number_format((float)$product['rating'], 1); ?></span></div>
                            <p class="product-meta"><?php echo e($product['unit']); ?> · <?php echo (int)$product['stock']; ?> in stock</p>
                            <div class="price-row"><strong>Rs. <?php echo number_format((float)$product['price'], 2); ?></strong><a href="<?php echo e(buyerRoute('ProductDetailsController.php', 'id=' . (int)$product['id'])); ?>">View Product <span class="material-symbols-outlined">arrow_forward</span></a></div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
