<?php
// Scope Boundary Stub: Farmer Module
require_once __DIR__ . '/../../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer Dashboard - Harvestly</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body style="background: var(--color-background); padding: 40px;">
    <div style="max-width: 680px; margin: 40px auto; background: #fff; border-radius: var(--radius-xl); padding: 40px; text-align: center; border: 1px solid var(--color-outline-variant); box-shadow: var(--shadow-md);">
        <div style="font-size: 48px; margin-bottom: 16px;">👨‍🌾</div>
        <h1 style="font-size: 26px; font-weight: 800; color: var(--color-on-surface);">Farmer Producer Console</h1>
        <p style="color: var(--color-on-surface-variant); margin-top: 8px;">
            Logged in as <strong><?= isset($_SESSION['user_name']) ? sanitize($_SESSION['user_name']) : 'Sunil Perera (Farmer)'; ?></strong>
        </p>

        <div style="margin-top: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <a href="../../index.php?page=farmer_products" class="btn btn-primary" style="padding: 16px; font-weight: 700; text-decoration: none;">
                📦 View My Produce Listings
            </a>
            <a href="../../index.php?page=farmer_add_product" class="btn btn-secondary" style="padding: 16px; font-weight: 700; text-decoration: none; background: var(--color-secondary); color: #fff;">
                ➕ Add New Produce Listing
            </a>
        </div>

        <div style="margin-top: 24px; padding: 16px; background: var(--color-surface-container-low); border-radius: var(--radius-md); font-size: 13px; color: var(--color-on-surface-variant); text-align: left;">
            ℹ️ <strong>Scientific Shelf-Life Reference Integration Enabled:</strong> You can link crop listings to scientific post-harvest reference data (Rajapaksha et al. 2021) with recommended temperature, humidity, and storage life.
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center;">
            <a href="../../index.php" style="font-size: 13px; color: var(--color-outline); font-weight: 600;">&larr; Back to Public Landing Page</a>
            <a href="../../index.php?action=logout" class="btn btn-outline btn-sm">Logout Session</a>
        </div>
    </div>
</body>
</html>
