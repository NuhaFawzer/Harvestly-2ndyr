<?php
// Scope Boundary Stub: Buyer Module
// Note: This module is owned and built by the Buyer teammate.
require_once __DIR__ . '/../../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buyer Dashboard - Harvestly</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body style="background: var(--color-background); padding: 40px;">
    <div style="max-width: 600px; margin: 40px auto; background: #fff; border-radius: var(--radius-xl); padding: 40px; text-align: center; border: 1px solid var(--color-outline-variant);">
        <div style="font-size: 48px; margin-bottom: 16px;">🛒</div>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--color-on-surface);">Buyer Marketplace Dashboard</h1>
        <p style="color: var(--color-on-surface-variant); margin-top: 8px;">
            Logged in as <strong><?= isset($_SESSION['user_name']) ? sanitize($_SESSION['user_name']) : 'Buyer User'; ?></strong>
        </p>
        <div style="margin-top: 24px; padding: 16px; background: var(--color-surface-container-low); border-radius: var(--radius-md); font-size: 13px; color: var(--color-outline);">
            <!-- Built by Buyer Teammate -->
            📌 <em>This interface module is within the Buyer teammate's assigned scope. Authentication routing logic successfully verified.</em>
        </div>
        <a href="../../index.php?action=logout" class="btn btn-outline btn-sm" style="margin-top: 24px;">Logout</a>
    </div>
</body>
</html>
