<div class="page-content">
    <div class="section-header">
        <div class="section-title-group">
            <h1>Notifications Center</h1>
            <p>Compose system announcements and review dispatched notification history log</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px;">
            ✓ <?= sanitize($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        <!-- Notification Composer Form -->
        <div style="background: #fff; border: 1px solid var(--color-outline-variant); border-radius: var(--radius-lg); padding: 24px; box-shadow: var(--shadow-sm); height: fit-content;">
            <h2 style="font-size: 18px; font-weight: 800; color: var(--color-on-surface); margin-bottom: 16px;">
                📢 Compose Announcement
            </h2>
            <form action="index.php?admin_action=send_notification" method="POST">
                <div class="form-group">
                    <label class="form-label" for="notif-scope">Recipient Scope</label>
                    <select id="notif-scope" name="recipient_scope" class="form-control" required>
                        <option value="all">Broadcast to All Users</option>
                        <option value="farmers">All Approved Farmers</option>
                        <option value="buyers">All Active Buyers</option>
                        <option value="couriers">All Courier Companies</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="notif-subj">Subject Line</label>
                    <input type="text" id="notif-subj" name="subject" class="form-control" placeholder="e.g. Weather Alert & Route Update" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="notif-msg">Message Body</label>
                    <textarea id="notif-msg" name="message" class="form-control" rows="4" placeholder="Enter broadcast message details..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 8px;">
                    Dispatch Notification
                </button>
            </form>
        </div>

        <!-- Sent Notification History Log Table -->
        <div class="card-table-wrapper">
            <div class="table-toolbar">
                <strong style="font-size: 15px; color: var(--color-on-surface);">Dispatched History Log</strong>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Target Scope</th>
                        <th>Subject</th>
                        <th>Message Content</th>
                        <th>Sent Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 32px; color: var(--color-outline);">No sent notifications recorded yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td>#NOT-<?= sprintf('%04d', $l['id']); ?></td>
                                <td><span class="badge badge-info"><?= ucfirst(sanitize($l['recipient_scope'])); ?></span></td>
                                <td><strong><?= sanitize($l['subject']); ?></strong></td>
                                <td style="max-width: 260px; font-size: 13px;"><?= sanitize($l['message']); ?></td>
                                <td><?= date('M d, H:i', strtotime($l['sent_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
