<div class="page-content">
    <div class="section-header">
        <div class="section-title-group">
            <h1>Orders Monitor & Logistics Assignment</h1>
            <p>Track order fulfillment timeline and manually override delivery assignments when auto-assignment yields "Pending Assignment"</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px;">
            ✓ <?= sanitize($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div class="card-table-wrapper">
        <div class="table-toolbar">
            <div class="search-box">
                🔍 <input type="text" placeholder="Search order number or buyer..." data-table-search="orders-table">
            </div>
        </div>

        <table class="data-table" id="orders-table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Buyer Name</th>
                    <th>Assigned Courier Partner</th>
                    <th>Total Amount</th>
                    <th>Delivery Fee</th>
                    <th>Fulfillment Status</th>
                    <th>Date Placed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 32px; color: var(--color-outline);">No platform orders found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><strong><?= sanitize($o['order_number']); ?></strong></td>
                            <td><?= sanitize($o['buyer_name']); ?></td>
                            <td>
                                <?php if ($o['courier_name']): ?>
                                    🏢 <?= sanitize($o['courier_name']); ?>
                                <?php else: ?>
                                    <span class="badge badge-danger">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td><strong>Rs. <?= number_format($o['total_amount'], 2); ?></strong></td>
                            <td>Rs. <?= number_format($o['delivery_fee'], 2); ?></td>
                            <td>
                                <?php if ($o['status'] === 'delivered' || $o['status'] === 'confirmed_received'): ?>
                                    <span class="badge badge-success"><?= ucfirst(str_replace('_', ' ', $o['status'])); ?></span>
                                <?php elseif ($o['status'] === 'pending_assignment'): ?>
                                    <span class="badge badge-danger">Pending Assignment</span>
                                <?php else: ?>
                                    <span class="badge badge-pending"><?= ucfirst(str_replace('_', ' ', $o['status'])); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M d, Y', strtotime($o['created_at'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-outline btn-sm" data-modal-target="modal-override-<?= $o['id']; ?>">
                                    <?= ($o['status'] === 'pending_assignment') ? '⚠️ Override Courier' : 'Re-assign Fleet'; ?>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal: Manual Override Delivery Assignment -->
                        <div class="modal-backdrop" id="modal-override-<?= $o['id']; ?>">
                            <div class="modal-card">
                                <div class="modal-header">
                                    <div class="modal-title">Manual Delivery Assignment - <?= sanitize($o['order_number']); ?></div>
                                    <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
                                </div>
                                <form action="index.php?admin_action=override_delivery" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="order_id" value="<?= $o['id']; ?>">
                                        <p style="font-size: 13px; color: var(--color-on-surface-variant);">
                                            <strong>Buyer Address:</strong> <?= sanitize($o['delivery_address']); ?>
                                        </p>
                                        <div class="form-group" style="margin-top: 12px;">
                                            <label class="form-label" for="c-select-<?= $o['id']; ?>">Select Verified Courier Partner Company</label>
                                            <select id="c-select-<?= $o['id']; ?>" name="courier_id" class="form-control" required>
                                                <?php foreach ($couriers as $c): ?>
                                                    <option value="<?= $c['id']; ?>" <?= ($o['courier_id'] == $c['id']) ? 'selected' : ''; ?>>
                                                        🏢 <?= sanitize($c['name']); ?> (<?= sanitize($c['email']); ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline btn-sm" data-modal-close>Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Confirm Courier Assignment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
