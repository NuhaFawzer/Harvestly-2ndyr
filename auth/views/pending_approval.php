<?php
$message = isset($_GET['message']) ? sanitize($_GET['message']) : 'Your registration application has been received and is currently under review.';
?>
<div class="page-content" style="max-width: 600px; margin: 80px auto; padding: 0 20px;">
    <div style="background-color: var(--color-surface); border: 1px solid var(--color-outline-variant); border-radius: var(--radius-xl); padding: 48px 36px; text-align: center; box-shadow: var(--shadow-md);">
        <div style="width: 72px; height: 72px; border-radius: 50%; background-color: #fff3cd; color: #664d03; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>

        <h1 style="font-size: 26px; font-weight: 800; color: var(--color-on-surface); margin-bottom: 12px;">Application Under Review</h1>
        
        <p style="font-size: 15px; color: var(--color-on-surface-variant); line-height: 1.6; margin-bottom: 24px;">
            <?= $message; ?>
        </p>

        <div style="background-color: var(--color-surface-container-low); padding: 16px; border-radius: var(--radius-md); text-align: left; font-size: 13px; color: var(--color-on-surface-variant); margin-bottom: 32px; display: flex; flex-direction: column; gap: 8px;">
            <div>📌 <strong>Identity & Document Verification:</strong> Administrator verification checks supporting verification documents for Farmers and Courier Logistics Partners.</div>
            <div>⏱️ <strong>Processing Time:</strong> Applications are reviewed by an Admin. You can sign in later to check whether your account has been approved.</div>
        </div>

        <a href="index.php?page=login" class="btn btn-primary" style="padding: 12px 32px;">
            Return to Login Screen
        </a>
    </div>
</div>
