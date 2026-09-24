<div class="page-content">
    <div class="section-header">
        <div class="section-title-group">
            <h1>Weekly Settlements & Escrow Payouts</h1>
            <p>Monitor weekly escrow disbursements for Farmers and Courier Partner companies</p>
        </div>
    </div>

    <!-- Settlement KPI Overview -->
    <div class="kpi-grid" style="margin-bottom: 24px;">
        <div class="kpi-card">
            <div class="kpi-icon-box">💰</div>
            <div class="kpi-data">
                <span class="kpi-value">Rs. 1,258,80.00</span>
                <span class="kpi-label">Weekly Farmer Payouts</span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon-box">🚚</div>
            <div class="kpi-data">
                <span class="kpi-value">Rs. 345,000.00</span>
                <span class="kpi-label">Courier Logistics Payouts</span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon-box">🏦</div>
            <div class="kpi-data">
                <span class="kpi-value">12.0%</span>
                <span class="kpi-label">Platform Commission Retained</span>
            </div>
        </div>
    </div>

    <div class="card-table-wrapper">
        <div class="table-toolbar">
            <div class="search-box">
                🔍 <input type="text" placeholder="Search settlement reference or ID..." data-table-search="settlements-table">
            </div>
        </div>

        <table class="data-table" id="settlements-table">
            <thead>
                <tr>
                    <th>Reference No</th>
                    <th>Recipient Role</th>
                    <th>User ID</th>
                    <th>Settlement Amount</th>
                    <th>Payout Status</th>
                    <th>Settlement Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($settlements)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 32px; color: var(--color-outline);">No settlements recorded yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($settlements as $s): ?>
                        <tr>
                            <td><code><?= sanitize($s['reference_no']); ?></code></td>
                            <td>
                                <?php if ($s['user_type'] === 'farmer'): ?>
                                    <span class="badge badge-info">👨‍🌾 Farmer</span>
                                <?php else: ?>
                                    <span class="badge badge-info">🚚 Courier Company</span>
                                <?php endif; ?>
                            </td>
                            <td>#USR-<?= sprintf('%04d', $s['user_id']); ?></td>
                            <td><strong>Rs. <?= number_format($s['amount'], 2); ?></strong></td>
                            <td>
                                <?php if ($s['status'] === 'completed'): ?>
                                    <span class="badge badge-success">Completed</span>
                                <?php else: ?>
                                    <span class="badge badge-pending"><?= ucfirst(sanitize($s['status'])); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M d, Y', strtotime($s['settlement_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
