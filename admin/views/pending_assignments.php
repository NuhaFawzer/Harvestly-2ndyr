<?php
/**
 * Pending Courier Assignments Dedicated View (Manual Override)
 */
$currentPage = 'admin_pending_assignments';
?>

<div class="view-container">
    <div class="page-header mb-6">
        <h1 class="text-2xl font-bold text-on-surface">Pending Courier Partner Assignments</h1>
        <p class="text-sm text-on-surface-variant">Admin manual dispatch override for orders where automatic courier partner assignment failed or timed out</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mb-6"><?= sanitize($_GET['success']); ?></div>
    <?php endif; ?>

    <div class="card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Buyer</th>
                        <th>Farmer Origin District</th>
                        <th>Destination District</th>
                        <th>Assignment Reason / Status</th>
                        <th class="text-right">Admin Manual Override</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pendingOrders)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-on-surface-variant">✓ All active orders have been successfully assigned to Courier Partners.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pendingOrders as $order): ?>
                            <tr>
                                <td><strong class="text-primary"><?= sanitize($order['order_number']); ?></strong></td>
                                <td><?= sanitize($order['buyer_name']); ?></td>
                                <td>📍 <?= sanitize($order['origin_district'] ?? 'Nuwara Eliya'); ?></td>
                                <td>📍 <?= sanitize($order['destination_district'] ?? 'Colombo'); ?></td>
                                <td>
                                    <span class="badge badge-warning">Auto Assignment Failed / Timeout</span>
                                </td>
                                <td class="text-right">
                                    <form method="POST" action="index.php?admin_action=override_delivery" class="flex justify-end items-center gap-2">
                                        <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                        <select name="courier_id" class="input-field py-1 px-2 text-xs w-48" required>
                                            <option value="">Select Eligible Courier...</option>
                                            <?php foreach ($couriers as $c): ?>
                                                <option value="<?= $c['id']; ?>"><?= sanitize($c['name']); ?> (<?= sanitize($c['district'] ?? 'All Districts'); ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Assign Manually</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
