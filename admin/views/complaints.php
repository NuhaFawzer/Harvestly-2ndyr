<div class="page-content">
    <div class="section-header">
        <div class="section-title-group">
            <h1>Complaints / Issues</h1>
            <p>Review order and delivery complaints from Buyers, Farmers, and Courier Partners</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px;">
            ✓ <?= sanitize($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div class="card-table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Complaint ID</th>
                    <th>Order Number</th>
                    <th>Submitted By</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($complaints)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 32px; color: var(--color-outline);">No complaints or issues found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($complaints as $c): ?>
                        <tr>
                            <td>#CMP-<?= sprintf('%04d', $c['id']); ?></td>
                            <td><strong><?= sanitize($c['order_number']); ?></strong></td>
                            <td><?= sanitize($c['complainant_name']); ?> <small>(<?= sanitize($c['user_role']); ?>)</small></td>
                            <td><span class="badge badge-warning"><?= sanitize($c['category']); ?></span></td>
                            <td style="max-width: 250px; font-size: 13px;"><?= sanitize($c['description']); ?></td>
                            <td>
                                <?php if (strtoupper($c['status']) === 'RESOLVED'): ?>
                                    <span class="badge badge-success">Resolved</span>
                                <?php else: ?>
                                    <span class="badge badge-pending"><?= ucfirst(strtolower(sanitize($c['status']))); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline btn-sm" data-modal-target="modal-complaint-<?= $c['id']; ?>">
                                    Review & Resolve
                                </button>
                            </td>
                        </tr>

                        <!-- Modal: Complaint Detail & Resolution -->
                        <div class="modal-backdrop" id="modal-complaint-<?= $c['id']; ?>">
                            <div class="modal-card">
                                <div class="modal-header">
                                    <div class="modal-title">Complaint #CMP-<?= sprintf('%04d', $c['id']); ?> - <?= sanitize($c['order_number']); ?></div>
                                    <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
                                </div>
                                <form action="index.php?admin_action=resolve_complaint" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="complaint_id" value="<?= $c['id']; ?>">
                                        
                                        <div style="background: var(--color-surface-container-low); padding: 14px; border-radius: var(--radius-md); font-size: 13px; margin-bottom: 12px;">
                                            <div><strong>Category:</strong> <?= sanitize($c['category']); ?></div>
                                            <div><strong>Submitted By:</strong> <?= sanitize($c['complainant_name']); ?> (<?= sanitize($c['user_role']); ?>)</div>
                                            <div style="margin-top: 4px;"><strong>Description:</strong> <?= sanitize($c['description']); ?></div>
                                            <?php if ($c['evidence_path']): ?>
                                                <div style="margin-top: 8px;">
                                                    <strong>Evidence Attachment:</strong> 
                                                    <a href="<?= baseUrl(sanitize($c['evidence_path'])); ?>" target="_blank" style="color: var(--color-primary); font-weight: 700;">View Attachment File</a>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="disp-status-<?= $c['id']; ?>">Set Decision Status</label>
                                            <select id="disp-status-<?= $c['id']; ?>" name="status" class="form-control" required>
                                                <option value="resolved">Resolved</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="disp-notes-<?= $c['id']; ?>">Resolution Findings & Notes</label>
                                            <textarea id="complaint-notes-<?= $c['id']; ?>" name="resolution_notes" class="form-control" rows="3" placeholder="Enter resolution findings or notes..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline btn-sm" data-modal-close>Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Save Complaint Resolution</button>
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
