<?php
/**
 * Farmer Products List View
 * Displays farmer's listings with linked scientific shelf-life references
 */
require_once __DIR__ . '/../models/FarmerProductModel.php';
require_once __DIR__ . '/../../includes/functions.php';

$model = new FarmerProductModel();
$farmerId = (isset($_SESSION['user_id']) && isset($_SESSION['role']) && strtoupper($_SESSION['role']) === 'FARMER') ? (int)$_SESSION['user_id'] : 3;

$products = $model->getFarmerProducts($farmerId);

$error   = isset($_GET['error']) ? sanitize($_GET['error']) : null;
$success = isset($_GET['success']) ? sanitize($_GET['success']) : null;
?>

<div style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 26px; font-weight: 800; color: var(--color-on-surface);">My Farm Produce Listings</h1>
            <p style="font-size: 14px; color: var(--color-on-surface-variant);">Manage your active agricultural produce, pricing, and post-harvest scientific references</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="index.php?page=farmer_dashboard" class="btn btn-outline">Farmer Console</a>
            <a href="index.php?page=farmer_add_product" class="btn btn-primary">➕ Add New Produce Listing</a>
        </div>
    </div>

    <?php if ($success): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 14px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 24px;">
            ✓ <?= $success; ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="background-color: var(--color-error-container); color: var(--color-error); padding: 14px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 24px;">
            ⚠️ <?= $error; ?>
        </div>
    <?php endif; ?>

    <div style="background: var(--color-surface); border: 1px solid var(--color-outline-variant); border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-md);">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--color-surface-container-low); text-align: left;">
                    <th style="padding: 16px; width: 60px;"></th>
                    <th style="padding: 16px;">Product Title &amp; Category</th>
                    <th style="padding: 16px;">Price / Unit</th>
                    <th style="padding: 16px;">Available Quantity</th>
                    <th style="padding: 16px;">Grade &amp; Method</th>
                    <th style="padding: 16px;">Scientific Shelf-Life Reference</th>
                    <th style="padding: 16px;">Best Before</th>
                    <th style="padding: 16px;">Status</th>
                    <th style="padding: 16px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px; color: var(--color-outline);">
                            No produce listings found. Click <strong>Add New Produce Listing</strong> to create your first item.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr style="border-bottom: 1px solid var(--color-surface-container);">
                            <td style="padding: 12px 16px;">
                                <img src="<?= htmlspecialchars(getProductImage($p['product_name'], $p['category_name'])); ?>"
                                     alt="<?= sanitize($p['product_name']); ?>"
                                     style="width: 48px; height: 48px; border-radius: var(--radius-md); object-fit: cover; border: 1px solid var(--color-outline-variant);">
                            </td>
                            <td style="padding: 16px;">
                                <strong style="font-size: 15px; color: var(--color-on-surface);"><?= sanitize($p['product_name']); ?></strong>
                                <div style="font-size: 12px; color: var(--color-outline);"><?= sanitize($p['category_name']); ?></div>
                            </td>
                            <td style="padding: 16px;">
                                <strong style="color: var(--color-primary);">Rs. <?= number_format($p['unit_price'], 2); ?></strong>
                                <span style="font-size: 12px; color: var(--color-outline);">/ <?= sanitize($p['unit_label']); ?></span>
                            </td>
                            <td style="padding: 16px;">
                                <?= number_format($p['available_quantity'], 2); ?> <?= sanitize($p['unit_label']); ?>
                            </td>
                            <td style="padding: 16px;">
                                <span class="badge badge-info">Grade <?= sanitize($p['declared_grade']); ?></span>
                                <div style="font-size: 11px; color: var(--color-outline); margin-top: 4px;"><?= sanitize($p['growing_method']); ?></div>
                            </td>
                            <td style="padding: 16px;">
                                <?php if (!empty($p['product_reference_name'])): ?>
                                    <div style="font-weight: 700; color: var(--color-primary); font-size: 13px;">
                                        📖 <?= sanitize($p['product_reference_name']); ?>
                                    </div>
                                    <div style="font-size: 11px; color: var(--color-on-surface-variant);">
                                        <?php 
                                            $tMin = number_format($p['temperature_min_c'], 0);
                                            $tMax = number_format($p['temperature_max_c'], 0);
                                            $tStr = ($tMin === $tMax) ? "{$tMin}°C" : "{$tMin}–{$tMax}°C";

                                            $hMin = number_format($p['humidity_min_percent'], 0);
                                            $hMax = number_format($p['humidity_max_percent'], 0);
                                            $hStr = ($hMin === $hMax) ? "{$hMin}% RH" : "{$hMin}–{$hMax}% RH";

                                            $lMin = $p['storage_life_min_days'];
                                            $lMax = $p['storage_life_max_days'];
                                            $lStr = ($lMin === $lMax) ? "{$lMin}d" : "{$lMin}–{$lMax}d";
                                        ?>
                                        Ref: <?= $tStr; ?>, <?= $hStr; ?>, <?= $lStr; ?>
                                    </div>
                                <?php else: ?>
                                    <span style="font-size: 12px; color: var(--color-outline); font-style: italic;">No Reference</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 16px;">
                                <?php if (!empty($p['best_before_date'])): ?>
                                    <span style="font-size: 13px; font-weight: 600; color: var(--color-on-surface);"><?= sanitize($p['best_before_date']); ?></span>
                                <?php else: ?>
                                    <span style="font-size: 12px; color: var(--color-outline);">Not Set</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 16px;">
                                <?php if ($p['listing_status'] === 'ACTIVE'): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php elseif ($p['listing_status'] === 'SOLD_OUT'): ?>
                                    <span class="badge badge-warning">Sold Out</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 16px; text-align: right;">
                                <a href="index.php?page=farmer_edit_product&product_id=<?= $p['product_id']; ?>" class="btn btn-sm btn-outline">✏️ Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
