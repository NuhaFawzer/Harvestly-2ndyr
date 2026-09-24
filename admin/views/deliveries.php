<?php
/**
 * Deliveries Management Dedicated View
 */
$currentPage = 'admin_deliveries';
?>

<div class="view-container">
    <div class="page-header mb-6">
        <h1 class="text-2xl font-bold text-on-surface">Delivery Management & Rapid Logistics Monitor</h1>
        <p class="text-sm text-on-surface-variant">Track direct farm-to-table delivery statuses, assigned couriers, and delivery fee calculation</p>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Delivery ID</th>
                        <th>Order #</th>
                        <th>Farmer Origin</th>
                        <th>Buyer Destination</th>
                        <th>Courier Partner</th>
                        <th>Delivery Fee</th>
                        <th>Delivery Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($deliveries)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-8 text-on-surface-variant">No active or historical deliveries recorded yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($deliveries as $del): ?>
                            <tr>
                                <td><strong>#DEL-<?= sprintf('%03d', $del['id']); ?></strong></td>
                                <td class="font-bold text-primary"><?= sanitize($del['order_number']); ?></td>
                                <td>
                                    <div><?= sanitize($del['farmer_name'] ?? 'Local Grower'); ?></div>
                                    <div class="text-xs text-outline">📍 <?= sanitize($del['origin_district'] ?? 'Nuwara Eliya'); ?></div>
                                </td>
                                <td>
                                    <div><?= sanitize($del['buyer_name']); ?></div>
                                    <div class="text-xs text-outline">📍 <?= sanitize($del['destination_district'] ?? 'Colombo'); ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($del['courier_name'])): ?>
                                        <span class="badge badge-info"><?= sanitize($del['courier_name']); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong>Rs. <?= number_format($del['delivery_fee'], 2); ?></strong></td>
                                <td>
                                    <?php
                                    $st = strtoupper($del['delivery_status']);
                                    $badgeClass = 'badge-info';
                                    if ($st === 'DELIVERED' || $st === 'COMPLETED') $badgeClass = 'badge-success';
                                    else if ($st === 'PENDING_ASSIGNMENT' || $st === 'PENDING') $badgeClass = 'badge-warning';
                                    else if ($st === 'UNDELIVERABLE' || $st === 'CANCELLED') $badgeClass = 'badge-danger';
                                    ?>
                                    <span class="badge <?= $badgeClass; ?>"><?= sanitize($st); ?></span>
                                </td>
                                <td><?= date('M d, Y H:i', strtotime($del['order_date'] ?? 'now')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
