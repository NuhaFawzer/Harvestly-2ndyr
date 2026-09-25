<?php
$notifications = $notifications ?? [];
$totalNotifications = $totalNotifications ?? count($notifications);
$unreadNotifications = $unreadNotifications ?? 0;
$totalOrders = $totalOrders ?? 0;
$totalDeliveries = $totalDeliveries ?? 0;
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Harvestly - Notifications</title>

    <link
        rel="stylesheet"
        href="<?= e(BASE_URL) ?>/css/Buyer/notifications.css"
    >

    <!-- Fonts -->
    <!-- Material Icons -->
        <script src="<?= e(BASE_URL) ?>/js/icon-fallback.js" defer></script>
</head>

<body>


<!-- =====================================================
     DESKTOP NAVIGATION
===================================================== -->

<header class="desktop-header">

    <div class="header-inner">


        <!-- LOGO -->

        <a
            href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"
            class="brand"
        >

            <img src="<?= e(BASE_URL) ?>/assets/harvestly-logo.jpeg" alt="Harvestly" class="harvestly-brand-logo">

        </a>


        <!-- NAVIGATION -->

        <nav class="main-nav">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Home
            </a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php">
                Products
            </a>

        </nav>


        <!-- HEADER ACTIONS -->

        <div class="header-actions">


            <!-- SEARCH -->

            <div class="search-box">

                <span class="material-symbols-outlined">
                    search
                </span>

                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search..."
                >

            </div>


            <!-- NOTIFICATION -->

            <button
                class="header-icon notification-button active"
                id="notificationButton"
                type="button"
            >

                <span class="material-symbols-outlined filled-icon">
                    notifications
                </span>

                <?php if ($unreadNotifications > 0): ?>
                    <span class="notification-dot notification-count-badge">
                        <?php echo $unreadNotifications > 99 ? '99+' : $unreadNotifications; ?>
                    </span>
                <?php endif; ?>

            </button>


            <!-- CART -->

            <a
                href="<?= e(BASE_URL) ?>/Controller/Buyer/CartController.php"
                class="header-icon"
                id="cartButton"
                aria-label="Shopping Cart"
            >
                <svg class="header-svg-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M3 4H5L7.2 14.5C7.4 15.4 8.2 16 9.1 16H17.5C18.3 16 19 15.5 19.3 14.8L21 9H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="9.5" cy="20" r="1.5" stroke="currentColor" stroke-width="1.8"/>
                    <circle cx="17" cy="20" r="1.5" stroke="currentColor" stroke-width="1.8"/>
                </svg>
            </a>


            <!-- AUTH -->

        </div>

    </div>

</header>


<!-- =====================================================
     MOBILE HEADER
===================================================== -->

<header class="mobile-header">

    <a
        href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"
        class="mobile-logo"
    >

        <span class="material-symbols-outlined filled-icon">
            eco
        </span>

        <span>
            Harvestly
        </span>

    </a>


    <div class="mobile-actions">

        <button
            type="button"
            class="mobile-notification"
        >

            <span class="material-symbols-outlined">
                notifications
            </span>

            <?php if ($unreadNotifications > 0): ?>
                <span class="mobile-notification-dot notification-count-badge">
                    <?php echo $unreadNotifications > 99 ? '99+' : $unreadNotifications; ?>
                </span>
            <?php endif; ?>

        </button>


        <button
            type="button"
            id="mobileMenuButton"
            class="mobile-menu-button"
        >

            <span class="material-symbols-outlined">
                menu
            </span>

        </button>

    </div>

</header>


<!-- MOBILE MENU -->

<div
    class="mobile-menu"
    id="mobileMenu"
>

    <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
        Home
    </a>

    <a href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php">
        Products
    </a>

</div>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="main-container">


    <!-- =================================================
         PAGE HEADER
    ================================================== -->

    <section class="page-header">

        <div class="page-heading">

            <h1>
                Notifications
            </h1>

            <p>
                Stay updated with your orders, deliveries,
                payments, and important system announcements.
            </p>

        </div>


        <div class="page-actions">

            <button
                class="mark-read-button"
                id="markAllRead"
                type="button"
            >

                <span class="material-symbols-outlined">
                    done_all
                </span>

                Mark All as Read

            </button>


            <button
                class="settings-button"
                id="settingsButton"
                type="button"
                aria-label="Open profile settings"
            >

                <span class="material-symbols-outlined">
                    settings
                </span>

            </button>

        </div>

    </section>


    <!-- =================================================
         SUMMARY CARDS
    ================================================== -->

    <section class="summary-grid">


        <!-- TOTAL -->

        <div class="summary-card">

            <div class="summary-top">

                <div class="summary-icon">

                    <span class="material-symbols-outlined filled-icon">
                        inbox
                    </span>

                </div>

                <span class="summary-label">
                    TOTAL
                </span>

            </div>

            <div class="summary-number">
                <?php echo $totalNotifications; ?>
            </div>

        </div>


        <!-- UNREAD -->

        <div class="summary-card unread-card">

            <div class="summary-decoration"></div>

            <div class="summary-top">

                <div class="summary-icon unread-icon">

                    <span class="material-symbols-outlined filled-icon">
                        mark_email_unread
                    </span>

                </div>

                <span class="summary-label">
                    UNREAD
                </span>

            </div>

            <div class="summary-number">
                <?php echo $unreadNotifications; ?>
            </div>

        </div>


        <!-- ORDERS -->

        <div class="summary-card">

            <div class="summary-top">

                <div class="summary-icon grey-icon">

                    <span class="material-symbols-outlined">
                        shopping_bag
                    </span>

                </div>

                <span class="summary-label">
                    ORDERS
                </span>

            </div>

            <div class="summary-number">
                <?php echo $totalOrders; ?>
            </div>

        </div>


        <!-- DELIVERIES -->

        <div class="summary-card">

            <div class="summary-top">

                <div class="summary-icon grey-icon">

                    <span class="material-symbols-outlined">
                        local_shipping
                    </span>

                </div>

                <span class="summary-label">
                    DELIVERIES
                </span>

            </div>

            <div class="summary-number">
                <?php echo $totalDeliveries; ?>
            </div>

        </div>

    </section>


    <!-- =================================================
         MAIN CONTENT
    ================================================== -->

    <div class="content-layout">


        <!-- =================================================
             LEFT COLUMN
        ================================================== -->

        <section class="notifications-column">


            <!-- FILTER TABS -->

            <div class="filter-tabs">

                <button
                    class="filter-tab active"
                    data-filter="All"
                    type="button"
                >
                    All
                </button>

                <button
                    class="filter-tab"
                    data-filter="Unread"
                    type="button"
                >
                    Unread
                </button>

                <button
                    class="filter-tab"
                    data-filter="Read"
                    type="button"
                >
                    Read
                </button>

            </div>


            <!-- NOTIFICATION LIST -->

            <div
                class="notification-list"
                id="notificationList"
            >


                <?php foreach ($notifications as $index => $notification): ?>

                    <article
                        class="
                            notification-card
                            <?php echo $notification["unread"] ? "unread" : "read"; ?>
                            <?php echo !empty($notification["high"]) ? "high-priority" : ""; ?>
                            <?php echo !empty($notification["promotion"]) ? "promotion-card" : ""; ?>
                        "
                        data-type="<?php echo htmlspecialchars($notification["type"]); ?>"
                        data-id="<?php echo (int)$notification["id"]; ?>"
                    >


                        <?php if ($notification["unread"]): ?>

                            <span class="unread-dot"></span>

                        <?php endif; ?>


                        <!-- ICON -->

                        <div class="notification-icon-area">

                            <div class="notification-icon">

                                <span class="material-symbols-outlined">
                                    <?php echo htmlspecialchars($notification["icon"]); ?>
                                </span>

                            </div>

                        </div>


                        <!-- CONTENT -->

                        <div class="notification-content">

                            <div class="notification-title-row">

                                <h3>
                                    <?php echo htmlspecialchars($notification["title"]); ?>
                                </h3>


                                <?php if (!empty($notification["priority"])): ?>

                                    <span class="priority-badge">
                                        <?php echo htmlspecialchars($notification["priority"]); ?>
                                    </span>

                                <?php endif; ?>

                            </div>


                            <p class="notification-message">

                                <?php echo htmlspecialchars($notification["message"]); ?>

                            </p>


                            <div class="notification-bottom">

                                <span class="notification-time">

                                    <?php echo htmlspecialchars($notification["time"]); ?>

                                </span>


                                <?php if (!empty($notification["action"])): ?>

                                    <button
                                        type="button"
                                        class="notification-action"
                                        data-url="<?php echo htmlspecialchars((string)($notification["action_url"] ?? "")); ?>"
                                    >

                                        <?php echo htmlspecialchars($notification["action"]); ?>

                                    </button>

                                <?php endif; ?>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>


            </div>

        </section>


        <!-- =================================================
             RIGHT COLUMN
        ================================================== -->

        <aside class="sidebar">


            <!-- =================================================
                 ORDER ACTIVITY
            ================================================== -->

            <div class="sidebar-card">

                <h3 class="sidebar-title">
                    <?php if (!empty($latestOrder)): ?>
                        Order #<?= htmlspecialchars($latestOrder['id']) ?> Activity
                    <?php else: ?>
                        Order Activity
                    <?php endif; ?>
                </h3>

                <?php if (empty($latestOrder)): ?>
                    <p class="empty-activity">No orders yet. Start shopping to see order activity here.</p>
                <?php else: ?>
                    <div class="timeline">
                        <?php
                        $activity = [
                            ['title' => 'Order Placed', 'icon' => 'shopping_cart_checkout', 'done' => true],
                            ['title' => 'Accepted', 'icon' => 'handshake', 'done' => in_array($latestOrder['status'], ['Accepted','In Transit','Out for Delivery','Delivered'], true)],
                            ['title' => 'In Transit', 'icon' => 'local_shipping', 'done' => in_array($latestOrder['status'], ['In Transit','Out for Delivery','Delivered'], true)],
                            ['title' => 'Delivered', 'icon' => 'task_alt', 'done' => $latestOrder['status'] === 'Delivered'],
                        ];
                        ?>
                        <?php foreach ($activity as $item): ?>
                            <div class="timeline-item">
                                <div class="timeline-icon <?= $item['done'] ? 'completed' : 'pending' ?>">
                                    <span class="material-symbols-outlined"><?= htmlspecialchars($item['icon']) ?></span>
                                </div>
                                <div class="timeline-content">
                                    <p class="timeline-title <?= !$item['done'] ? 'pending-text' : '' ?>">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </p>
                                    <p class="timeline-time">
                                        <?= $item['done'] ? htmlspecialchars($latestOrder['date']) : 'Pending' ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a class="activity-link" href="<?= e(BASE_URL) ?>/Controller/Buyer/OrderTrackingController.php?id=<?= urlencode($latestOrder['id']) ?>">
                        Track this order
                    </a>
                <?php endif; ?>
            </div>


            <!-- =================================================
                 PREFERENCES
            ================================================== -->

            <div class="sidebar-card">

                <h3 class="sidebar-title preferences-title">

                    <span class="material-symbols-outlined">
                        tune
                    </span>

                    Preferences

                </h3>


                <div class="preferences">


                    <!-- PUSH -->

                    <label class="preference-row">

                        <span>
                            Push Notifications
                        </span>

                        <input
                            type="checkbox"
                            class="preference-checkbox"
                            checked
                        >

                        <span class="toggle"></span>

                    </label>


                    <!-- EMAIL -->

                    <label class="preference-row">

                        <span>
                            Email Updates
                        </span>

                        <input
                            type="checkbox"
                            class="preference-checkbox"
                            checked
                        >

                        <span class="toggle"></span>

                    </label>


                    <!-- PROMOTION -->

                    <label class="preference-row">

                        <span>
                            Promotional Offers
                        </span>

                        <input
                            type="checkbox"
                            class="preference-checkbox"
                        >

                        <span class="toggle"></span>

                    </label>


                </div>

            </div>


        </aside>

    </div>

</main>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="footer">

    <div class="footer-inner">


        <!-- BRAND -->

        <div class="footer-brand">

            <div class="footer-logo">

                <span class="material-symbols-outlined filled-icon">
                    eco
                </span>

                Harvestly

            </div>

            <p>
                © 2026 Harvestly.
                Bridging Sri Lankan Fields to Your Table.
            </p>

        </div>


        <!-- COLUMN 1 -->

        <div class="footer-column">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php#about">
                About Harvestly
            </a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Quick Links
            </a>

        </div>


        <!-- COLUMN 2 -->

        <div class="footer-column">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Contact Us
            </a>

        </div>


        <!-- COLUMN 3 -->

        <div class="footer-column">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Privacy Policy
            </a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Terms of Service
            </a>

        </div>


    </div>

</footer>


<script src="<?= e(BASE_URL) ?>/js/Buyer/notifications.js"></script>

</body>

</html>