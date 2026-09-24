<?php
/**
 * Forgot Password View
 */
$error = isset($_GET['error']) ? sanitize($_GET['error']) : null;
$success = isset($_GET['success']) ? sanitize($_GET['success']) : null;
?>
<div class="page-content" style="max-width: 480px; margin: 100px auto; padding: 0 20px;">
    <div style="background-color: var(--color-surface); border: 1px solid var(--color-outline-variant); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-md);">
        <div style="text-align: center; margin-bottom: 28px;">
            <img src="assets/images/harvestly_logo.jpg" alt="Harvestly Logo" style="height: 60px; width: auto; margin: 0 auto 12px; display: block; border-radius: var(--radius-md); object-fit: contain;">
            <h1 style="font-size: 24px; font-weight: 800; color: var(--color-on-surface);">Forgot Password?</h1>
            <p style="font-size: 14px; color: var(--color-on-surface-variant); margin-top: 4px;">Enter your registered account email address to receive password reset instructions</p>
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

        <form action="index.php?action=request_password_reset" method="POST">
            <?= csrfField(); ?>
            <div class="form-group mb-4">
                <label class="form-label" for="reset-email">Email Address</label>
                <input type="email" id="reset-email" name="email" class="form-control" placeholder="name@domain.com" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 12px; padding: 12px;">
                Send Reset Password Link
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--color-on-surface-variant);">
            Remember your password? <a href="index.php?page=login" style="color: var(--color-primary); font-weight: 700;">Back to Login</a>
        </div>
    </div>
</div>
