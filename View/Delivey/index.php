<?php
require_once __DIR__ . '/../../config/app.php';
if (currentCourierId() <= 0) {
    header('Location: ' . url('index.php?page=login&error=' . urlencode('Please login as a Courier Partner to continue.')));
    exit();
}
$courierName = (string)($_SESSION['user_name'] ?? 'Courier Partner');
$courierInitials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $courierName), 0, 2));
if ($courierInitials === '') { $courierInitials = 'CP'; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Harvestly — Courier Partner</title>

    <!-- Project CSS (relative from this file: ../../css/Delivery/style.css) -->
    <link rel="stylesheet" href="../../css/Delivery/style.css" />
</head>
<body>
    <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
    ?>

    <!-- ===== SIDEBAR OVERLAY (Mobile) ===== -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ============================================================ -->
    <!-- SIDEBAR (common) -->
    <!-- ============================================================ -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="logo-wrapper">
                <img src="../../logo.png" alt="Harvestly Logo" class="logo-img" id="sidebarLogo" />
                <div class="logo-fallback" id="sidebarLogoFallback">
                    <i class="fas fa-leaf"></i>
                </div>
            </div>
            <h1>Harvest<span>ly</span></h1>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Main</div>
            <a href="?page=dashboard" class="<?= ($page === 'dashboard' ? 'active' : '') ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="?page=requests" class="<?= ($page === 'requests' ? 'active' : '') ?>">
                <i class="fas fa-clipboard-list"></i> Delivery Requests
            </a>
            <a href="?page=assigned" class="<?= ($page === 'assigned' ? 'active' : '') ?>">
                <i class="fas fa-truck"></i> Assigned Deliveries
            </a>
            <a href="?page=tracking" class="<?= ($page === 'tracking' ? 'active' : '') ?>">
                <i class="fas fa-route"></i> Delivery Status
            </a>

            <div class="nav-label">Records &amp; Finance</div>
            <a href="?page=history" class="<?= ($page === 'history' ? 'active' : '') ?>">
                <i class="fas fa-history"></i> Delivery History
            </a>
            <a href="?page=earnings" class="<?= ($page === 'earnings' ? 'active' : '') ?>">
                <i class="fas fa-wallet"></i> Earnings
            </a>

            <div class="nav-label">Support</div>
            <a href="?page=complaints" class="<?= ($page === 'complaints' ? 'active' : '') ?>">
                <i class="fas fa-exclamation-triangle"></i> Complaints
            </a>
            <a href="?page=profile" class="<?= ($page === 'profile' ? 'active' : '') ?>">
                <i class="fas fa-user-circle"></i> Profile
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="avatar"><?= e($courierInitials) ?></div>
            <div class="user-info">
                <div class="name"><?= e($courierName) ?></div>
                <div class="role">Courier Partner</div>
            </div>
            <div class="logout-btn" id="logoutBtn" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </div>
        </div>
    </aside>

    <!-- ============================================================ -->
    <!-- MAIN CONTENT -->
    <!-- ============================================================ -->
    <div class="main">

        <!-- ===== TOPBAR ===== -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 id="pageTitle">
                    <?php
                        $titles = [
                            'dashboard' => 'Dashboard <span>| overview</span>',
                            'requests'  => 'Delivery Requests <span>| manage</span>',
                            'assigned'  => 'Assigned Deliveries <span>| active</span>',
                            'tracking'  => 'Delivery Status <span>| #HLY-9821</span>',
                            'history'   => 'Delivery History <span>| records</span>',
                            'earnings'  => 'Earnings <span>| analytics</span>',
                            'complaints'=> 'Complaints <span>| support</span>',
                            'profile'   => 'Profile <span>| settings</span>'
                        ];
                        echo isset($titles[$page]) ? $titles[$page] : 'Dashboard <span>| overview</span>';
                    ?>
                </h2>
            </div>
            <div class="topbar-right">
                <span class="partner-badge">
                    <i class="fas fa-check-circle"></i> Verified Partner
                </span>
                <button class="notif-btn">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </button>
            </div>
        </header>

        <!-- ===== PAGE CONTENT (dynamic include) ===== -->
        <div class="page-content">
            <?php
                $allowed = ['dashboard', 'requests', 'assigned', 'tracking', 'history', 'earnings', 'complaints', 'profile'];
                if (in_array($page, $allowed)) {
                    include $page . '.php';
                } else {
                    include 'dashboard.php';
                }
            ?>
        </div>
    </div>

    <!-- ===== LOGIN SCREEN (commented out) ===== -->
    <!-- ... (leave as is) ... -->

    <!-- ===== SCRIPTS ===== -->
    <!-- Use relative path from this file to the JS folder -->
    <script src="../../js/Delivery/script.js"></script>

    <!-- Logo fallback (kept inline) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLogo = document.getElementById('sidebarLogo');
            const sidebarFallback = document.getElementById('sidebarLogoFallback');
            const loginLogo = document.getElementById('loginLogo');
            const loginFallback = document.getElementById('loginLogoFallback');

            function checkLogo(img, fallback) {
                img.onerror = function() {
                    img.style.display = 'none';
                    fallback.style.display = 'flex';
                };
                img.onload = function() {
                    img.style.display = 'block';
                    fallback.style.display = 'none';
                };
                if (img.complete) {
                    if (img.naturalWidth === 0) {
                        img.style.display = 'none';
                        fallback.style.display = 'flex';
                    } else {
                        img.style.display = 'block';
                        fallback.style.display = 'none';
                    }
                }
            }
            if (sidebarLogo && sidebarFallback) checkLogo(sidebarLogo, sidebarFallback);
            if (loginLogo && loginFallback) checkLogo(loginLogo, loginFallback);
        });
    </script>

</body>
</html>