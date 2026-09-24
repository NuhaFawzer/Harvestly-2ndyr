<div class="page-content" style="max-width: 800px; margin: 0 auto;">
    <div class="section-header">
        <div class="section-title-group">
            <h1>Platform Settings</h1>
            <p>Configure platform commission rate, distance-based delivery fees, and the fixed 48-hour completion rule</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px;">
            ✓ <?= sanitize($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div style="background: #fff; border: 1px solid var(--color-outline-variant); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-sm);">
        <form action="index.php?admin_action=update_settings" method="POST">
            <h2 style="font-size: 18px; font-weight: 800; color: var(--color-primary); margin-bottom: 16px; border-bottom: 2px solid var(--color-outline-variant); padding-bottom: 8px;">
                ⚙️ Financial & Commission Configuration
            </h2>

            <div class="form-group">
                <label class="form-label" for="set-comm">Platform Commission Rate (%)</label>
                <input type="number" step="0.1" id="set-comm" name="commission_rate" class="form-control" value="<?= sanitize($settings['commission_rate'] ?? '12.0'); ?>" required>
                <span style="font-size: 11px; color: var(--color-outline);">Percentage retained per order transaction before farmer payout settlement.</span>
            </div>

            <h2 style="font-size: 18px; font-weight: 800; color: var(--color-primary); margin-top: 28px; margin-bottom: 16px; border-bottom: 2px solid var(--color-outline-variant); padding-bottom: 8px;">
                🚚 Delivery Fee Configuration
            </h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="set-base-fee">Base Delivery Fee (Rs.)</label>
                    <input type="number" step="0.01" min="0" id="set-base-fee" name="delivery_base_fee" class="form-control" value="<?= sanitize($settings['delivery_base_fee'] ?? '0.00'); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="set-per-km-rate">Per Kilometer Rate (Rs.)</label>
                    <input type="number" step="0.01" min="0" id="set-per-km-rate" name="delivery_per_km_rate" class="form-control" value="<?= sanitize($settings['delivery_per_km_rate'] ?? '0.00'); ?>" required>
                </div>
            </div>

            <p style="font-size: 13px; color: var(--color-on-surface-variant); margin-top: 4px;">Delivery fee is calculated using the Farmer pickup district and Buyer destination district based on the pre-loaded district distance table.</p>
            <p style="font-size: 13px; color: var(--color-on-surface-variant); margin-top: 8px;"><strong>Formula:</strong> Delivery Fee = Base Delivery Fee + (District Reference Distance × Per-Km Rate)</p>
            <ul style="font-size: 13px; color: var(--color-on-surface-variant); margin: 12px 0 0 20px; line-height: 1.6;">
                <li>All 25 Sri Lankan districts</li>
                <li>District-to-district reference distances</li>
                <li>No GPS, maps, zones, or live distance API</li>
            </ul>

            <h2 style="font-size: 18px; font-weight: 800; color: var(--color-primary); margin-top: 28px; margin-bottom: 16px; border-bottom: 2px solid var(--color-outline-variant); padding-bottom: 8px;">
                ⏱️ Delivery Completion Policy
            </h2>
            <p style="font-size: 13px; color: var(--color-on-surface-variant);">After a Courier Partner marks an order Delivered, the Buyer can select Confirm Received. If no confirmation is received, the order is automatically marked Completed after 48 hours.</p>

            <button type="submit" class="btn btn-primary" style="margin-top: 20px; padding: 12px 32px;">
                Save Settings to Database
            </button>
        </form>
    </div>
</div>
