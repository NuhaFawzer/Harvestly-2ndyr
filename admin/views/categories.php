<?php
/**
 * Product Categories View - Admin Main Assessment CRUD Feature
 */
$currentPage = 'admin_categories';

/**
 * Map category name to a produce emoji icon
 */
function getCategoryIcon($name) {
    $n = strtolower($name);
    if (strpos($n, 'fruit') !== false) return '🍊';
    if (strpos($n, 'vegetable') !== false || strpos($n, 'veg') !== false) return '🥦';
    if (strpos($n, 'grain') !== false || strpos($n, 'rice') !== false || strpos($n, 'cereal') !== false) return '🌾';
    if (strpos($n, 'spice') !== false || strpos($n, 'herb') !== false) return '🌿';
    if (strpos($n, 'organic') !== false) return '🌱';
    if (strpos($n, 'dairy') !== false) return '🥛';
    if (strpos($n, 'leaf') !== false || strpos($n, 'green') !== false) return '🥬';
    if (strpos($n, 'root') !== false || strpos($n, 'tuber') !== false) return '🥕';
    if (strpos($n, 'mushroom') !== false) return '🍄';
    if (strpos($n, 'flower') !== false) return '🌸';
    return '🌾';
}
?>

<div class="view-container">
    <div class="page-header flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-on-surface">Product Category Management</h1>
            <p class="text-sm text-on-surface-variant">Manage produce categories across the Harvestly platform</p>
        </div>
        <button type="button" class="btn btn-primary flex items-center gap-2" onclick="document.getElementById('addCategoryModal').style.display='flex'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Category
        </button>
    </div>

    <!-- Top Controls & Filters -->
    <div class="card mb-6">
        <form method="GET" action="index.php" class="flex flex-wrap gap-4 items-center">
            <input type="hidden" name="page" value="admin_categories">
            
            <div class="flex-1 min-w-[240px]">
                <input type="text" name="search" class="input-field" placeholder="Search categories..." value="<?= isset($_GET['search']) ? sanitize($_GET['search']) : ''; ?>">
            </div>

            <div class="w-48">
                <select name="status" class="input-field">
                    <option value="all" <?= (($_GET['status'] ?? '') === 'all') ? 'selected' : ''; ?>>All Statuses</option>
                    <option value="active" <?= (($_GET['status'] ?? '') === 'active') ? 'selected' : ''; ?>>Active Only</option>
                    <option value="inactive" <?= (($_GET['status'] ?? '') === 'inactive') ? 'selected' : ''; ?>>Inactive Only</option>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary">Filter</button>
            <?php if (isset($_GET['search']) || isset($_GET['status'])): ?>
                <a href="index.php?page=admin_categories" class="btn btn-outline">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mb-6"><?= sanitize($_GET['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger mb-6"><?= sanitize($_GET['error']); ?></div>
    <?php endif; ?>

    <!-- Categories Data Table (READ) -->
    <div class="card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 48px;"></th>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-on-surface-variant">No product categories found matching criteria.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td style="padding: 10px 16px; font-size: 24px; text-align: center; line-height: 1;"><?= getCategoryIcon($cat['category_name']); ?></td>
                                <td><strong>#CAT-<?= sprintf('%03d', $cat['id']); ?></strong></td>
                                <td class="font-bold text-primary"><?= sanitize($cat['category_name']); ?></td>
                                <td><?= sanitize($cat['description'] ?? 'Agricultural produce category'); ?></td>
                                <td>
                                    <?php if ($cat['status'] === 'active'): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <!-- UPDATE Action -->
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="openEditModal(<?= $cat['id']; ?>, '<?= addslashes(sanitize($cat['category_name'])); ?>', '<?= addslashes(sanitize($cat['description'] ?? '')); ?>', '<?= $cat['status']; ?>')">
                                            Edit
                                        </button>
                                        
                                        <!-- DELETE / DEACTIVATE Action -->
                                        <?php if ($cat['status'] === 'active'): ?>
                                            <form method="POST" action="index.php?admin_action=deactivate_category" style="display:inline;" onsubmit="return confirm('Deactivate this product category?');">
                                                <input type="hidden" name="category_id" value="<?= $cat['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline text-error">Deactivate</button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="index.php?admin_action=delete_category" style="display:inline;" onsubmit="return confirm('Permanently delete this product category?');">
                                                <input type="hidden" name="category_id" value="<?= $cat['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
</div>


<!-- Modal 1: CREATE Category -->
<div id="addCategoryModal" class="modal-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:500px; padding:24px; background:#fff; border-radius:var(--radius-lg);">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Add New Product Category</h3>
            <button type="button" onclick="document.getElementById('addCategoryModal').style.display='none'" class="text-2xl font-bold">&times;</button>
        </div>
        <form method="POST" action="index.php?admin_action=create_category">
            <?= csrfField(); ?>
            <div class="mb-4">
                <label class="form-label">Category Name *</label>
                <input type="text" name="category_name" class="input-field" required placeholder="e.g. Organic Produce, Spices, Grains">
            </div>
            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" class="input-field" rows="3" placeholder="Brief details regarding this produce category..."></textarea>
            </div>
            <div class="mb-6">
                <label class="form-label">Status</label>
                <select name="status" class="input-field">
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('addCategoryModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: UPDATE Category -->
<div id="editCategoryModal" class="modal-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:500px; padding:24px; background:#fff; border-radius:var(--radius-lg);">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Edit Product Category</h3>
            <button type="button" onclick="document.getElementById('editCategoryModal').style.display='none'" class="text-2xl font-bold">&times;</button>
        </div>
        <form method="POST" action="index.php?admin_action=update_category">
            <?= csrfField(); ?>
            <input type="hidden" name="category_id" id="edit_category_id">
            <div class="mb-4">
                <label class="form-label">Category Name *</label>
                <input type="text" name="category_name" id="edit_category_name" class="input-field" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" id="edit_description" class="input-field" rows="3"></textarea>
            </div>
            <div class="mb-6">
                <label class="form-label">Status</label>
                <select name="status" id="edit_status" class="input-field">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('editCategoryModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, desc, status) {
    document.getElementById('edit_category_id').value = id;
    document.getElementById('edit_category_name').value = name;
    document.getElementById('edit_description').value = desc;
    document.getElementById('edit_status').value = status;
    document.getElementById('editCategoryModal').style.display = 'flex';
}
</script>
