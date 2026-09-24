<?php
$reportType = $_GET['type'] ?? 'orders';
$startDate = $_GET['start'] ?? date('Y-m-01');
$endDate = $_GET['end'] ?? date('Y-m-d');
?>
<div class="page-content">
    <div class="section-header">
        <div class="section-title-group">
            <h1>Reports Generator</h1>
            <p>Generate, preview, and export platform operation metrics</p>
        </div>
        <div style="display: flex; gap: 12px;" class="no-print">
            <button type="button" class="btn btn-outline" data-export-csv="report-data-table">
                Export CSV
            </button>
            <button type="button" class="btn btn-primary" data-trigger-print>
                Print / Save PDF
            </button>
        </div>
    </div>

    <!-- Filter Generator Controls -->
    <div style="background: #fff; border: 1px solid var(--color-outline-variant); border-radius: var(--radius-lg); padding: 24px; margin-bottom: 24px;" class="no-print">
        <form action="index.php" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end;">
            <input type="hidden" name="page" value="admin_reports">
            
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="rep-type">Report Module Type</label>
                <select id="rep-type" name="type" class="form-control">
                    <option value="orders" <?= ($reportType==='orders')?'selected':''; ?>>Order Fulfillment & Revenue Report</option>
                    <option value="settlements" <?= ($reportType==='settlements')?'selected':''; ?>>Weekly Settlements & Escrow Report</option>
                    <option value="farmers" <?= ($reportType==='farmers')?'selected':''; ?>>Farmer Growth & Verification Report</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="rep-start">Start Date</label>
                <input type="date" id="rep-start" name="start" class="form-control" value="<?= sanitize($startDate); ?>">
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="rep-end">End Date</label>
                <input type="date" id="rep-end" name="end" class="form-control" value="<?= sanitize($endDate); ?>">
            </div>

            <div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Generate Report Preview
                </button>
            </div>
        </form>
    </div>

    <!-- Report Preview Document -->
    <div class="card-table-wrapper" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid var(--color-outline-variant); padding-bottom: 16px;">
            <div>
                <h2 style="font-size: 20px; font-weight: 800; color: var(--color-primary);">Harvestly Executive Summary Report</h2>
                <p style="font-size: 13px; color: var(--color-on-surface-variant); margin-top: 4px;">
                    Module: <strong><?= ucfirst(sanitize($reportType)); ?></strong> | Date Range: <strong><?= sanitize($startDate); ?></strong> to <strong><?= sanitize($endDate); ?></strong>
                </p>
            </div>
            <div style="text-align: right; font-size: 12px; color: var(--color-outline);">
                Generated On: <?= date('Y-m-d H:i'); ?><br>
                Authority: System Administrator
            </div>
        </div>

        <table class="data-table" id="report-data-table">
            <thead>
                <tr>
                    <?php if ($reportType === 'orders'): ?>
                        <th>Order Number</th>
                        <th>Total Amount (Rs.)</th>
                        <th>Delivery Fee (Rs.)</th>
                        <th>Status</th>
                        <th>Date Placed</th>
                    <?php elseif ($reportType === 'settlements'): ?>
                        <th>Reference No</th>
                        <th>Recipient Type</th>
                        <th>User ID</th>
                        <th>Amount (Rs.)</th>
                        <th>Status</th>
                        <th>Settlement Date</th>
                    <?php else: ?>
                        <th>Farmer Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Registered Date</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reportData)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 32px; color: var(--color-outline);">No record data found for selected date range.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reportData as $row): ?>
                        <tr>
                            <?php if ($reportType === 'orders'): ?>
                                <td><strong><?= sanitize($row['order_number']); ?></strong></td>
                                <td>Rs. <?= number_format($row['total_amount'], 2); ?></td>
                                <td>Rs. <?= number_format($row['delivery_fee'], 2); ?></td>
                                <td><span class="badge badge-success"><?= sanitize($row['status']); ?></span></td>
                                <td><?= date('Y-m-d', strtotime($row['created_at'])); ?></td>
                            <?php elseif ($reportType === 'settlements'): ?>
                                <td><code><?= sanitize($row['reference_no']); ?></code></td>
                                <td><?= ucfirst(sanitize($row['user_type'])); ?></td>
                                <td>#USR-<?= sprintf('%04d', $row['user_id']); ?></td>
                                <td>Rs. <?= number_format($row['amount'], 2); ?></td>
                                <td><span class="badge badge-success"><?= sanitize($row['status']); ?></span></td>
                                <td><?= sanitize($row['settlement_date']); ?></td>
                            <?php else: ?>
                                <td><strong><?= sanitize($row['full_name']); ?></strong></td>
                                <td><?= sanitize($row['email']); ?></td>
                                <td><?= sanitize($row['phone']); ?></td>
                                <td><span class="badge badge-success"><?= sanitize($row['status']); ?></span></td>
                                <td><?= date('Y-m-d', strtotime($row['created_at'])); ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
