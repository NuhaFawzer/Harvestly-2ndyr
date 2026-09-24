<?php
$error = isset($_GET['error']) ? sanitize($_GET['error']) : null;
$success = isset($_GET['success']) ? sanitize($_GET['success']) : null;
?>
<div class="page-content" style="max-width: 480px; margin: 100px auto; padding: 0 20px;">
    <div style="background-color: var(--color-surface); border: 1px solid var(--color-outline-variant); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-md);">
        <div style="text-align: center; margin-bottom: 28px;">
            <img src="assets/images/harvestly_logo.jpg" alt="Harvestly Logo" style="height: 60px; width: auto; margin: 0 auto 12px; display: block; border-radius: var(--radius-md); object-fit: contain;">
            <h1 style="font-size: 24px; font-weight: 800; color: var(--color-on-surface);">Welcome to Harvestly</h1>
            <p style="font-size: 14px; color: var(--color-on-surface-variant); margin-top: 4px;">Sign in to access your platform account</p>
        </div>

        <?php if ($error): ?>
            <div style="background-color: var(--color-error-container); color: var(--color-error); padding: 12px 16px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                ⚠️ <?= $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                ✓ <?= $success; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=login" method="POST">
            <?= csrfField(); ?>
            <div class="form-group">
                <label class="form-label" for="login-email">Email Address</label>
                <input type="email" id="login-email" name="email" class="form-control" placeholder="name@domain.com" required>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label class="form-label" for="login-password">Password</label>
                    <a href="index.php?page=forgot_password" style="font-size: 12px; color: var(--color-primary); font-weight: 600; text-decoration: none;">Forgot Password?</a>
                </div>
                <input type="password" id="login-password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 12px; padding: 12px;">
                Sign In
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--color-on-surface-variant);">
            Don't have an account? <a href="index.php?page=role_select" style="color: var(--color-primary); font-weight: 700;">Sign up here</a>
        </div>
        
        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--color-outline-variant); font-size: 12px; color: var(--color-outline); text-align: center;">
            <strong>Demo Credentials:</strong><br>
            Admin: <code>admin@harvestly.lk</code> | Pass: <code>admin123</code>
        </div>
    </div>
</div>
