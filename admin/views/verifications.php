<div class="page-content">
    <div class="section-header">
        <div class="section-title-group">
            <h1>Verification Queue</h1>
            <p>Review uploaded verification documents for Farmers and Courier Partner companies</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px;">
            ✓ <?= sanitize($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div class="tabs-container">
        <div class="tabs-header">
            <button type="button" class="tab-btn active" data-tab="tab-farmers">
                👨‍🌾 Pending Farmers (<?= count($pendingFarmers); ?>)
            </button>
            <button type="button" class="tab-btn" data-tab="tab-couriers">
                🚚 Pending Courier Companies (<?= count($pendingCouriers); ?>)
            </button>
        </div>

        <!-- Tab 1: Pending Farmers -->
        <div class="tab-pane" id="tab-farmers" style="display: block;">
            <div class="card-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Applicant ID</th>
                            <th>Farmer Name</th>
                            <th>NIC Number</th>
                            <th>District</th>
                            <th>Farm Address</th>
                            <th>NIC Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pendingFarmers)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 32px; color: var(--color-outline);">No pending farmer applications.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pendingFarmers as $f): ?>
                                <tr>
                                    <td>#FAR-<?= sprintf('%04d', $f['id']); ?></td>
                                    <td><strong><?= sanitize($f['full_name']); ?></strong><br><span style="font-size:11px; color: var(--color-outline);"><?= sanitize($f['email']); ?></span></td>
                                    <td><code><?= sanitize($f['nic_number']); ?></code></td>
                                    <td><?= sanitize($f['district']); ?></td>
                                    <td style="max-width: 200px; font-size: 13px;"><?= sanitize($f['farm_address']); ?></td>
                                    <td>
                                        <a href="<?= baseUrl(sanitize($f['id_document_path'])); ?>" target="_blank" class="btn btn-outline btn-sm">📄 View NIC Doc</a>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 6px;">
                                            <form action="index.php?admin_action=verify_farmer" method="POST" style="display:inline;">
                                                <input type="hidden" name="farmer_id" value="<?= $f['id']; ?>">
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                                            </form>
                                            
                                            <form action="index.php?admin_action=verify_farmer" method="POST" style="display:inline;">
                                                <input type="hidden" name="farmer_id" value="<?= $f['id']; ?>">
                                                <input type="hidden" name="status" value="resubmit_requested">
                                                <button type="submit" class="btn btn-outline btn-sm">Resubmit</button>
                                            </form>

                                            <button type="button" class="btn btn-danger btn-sm" data-modal-target="modal-reject-farmer-<?= $f['id']; ?>">
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal: Reject Farmer with Reason -->
                                <div class="modal-backdrop" id="modal-reject-farmer-<?= $f['id']; ?>">
                                    <div class="modal-card">
                                        <div class="modal-header">
                                            <div class="modal-title" style="color: var(--color-error);">Reject Farmer Application</div>
                                            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
                                        </div>
                                        <form action="index.php?admin_action=verify_farmer" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="farmer_id" value="<?= $f['id']; ?>">
                                                <input type="hidden" name="status" value="rejected">
                                                <p style="font-size: 14px; color: var(--color-on-surface);">
                                                    Specify rejection reason for farmer <strong><?= sanitize($f['full_name']); ?></strong> (NIC: <?= sanitize($f['nic_number']); ?>):
                                                </p>
                                                <div class="form-group" style="margin-top: 12px;">
                                                    <label class="form-label" for="rej-reason-f-<?= $f['id']; ?>">Rejection Reason</label>
                                                    <textarea id="rej-reason-f-<?= $f['id']; ?>" name="rejection_reason" class="form-control" rows="3" placeholder="e.g. Uploaded NIC document image is blurry or unreadable..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline btn-sm" data-modal-close>Cancel</button>
                                                <button type="submit" class="btn btn-danger btn-sm">Confirm Rejection</button>
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

        <!-- Tab 2: Pending Courier Companies -->
        <div class="tab-pane" id="tab-couriers" style="display: none;">
            <div class="card-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Company ID</th>
                            <th>Logistics Company</th>
                            <th>Contact Person</th>
                            <th>District</th>
                            <th>Verification Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pendingCouriers)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 32px; color: var(--color-outline);">No pending courier company applications.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pendingCouriers as $c): ?>
                                <tr>
                                    <td>#CP-<?= sprintf('%04d', $c['id']); ?></td>
                                    <td><strong><?= sanitize($c['company_name']); ?></strong><br><span style="font-size:11px; color: var(--color-outline);"><?= sanitize($c['email']); ?></span></td>
                                    <td><?= sanitize($c['contact_person']); ?> (<?= sanitize($c['phone']); ?>)</td>
                                    <td><?= sanitize($c['district']); ?></td>
                                    <td>
                                        <a href="<?= baseUrl(sanitize($c['verification_document_path'])); ?>" target="_blank" class="btn btn-outline btn-sm">📋 View Document</a>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 6px;">
                                            <form action="index.php?admin_action=verify_courier" method="POST" style="display:inline;">
                                                <input type="hidden" name="courier_id" value="<?= $c['id']; ?>">
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                                            </form>
                                            
                                            <form action="index.php?admin_action=verify_courier" method="POST" style="display:inline;">
                                                <input type="hidden" name="courier_id" value="<?= $c['id']; ?>">
                                                <input type="hidden" name="status" value="resubmit_requested">
                                                <button type="submit" class="btn btn-outline btn-sm">Resubmit</button>
                                            </form>

                                            <button type="button" class="btn btn-danger btn-sm" data-modal-target="modal-reject-courier-<?= $c['id']; ?>">
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal: Reject Courier Company with Reason -->
                                <div class="modal-backdrop" id="modal-reject-courier-<?= $c['id']; ?>">
                                    <div class="modal-card">
                                        <div class="modal-header">
                                            <div class="modal-title" style="color: var(--color-error);">Reject Courier Company Application</div>
                                            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
                                        </div>
                                        <form action="index.php?admin_action=verify_courier" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="courier_id" value="<?= $c['id']; ?>">
                                                <input type="hidden" name="status" value="rejected">
                                                <p style="font-size: 14px; color: var(--color-on-surface);">
                                                    Specify rejection reason for company <strong><?= sanitize($c['company_name']); ?></strong>:
                                                </p>
                                                <div class="form-group" style="margin-top: 12px;">
                                                    <label class="form-label" for="rej-reason-c-<?= $c['id']; ?>">Rejection Reason</label>
                                                    <textarea id="rej-reason-c-<?= $c['id']; ?>" name="rejection_reason" class="form-control" rows="3" placeholder="e.g. Verification document image is blurry or unreadable..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline btn-sm" data-modal-close>Cancel</button>
                                                <button type="submit" class="btn btn-danger btn-sm">Confirm Rejection</button>
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
    </div>
</div>
