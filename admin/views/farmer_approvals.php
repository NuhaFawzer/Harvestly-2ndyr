<?php
/**
 * Farmer Approvals Dedicated View
 */
$currentPage = 'admin_farmer_approvals';
?>

<div class="view-container">
    <div class="page-header mb-6">
        <h1 class="text-2xl font-bold text-on-surface">Farmer Registration Approvals</h1>
        <p class="text-sm text-on-surface-variant">Review identity verification documents and approve or reject Sri Lankan grower accounts</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mb-6"><?= sanitize($_GET['success']); ?></div>
    <?php endif; ?>

    <div class="card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Farmer Name</th>
                        <th>Contact Email & Phone</th>
                        <th>District</th>
                        <th>Verification Document</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pendingFarmers)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-on-surface-variant">No pending farmer registrations awaiting approval.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pendingFarmers as $farmer): ?>
                            <tr>
                                <td>
                                    <strong><?= sanitize($farmer['full_name']); ?></strong>
                                    <div class="text-xs text-outline"><?= sanitize($farmer['farm_name'] ?? 'Local Grower'); ?></div>
                                </td>
                                <td>
                                    <div><?= sanitize($farmer['email']); ?></div>
                                    <div class="text-xs text-outline"><?= sanitize($farmer['phone']); ?></div>
                                </td>
                                <td><?= sanitize($farmer['district'] ?? 'Nuwara Eliya'); ?></td>
                                <td>
                                    <?php if (!empty($farmer['id_document_path'])): ?>
                                        <span class="badge badge-info flex items-center gap-1 inline-flex">
                                            📄 <?= sanitize($farmer['original_file_name'] ?? 'nic_document.jpg'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-outline">No document uploaded</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-warning"><?= strtoupper(sanitize($farmer['status'])); ?></span>
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <form method="POST" action="index.php?admin_action=verify_farmer" style="display:inline;">
                                            <input type="hidden" name="farmer_id" value="<?= $farmer['id']; ?>">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                                        </form>

                                        <form method="POST" action="index.php?admin_action=verify_farmer" style="display:inline;" onsubmit="return confirm('Reject this farmer account?');">
                                            <input type="hidden" name="farmer_id" value="<?= $farmer['id']; ?>">
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
