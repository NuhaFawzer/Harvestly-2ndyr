<div class="page-content">
    <div class="section-header">
        <div class="section-title-group">
            <h1>Listings Monitor</h1>
            <p>Monitor active farm produce listings across Sri Lankan agrarian regions</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px;">
            ✓ <?= sanitize($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div class="card-table-wrapper">
        <div class="table-toolbar">
            <div class="search-box">
                🔍 <input type="text" placeholder="Search product title or farmer..." data-table-search="listings-table">
            </div>

            <div class="filter-group">
                <span style="font-size: 12px; color: var(--color-outline);">ℹ️ Quality grades are farmer-declared</span>
            </div>
        </div>

        <table class="data-table" id="listings-table">
            <thead>
                <tr>
                    <th>Listing ID</th>
                    <th>Product Title</th>
                    <th>Category</th>
                    <th>Farmer (Origin)</th>
                    <th>Price / Unit</th>
                    <th>Farmer Declared Grade</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 32px; color: var(--color-outline);">No produce listings found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>#PRD-<?= sprintf('%04d', $p['id']); ?></td>
                            <td><strong><?= sanitize($p['title']); ?></strong></td>
                            <td><?= sanitize($p['category']); ?></td>
                            <td><?= sanitize($p['farmer_name']); ?> (<?= sanitize($p['district']); ?>)</td>
                            <td><strong>Rs. <?= number_format($p['price_per_unit'], 2); ?></strong> / <?= sanitize($p['unit_type']); ?></td>
                            <td><span class="badge badge-info"><?= sanitize($p['grade']); ?></span></td>
                            <td>
                                <?php if ($p['status'] === 'active'): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php elseif ($p['status'] === 'flagged'): ?>
                                    <span class="badge badge-pending">Flagged</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Removed</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px;">
                                    <?php if ($p['status'] === 'active'): ?>
                                        <form action="index.php?admin_action=update_product_status" method="POST" style="display:inline;">
                                            <input type="hidden" name="product_id" value="<?= $p['id']; ?>">
                                            <input type="hidden" name="status" value="flagged">
                                            <button type="submit" class="btn btn-outline btn-sm">Flag Listing</button>
                                        </form>

                                        <form action="index.php?admin_action=update_product_status" method="POST" style="display:inline;">
                                            <input type="hidden" name="product_id" value="<?= $p['id']; ?>">
                                            <input type="hidden" name="status" value="removed">
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="index.php?admin_action=update_product_status" method="POST" style="display:inline;">
                                            <input type="hidden" name="product_id" value="<?= $p['id']; ?>">
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="btn btn-primary btn-sm">Re-activate</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
