<?php
/**
 * Admin Sidebar Component (Strictly Matching Harvestly Admin Requirements)
 */
$currentPage = isset($_GET['page']) ? sanitize($_GET['page']) : 'admin_overview';
?>
<aside class="sidebar no-print">
    <div class="sidebar-header">
        <a href="index.php?page=admin_overview" style="display: flex; align-items: center; gap: 10px;">
            <img src="assets/images/harvestly_logo.jpg" alt="Harvestly Logo" style="height: 38px; width: auto; border-radius: var(--radius-sm); object-fit: contain;">
        </a>
        <div class="sidebar-subtitle">Operations Console</div>
    </div>

    <ul class="sidebar-nav" style="overflow-y: auto; max-height: calc(100vh - 150px); padding-bottom: 20px;">
        <!-- 1. Dashboard -->
        <li>
            <a href="index.php?page=admin_overview" class="sidebar-link <?= ($currentPage === 'admin_overview') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
        </li>

        <!-- 2. Users -->
        <li>
            <a href="index.php?page=admin_users" class="sidebar-link <?= ($currentPage === 'admin_users') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Users
            </a>
        </li>

        <!-- 3. Farmer Approvals -->
        <li>
            <a href="index.php?page=admin_farmer_approvals" class="sidebar-link <?= ($currentPage === 'admin_farmer_approvals') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                Farmer Approvals
            </a>
        </li>

        <!-- 4. Courier Approvals -->
        <li>
            <a href="index.php?page=admin_courier_approvals" class="sidebar-link <?= ($currentPage === 'admin_courier_approvals') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Courier Approvals
            </a>
        </li>

        <!-- 5. Products -->
        <li>
            <a href="index.php?page=admin_listings" class="sidebar-link <?= ($currentPage === 'admin_listings') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                Products
            </a>
        </li>

        <!-- 6. Product Categories -->
        <li>
            <a href="index.php?page=admin_categories" class="sidebar-link <?= ($currentPage === 'admin_categories') ? 'active' : ''; ?>" style="font-weight: 700; color: #cbffc2;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                Product Categories
            </a>
        </li>

        <!-- 7. Orders -->
        <li>
            <a href="index.php?page=admin_orders" class="sidebar-link <?= ($currentPage === 'admin_orders') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Orders
            </a>
        </li>

        <!-- 8. Deliveries -->
        <li>
            <a href="index.php?page=admin_deliveries" class="sidebar-link <?= ($currentPage === 'admin_deliveries') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                Deliveries
            </a>
        </li>

        <!-- 9. Pending Assignments -->
        <li>
            <a href="index.php?page=admin_pending_assignments" class="sidebar-link <?= ($currentPage === 'admin_pending_assignments') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Pending Assignments
            </a>
        </li>

        <!-- 10. District Distances -->
        <li>
            <a href="index.php?page=admin_district_distances" class="sidebar-link <?= ($currentPage === 'admin_district_distances') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
                District Distances
            </a>
        </li>

        <!-- 11. Complaints / Issues -->
        <li>
            <a href="index.php?page=admin_complaints" class="sidebar-link <?= ($currentPage === 'admin_complaints') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                Complaints / Issues
            </a>
        </li>

        <!-- 12. Payments -->
        <li>
            <a href="index.php?page=admin_payments" class="sidebar-link <?= ($currentPage === 'admin_payments') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                Payments
            </a>
        </li>

        <!-- 13. Earnings / Settlements -->
        <li>
            <a href="index.php?page=admin_settlements" class="sidebar-link <?= ($currentPage === 'admin_settlements') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Earnings / Settlements
            </a>
        </li>

        <!-- 14. Platform Settings -->
        <li>
            <a href="index.php?page=admin_settings" class="sidebar-link <?= ($currentPage === 'admin_settings') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                Platform Settings
            </a>
        </li>

        <!-- 15. Notifications -->
        <li>
            <a href="index.php?page=admin_notifications" class="sidebar-link <?= ($currentPage === 'admin_notifications') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                Notifications
            </a>
        </li>

        <!-- 16. Reports -->
        <li>
            <a href="index.php?page=admin_reports" class="sidebar-link <?= ($currentPage === 'admin_reports') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Reports
            </a>
        </li>

        <!-- 17. Profile -->
        <li>
            <a href="index.php?page=admin_profile" class="sidebar-link <?= ($currentPage === 'admin_profile') ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Admin Profile
            </a>
        </li>

        <!-- 18. Logout -->
        <li>
            <form action="index.php?action=logout" method="POST" onsubmit="return confirm('Log out of Admin Operations Console?');">
                <?= csrfField(); ?>
                <button type="submit" class="sidebar-link text-error" style="width:100%; border:0; background:transparent; text-align:left; cursor:pointer;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
                </button>
            </form>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="index.php?page=admin_profile" class="admin-profile-card" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px;">
            <div class="admin-avatar">AD</div>
            <div class="admin-info">
                <span class="admin-name"><?= isset($_SESSION['user_name']) ? sanitize($_SESSION['user_name']) : 'Admin User'; ?></span>
                <span class="admin-role">Administrator</span>
            </div>
        </a>
    </div>
</aside>
