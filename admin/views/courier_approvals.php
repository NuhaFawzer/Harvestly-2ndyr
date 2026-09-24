<?php
/**
 * Courier Partner Approvals Dedicated View
 */
$currentPage = 'admin_courier_approvals';
?>

<div class="view-container">
    <div class="page-header mb-6">
        <h1 class="text-2xl font-bold text-on-surface">Courier Partner Registration Approvals</h1>
        <p class="text-sm text-on-surface-variant">Review supporting verification documents for logistics partner companies across Sri Lanka</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mb-6"><?= sanitize($_GET['success']); ?></div>
    <?php endif; ?>

    <div class="card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Courier Company / Organisation</th>
                        <th>Contact Person</th>
                        <th>Contact Email & Phone</th>
                        <th>District</th>
                        <th>Verification Document</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pendingCouriers)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-on-surface-variant">No pending Courier Partner registrations awaiting approval.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pendingCouriers as $courier): ?>
                            <tr>
                                <td><strong><?= sanitize($courier['company_name']); ?></strong></td>
                                <td><?= sanitize($courier['contact_person'] ?? 'Official Contact'); ?></td>
                                <td>
                                    <div><?= sanitize($courier['email']); ?></div>
                                    <div class="text-xs text-outline"><?= sanitize($courier['phone']); ?></div>
                                </td>
                                <td><?= sanitize($courier['district'] ?? 'Colombo'); ?></td>
                                <td>
                                    <?php if (!empty($courier['verification_document_path'])): ?>
                                        <span class="badge badge-info flex items-center gap-1 inline-flex">
                                            📜 <?= sanitize($courier['original_file_name'] ?? 'verification_document.pdf'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-outline">No document uploaded</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-warning"><?= strtoupper(sanitize($courier['status'])); ?></span>
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <form method="POST" action="index.php?admin_action=verify_courier" style="display:inline;">
                                            <input type="hidden" name="courier_id" value="<?= $courier['id']; ?>">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                                        </form>

                                        <form method="POST" action="index.php?admin_action=verify_courier" style="display:inline;" onsubmit="return confirm('Reject this Courier Partner company?');">
                                            <input type="hidden" name="courier_id" value="<?= $courier['id']; ?>">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
