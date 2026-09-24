<?php
/**
 * Shared Footer Component
 */
$currentPage = isset($_GET['page']) ? sanitize($_GET['page']) : 'landing';
$isAdminPage = strpos($currentPage, 'admin_') === 0;
?>

<?php if (!$isAdminPage): ?>
    <!-- Public Footer -->
    <footer class="public-footer no-print" style="background-color: var(--color-surface-container-high); padding: 64px 24px 32px; border-top: 1px solid var(--color-outline-variant); margin-top: auto;">
        <div style="max-width: 1280px; margin: 0 auto; display: flex; flex-direction: column; gap: 48px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 32px;">
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <a href="index.php" class="logo-brand" style="text-decoration: none;">
                        <img src="assets/images/harvestly_logo.jpg" alt="Harvestly Logo" style="height: 40px; width: auto; border-radius: var(--radius-md); object-fit: contain;">
                        <span class="logo-title">Harvestly</span>
                    </a>
                    <p style="font-size: 14px; color: var(--color-on-surface-variant); line-height: 1.6;">
                        Empowering Sri Lankan agriculture through transparent, direct farm-to-doorstep trade with zero broker markups.
                    </p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <strong style="font-size: 15px; color: var(--color-on-surface);">Quick Navigation</strong>
                    <a href="index.php" style="font-size: 14px; color: var(--color-on-surface-variant); text-decoration: none;">Home</a>
                    <a href="index.php?page=products" style="font-size: 14px; color: var(--color-on-surface-variant); text-decoration: none;">Browse Products</a>
                    <a href="index.php#how-it-works" style="font-size: 14px; color: var(--color-on-surface-variant); text-decoration: none;">How It Works</a>
                    <a href="index.php#about-us" style="font-size: 14px; color: var(--color-on-surface-variant); text-decoration: none;">About Us</a>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <strong style="font-size: 15px; color: var(--color-on-surface);">Account & Access</strong>
                    <a href="index.php?page=login" style="font-size: 14px; color: var(--color-on-surface-variant); text-decoration: none;">Login</a>
                    <a href="index.php?page=role_select" style="font-size: 14px; color: var(--color-on-surface-variant); text-decoration: none;">Register / Sign Up</a>
                    <?php if (strtolower((string)($_SESSION['role'] ?? '')) === 'buyer'): ?>
                        <a href="Controller/Buyer/DashboardController.php" style="font-size: 14px; color: var(--color-on-surface-variant); text-decoration: none;">Buyer Dashboard</a>
                    <?php endif; ?>
                    <a href="index.php?page=signup_farmer" style="font-size: 14px; color: var(--color-on-surface-variant); text-decoration: none;">Farmer Registration</a>
                    <a href="index.php?page=signup_courier" style="font-size: 14px; color: var(--color-on-surface-variant); text-decoration: none;">Courier Fleet Registration</a>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <strong style="font-size: 15px; color: var(--color-on-surface);">Customer Support</strong>
                    <p style="font-size: 14px; color: var(--color-on-surface-variant); margin:0;">📞 +94 11 759 8400</p>
                    <p style="font-size: 14px; color: var(--color-on-surface-variant); margin:0;">✉️ support@harvestly.lk</p>
                    <p style="font-size: 14px; color: var(--color-on-surface-variant); margin:0;">📍 Colombo 03, Sri Lanka</p>
                </div>
            </div>

            <div style="padding-top: 24px; border-top: 1px solid var(--color-outline-variant); display: flex; flex-wrap: wrap; justify-content: space-between; gap: 16px; font-size: 13px; color: var(--color-outline);">
                <p>© 2026 Harvestly Technologies Ltd. All rights reserved. Fresh direct farm-to-doorstep marketplace.</p>
                <span>SL Registered Business</span>
            </div>
        </div>
    </footer>
<?php endif; ?>

<script src="js/main.js?v=admin-shell-20260923"></script>
</body>
</html>
