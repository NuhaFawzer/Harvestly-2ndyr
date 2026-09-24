<div class="page-content">
    <div class="section-header">
        <div class="section-title-group">
            <h1>Regions, Delivery Tiers & Hub Logistics</h1>
            <p>Manage Sri Lankan provincial zone fee tiers, internal sorting hubs, and Courier Partner regional coverage</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px;">
            ✓ <?= sanitize($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Zone Fee Tier Matrix -->
    <div style="margin-bottom: 32px;">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--color-on-surface); margin-bottom: 16px;">
            🚚 Delivery Fee Matrix (Zone-Based Tiers)
        </h2>
        <div class="card-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tier ID</th>
                        <th>Zone Description</th>
                        <th>Base Delivery Fee (Rs.)</th>
                        <th>Per Kg Fee (Rs.)</th>
                        <th>Est. Transit Days</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tiers as $t): ?>
                        <tr>
                            <td>#TIER-0<?= $t['id']; ?></td>
                            <td><strong><?= sanitize($t['zone_name']); ?></strong></td>
                            <td>Rs. <?= number_format($t['base_fee'], 2); ?></td>
                            <td>Rs. <?= number_format($t['per_kg_fee'], 2); ?> / kg</td>
                            <td><?= $t['min_days']; ?> - <?= $t['max_days']; ?> Days</td>
                            <td>
                                <button type="button" class="btn btn-outline btn-sm" data-modal-target="modal-tier-<?= $t['id']; ?>">
                                    Edit Tier Rates
                                </button>
                            </td>
                        </tr>

                        <!-- Modal: Edit Fee Tier Rates -->
                        <div class="modal-backdrop" id="modal-tier-<?= $t['id']; ?>">
                            <div class="modal-card">
                                <div class="modal-header">
                                    <div class="modal-title">Edit Zone Fee Matrix - Tier #0<?= $t['id']; ?></div>
                                    <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
                                </div>
                                <form action="index.php?admin_action=update_zone_fee" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="tier_id" value="<?= $t['id']; ?>">
                                        <div style="font-weight: 700; font-size: 14px; margin-bottom: 12px; color: var(--color-primary);">
                                            <?= sanitize($t['zone_name']); ?>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="base-fee-<?= $t['id']; ?>">Base Delivery Fee (Rs.)</label>
                                            <input type="number" step="0.01" id="base-fee-<?= $t['id']; ?>" name="base_fee" class="form-control" value="<?= $t['base_fee']; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="kg-fee-<?= $t['id']; ?>">Per Kg Additional Fee (Rs.)</label>
                                            <input type="number" step="0.01" id="kg-fee-<?= $t['id']; ?>" name="per_kg_fee" class="form-control" value="<?= $t['per_kg_fee']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline btn-sm" data-modal-close>Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Save Fee Matrix Rates</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Internal Logistics Hubs & District Mapping -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
        <div>
            <h2 style="font-size: 18px; font-weight: 800; color: var(--color-on-surface); margin-bottom: 16px;">
                🏬 Internal Courier Logistics Hubs
            </h2>
            <div class="card-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Hub Code</th>
                            <th>Hub Name</th>
                            <th>District</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hubs as $h): ?>
                            <tr>
                                <td><code><?= sanitize($h['code']); ?></code></td>
                                <td><strong><?= sanitize($h['hub_name']); ?></strong></td>
                                <td><?= sanitize($h['district']); ?></td>
                                <td><span class="badge badge-success"><?= ucfirst(sanitize($h['status'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h2 style="font-size: 18px; font-weight: 800; color: var(--color-on-surface); margin-bottom: 16px;">
                🗺️ District to Primary Hub Mapping
            </h2>
            <div class="card-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>District</th>
                            <th>Province</th>
                            <th>Assigned Primary Hub</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($districts as $d): ?>
                            <tr>
                                <td><strong><?= sanitize($d['district_name']); ?></strong></td>
                                <td><?= sanitize($d['province']); ?></td>
                                <td>🏬 <?= sanitize($d['hub_name'] ?? 'Unassigned'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Courier Partner Registered Coverage -->
    <div>
        <h2 style="font-size: 18px; font-weight: 800; color: var(--color-on-surface); margin-bottom: 16px;">
            🚚 Courier Partner Registered Fleet Coverage
        </h2>
        <div class="card-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Courier Company</th>
                        <th>Covered District</th>
                        <th>Coverage Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($coverage)): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 24px; color: var(--color-outline);">No courier coverage entries registered.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($coverage as $c): ?>
                            <tr>
                                <td><strong>🏢 <?= sanitize($c['company_name']); ?></strong></td>
                                <td>📍 <?= sanitize($c['district_name']); ?> District</td>
                                <td><span class="badge badge-success"><?= ucfirst(sanitize($c['status'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
