<?php
/**
 * Admin Profile Dedicated View
 */
$currentPage = 'admin_profile';
?>

<div class="view-container admin-profile-page">
    <div class="page-header mb-6">
        <h1 class="text-2xl font-bold text-on-surface">Administrator Profile</h1>
        <p class="text-sm text-on-surface-variant">View and update your administrator credentials and security account settings</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mb-6"><?= sanitize($_GET['success']); ?></div>
    <?php endif; ?>

    <div class="profile-grid">
        <!-- Card 1: Profile Summary -->
        <div class="profile-summary-card">
            <div class="profile-summary-top">
                <span class="profile-kicker">HARVESTLY ADMIN</span>
                <span class="profile-status"><span></span> Active account</span>
            </div>
            <div class="profile-avatar">AD</div>
            <h2><?= sanitize($adminProfile['full_name'] ?? 'System Administrator'); ?></h2>
            <span class="badge badge-success">System Administrator</span>
            <p class="profile-email"><?= sanitize($adminProfile['email'] ?? 'admin@harvestly.lk'); ?></p>
            <div class="profile-account-date">
                <span>Account created</span>
                <strong><?= date('M d, Y', strtotime($adminProfile['created_at'] ?? 'now')); ?></strong>
            </div>
        </div>

        <!-- Card 2: Edit Profile & Security Details -->
        <div class="card profile-form-card">
            <div class="profile-form-heading">
                <div class="profile-form-icon">⚙</div>
                <div>
                    <h3>Update Account Profile</h3>
                    <p>Keep your administrator details and sign-in credentials current.</p>
                </div>
            </div>
            <form method="POST" action="index.php?admin_action=update_admin_profile">
                <div class="profile-form-fields">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="input-field" value="<?= sanitize($adminProfile['full_name'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="input-field" value="<?= sanitize($adminProfile['email'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" name="new_password" class="input-field" placeholder="••••••••">
                </div>
                </div>

                <div class="profile-form-footer">
                    <span>Changes are saved securely to your administrator account.</span>
                    <button type="submit" class="btn btn-primary">Save Profile Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
