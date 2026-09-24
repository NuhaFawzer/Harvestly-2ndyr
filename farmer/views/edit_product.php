<?php
/**
 * Farmer Edit Product View
 * Includes Scientific Shelf-Life Reference Integration & Pre-loaded Values
 */
require_once __DIR__ . '/../models/FarmerProductModel.php';
require_once __DIR__ . '/../../includes/functions.php';

$model = new FarmerProductModel();
$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$farmerId  = (isset($_SESSION['user_id']) && isset($_SESSION['role']) && strtoupper($_SESSION['role']) === 'FARMER') ? (int)$_SESSION['user_id'] : 3;

$product = $model->getProductById($productId, $farmerId);

if (!$product) {
    header("Location: index.php?page=farmer_products&error=" . urlencode("Product not found or access denied."));
    exit();
}

$categories = $model->getCategories();
$shelfReferences = $model->getActiveShelfLifeReferences();

$error   = isset($_GET['error']) ? sanitize($_GET['error']) : null;
$success = isset($_GET['success']) ? sanitize($_GET['success']) : null;
?>

<div style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <a href="index.php?page=farmer_products" style="font-size: 13px; color: var(--color-outline); font-weight: 600; text-decoration: none;">&larr; Back to My Produce Listings</a>
            <h1 style="font-size: 26px; font-weight: 800; color: var(--color-on-surface); margin-top: 6px;">Edit Produce Listing</h1>
            <p style="font-size: 14px; color: var(--color-on-surface-variant);">Update pricing, stock, or shelf-life reference information for <?= sanitize($product['product_name']); ?></p>
        </div>
        <a href="index.php?page=farmer_dashboard" class="btn btn-outline btn-sm">Farmer Console</a>
    </div>

    <?php if ($error): ?>
        <div style="background-color: var(--color-error-container); color: var(--color-error); padding: 14px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 24px;">
            ⚠️ <?= $error; ?>
        </div>
    <?php endif; ?>

    <form action="index.php?action=farmer_update_product" method="POST" style="background: var(--color-surface); border: 1px solid var(--color-outline-variant); border-radius: var(--radius-xl); padding: 32px; box-shadow: var(--shadow-md);">
        <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">

        <!-- SECTION 1: BASIC PRODUCT INFORMATION -->
        <h3 style="font-size: 16px; font-weight: 700; color: var(--color-primary); border-bottom: 2px solid var(--color-surface-container); padding-bottom: 8px; margin-bottom: 20px;">
            1. Produce Details & Pricing
        </h3>

        <div class="form-group mb-4">
            <label class="form-label" for="product_name">Product Title / Crop Name *</label>
            <input type="text" id="product_name" name="product_name" class="form-control" value="<?= sanitize($product['product_name']); ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="mb-4">
            <div class="form-group">
                <label class="form-label" for="category_id">Agricultural Category *</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id']; ?>" <?= ($cat['category_id'] == $product['category_id']) ? 'selected' : ''; ?>>
                            <?= sanitize($cat['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="listing_type">Listing Type *</label>
                <select id="listing_type" name="listing_type" class="form-control" required>
                    <option value="AVAILABLE_NOW" <?= ($product['listing_type'] === 'AVAILABLE_NOW') ? 'selected' : ''; ?>>Available Now (Harvested)</option>
                    <option value="HARVEST_SOON" <?= ($product['listing_type'] === 'HARVEST_SOON') ? 'selected' : ''; ?>>Harvest Soon (Pre-Order)</option>
                    <option value="SEASONAL" <?= ($product['listing_type'] === 'SEASONAL') ? 'selected' : ''; ?>>Seasonal Special</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;" class="mb-4">
            <div class="form-group">
                <label class="form-label" for="unit_price">Unit Price (LKR) *</label>
                <input type="number" step="0.01" id="unit_price" name="unit_price" class="form-control" value="<?= number_format($product['unit_price'], 2, '.', ''); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="available_quantity">Available Quantity *</label>
                <input type="number" step="0.001" id="available_quantity" name="available_quantity" class="form-control" value="<?= number_format($product['available_quantity'], 3, '.', ''); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="unit_label">Unit Measurement *</label>
                <select id="unit_label" name="unit_label" class="form-control" required>
                    <option value="kg" <?= ($product['unit_label'] === 'kg') ? 'selected' : ''; ?>>Kilogram (kg)</option>
                    <option value="g" <?= ($product['unit_label'] === 'g') ? 'selected' : ''; ?>>Gram (g)</option>
                    <option value="bundle" <?= ($product['unit_label'] === 'bundle') ? 'selected' : ''; ?>>Bundle</option>
                    <option value="piece" <?= ($product['unit_label'] === 'piece') ? 'selected' : ''; ?>>Piece / Item</option>
                    <option value="crate" <?= ($product['unit_label'] === 'crate') ? 'selected' : ''; ?>>Crate</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="mb-4">
            <div class="form-group">
                <label class="form-label" for="declared_grade">Farmer-Declared Quality Grade *</label>
                <select id="declared_grade" name="declared_grade" class="form-control" required>
                    <option value="A" <?= ($product['declared_grade'] === 'A') ? 'selected' : ''; ?>>Grade A (Export / Premium Quality)</option>
                    <option value="B" <?= ($product['declared_grade'] === 'B') ? 'selected' : ''; ?>>Grade B (Standard Market Quality)</option>
                    <option value="C" <?= ($product['declared_grade'] === 'C') ? 'selected' : ''; ?>>Grade C (Processing / Value Add)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="growing_method">Farming & Growing Method</label>
                <select id="growing_method" name="growing_method" class="form-control">
                    <option value="CONVENTIONAL" <?= ($product['growing_method'] === 'CONVENTIONAL') ? 'selected' : ''; ?>>Conventional Farming</option>
                    <option value="ORGANIC" <?= ($product['growing_method'] === 'ORGANIC') ? 'selected' : ''; ?>>Certified Organic</option>
                    <option value="MIXED" <?= ($product['growing_method'] === 'MIXED') ? 'selected' : ''; ?>>Good Agricultural Practices (GAP)</option>
                </select>
            </div>
        </div>

        <div class="form-group mb-6">
            <label class="form-label" for="description">Produce Description & Batch Quality Notes</label>
            <textarea id="description" name="description" class="form-control" rows="3"><?= sanitize($product['description'] ?? ''); ?></textarea>
        </div>

        <!-- SECTION 2: SCIENTIFIC SHELF-LIFE REFERENCE -->
        <h3 style="font-size: 16px; font-weight: 700; color: var(--color-primary); border-bottom: 2px solid var(--color-surface-container); padding-bottom: 8px; margin-bottom: 20px;">
            2. Scientific Shelf-Life Reference (Optional)
        </h3>

        <div class="form-group mb-4">
            <label class="form-label" for="shelf_life_reference_id">Select Post-Harvest Scientific Shelf-Life Reference</label>
            <select id="shelf_life_reference_id" name="shelf_life_reference_id" class="form-control" onchange="updateShelfLifeDisplay();">
                <option value="" <?= (empty($product['shelf_life_reference_id'])) ? 'selected' : ''; ?>>No Reference Available / Not Applicable</option>
                <?php foreach ($shelfReferences as $ref): ?>
                    <?php 
                        $tempText = ($ref['temperature_min_c'] == $ref['temperature_max_c'])
                            ? number_format($ref['temperature_min_c'], 0) . '°C'
                            : number_format($ref['temperature_min_c'], 0) . '–' . number_format($ref['temperature_max_c'], 0) . '°C';
                        
                        $humText = ($ref['humidity_min_percent'] == $ref['humidity_max_percent'])
                            ? number_format($ref['humidity_min_percent'], 0) . '%'
                            : number_format($ref['humidity_min_percent'], 0) . '–' . number_format($ref['humidity_max_percent'], 0) . '%';
                        
                        $lifeText = ($ref['storage_life_min_days'] == $ref['storage_life_max_days'])
                            ? $ref['storage_life_min_days'] . ' days'
                            : $ref['storage_life_min_days'] . '–' . $ref['storage_life_max_days'] . ' days';

                        $isSelected = ($ref['shelf_life_reference_id'] == $product['shelf_life_reference_id']);
                    ?>
                    <option value="<?= $ref['shelf_life_reference_id']; ?>"
                            <?= $isSelected ? 'selected' : ''; ?>
                            data-temp="<?= $tempText; ?>"
                            data-humidity="<?= $humText; ?>"
                            data-life="<?= $lifeText; ?>"
                            data-guidance="<?= sanitize($ref['storage_guidance']); ?>"
                            data-source="<?= sanitize($ref['source_reference']); ?>">
                        <?= sanitize($ref['product_reference_name']); ?> (Ref: <?= $tempText; ?>, <?= $humText; ?>, <?= $lifeText; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <span style="font-size: 12px; color: var(--color-outline); margin-top: 4px; display: block;">
                Select a scientific crop reference to display recommended post-harvest temperature, humidity, and storage life.
            </span>
        </div>

        <!-- DYNAMIC REFERENCE INFORMATION DISPLAY BOX -->
        <div id="shelf-life-info-card" style="display: none; background: var(--color-surface-container-low); border: 1px solid var(--color-outline-variant); border-radius: var(--radius-md); padding: 20px; margin-bottom: 24px;">
            <div style="font-weight: 700; font-size: 14px; color: var(--color-primary); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <span>📖</span> <span>Reference Storage Information</span>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; margin-bottom: 14px;">
                <div style="background: #ffffff; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--color-outline-variant);">
                    <div style="font-size: 11px; color: var(--color-outline); font-weight: 600;">Recommended Temperature</div>
                    <div style="font-size: 16px; font-weight: 800; color: var(--color-on-surface);" id="ref-temp-val">--</div>
                </div>

                <div style="background: #ffffff; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--color-outline-variant);">
                    <div style="font-size: 11px; color: var(--color-outline); font-weight: 600;">Recommended Relative Humidity</div>
                    <div style="font-size: 16px; font-weight: 800; color: var(--color-on-surface);" id="ref-hum-val">--</div>
                </div>

                <div style="background: #ffffff; padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--color-outline-variant);">
                    <div style="font-size: 11px; color: var(--color-outline); font-weight: 600;">Reference Storage Life</div>
                    <div style="font-size: 16px; font-weight: 800; color: var(--color-primary);" id="ref-life-val">--</div>
                </div>
            </div>

            <div style="font-size: 13px; color: var(--color-on-surface-variant); margin-bottom: 10px; line-height: 1.5;" id="ref-guidance-val">
                --
            </div>

            <div style="font-size: 12px; color: var(--color-outline); font-style: italic; margin-bottom: 12px;">
                <strong>Source:</strong> <span id="ref-source-val">Rajapaksha et al. (2021)</span>
            </div>

            <div style="font-size: 12px; color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba; padding: 10px 14px; border-radius: var(--radius-sm);">
                ℹ️ <strong>Note:</strong> Reference values apply under recommended storage conditions. Actual freshness may vary based on handling and storage.
            </div>
        </div>

        <!-- SECTION 3: FARMER BATCH INFORMATION (INDEPENDENT) -->
        <h3 style="font-size: 16px; font-weight: 700; color: var(--color-primary); border-bottom: 2px solid var(--color-surface-container); padding-bottom: 8px; margin-bottom: 20px;">
            3. Farmer Batch & Expiry Information (Independent Batch Data)
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="mb-4">
            <div class="form-group">
                <label class="form-label" for="harvest_date">Harvest Date</label>
                <input type="date" id="harvest_date" name="harvest_date" class="form-control" value="<?= sanitize($product['harvest_date'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="best_before_date">Best Before Date (Farmer Provided)</label>
                <input type="date" id="best_before_date" name="best_before_date" class="form-control" value="<?= sanitize($product['best_before_date'] ?? ''); ?>">
                <span style="font-size: 11px; color: var(--color-outline); margin-top: 4px; display: block;">Set by farmer based on batch quality and handling. Will NOT be automatically overwritten.</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="mb-6">
            <div class="form-group">
                <label class="form-label" for="storage_guidance">Farmer Storage & Handling Instructions</label>
                <input type="text" id="storage_guidance" name="storage_guidance" class="form-control" value="<?= sanitize($product['storage_guidance'] ?? ''); ?>" placeholder="e.g. Keep in a dry, ventilated crate away from direct sunlight.">
            </div>

            <div class="form-group">
                <label class="form-label" for="listing_status">Listing Active Status</label>
                <select id="listing_status" name="listing_status" class="form-control" required>
                    <option value="ACTIVE" <?= ($product['listing_status'] === 'ACTIVE') ? 'selected' : ''; ?>>Active (Visible to Buyers)</option>
                    <option value="INACTIVE" <?= ($product['listing_status'] === 'INACTIVE') ? 'selected' : ''; ?>>Inactive (Hidden)</option>
                    <option value="SOLD_OUT" <?= ($product['listing_status'] === 'SOLD_OUT') ? 'selected' : ''; ?>>Sold Out</option>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="index.php?page=farmer_products" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 12px 28px;">Update Produce Listing</button>
        </div>

    </form>
</div>

<script>
function updateShelfLifeDisplay() {
    var select = document.getElementById('shelf_life_reference_id');
    var card = document.getElementById('shelf-life-info-card');
    
    if (!select || !select.value) {
        if (card) card.style.display = 'none';
        return;
    }

    var selectedOption = select.options[select.selectedIndex];
    if (!selectedOption || !selectedOption.dataset.temp) {
        if (card) card.style.display = 'none';
        return;
    }

    document.getElementById('ref-temp-val').innerText     = selectedOption.dataset.temp;
    document.getElementById('ref-hum-val').innerText      = selectedOption.dataset.humidity;
    document.getElementById('ref-life-val').innerText     = selectedOption.dataset.life;
    document.getElementById('ref-guidance-val').innerText = selectedOption.dataset.guidance;
    document.getElementById('ref-source-val').innerText   = selectedOption.dataset.source;

    card.style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function() {
    updateShelfLifeDisplay();
});
</script>
