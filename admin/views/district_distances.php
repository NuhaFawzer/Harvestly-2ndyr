<?php
/**
 * District Distance Management Dedicated View
 * Reference Data for Inter-district Delivery Calculation
 */
$currentPage = 'admin_district_distances';
$fromDistrict = $fromDistrict ?? '';
$toDistrict = $toDistrict ?? '';
?>

<div class="view-container">
    <div class="page-header flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-on-surface">District Distance Management</h1>
            <p class="text-sm text-on-surface-variant">Reference matrix of Sri Lanka inter-district road distances in kilometers for automated per-KM delivery calculation</p>
        </div>
        <button type="button" class="btn btn-primary flex items-center gap-2" onclick="document.getElementById('addDistanceModal').style.display='flex'">
            + Add District Pair
        </button>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mb-6"><?= sanitize($_GET['success']); ?></div>
    <?php endif; ?>

    <div class="card mb-6">
        <form method="GET" action="index.php" class="flex flex-wrap gap-4 items-center">
            <input type="hidden" name="page" value="admin_district_distances">
            <div class="flex-1" style="min-width: 220px;">
                <label class="form-label" for="from-district-search">From District</label>
                <input type="search" id="from-district-search" name="from_district" class="input-field" value="<?= sanitize($fromDistrict); ?>" placeholder="e.g. Colombo">
            </div>
            <div class="flex-1" style="min-width: 220px;">
                <label class="form-label" for="to-district-search">To District</label>
                <input type="search" id="to-district-search" name="to_district" class="input-field" value="<?= sanitize($toDistrict); ?>" placeholder="e.g. Kandy">
            </div>
            <button type="submit" class="btn btn-primary">Search Distances</button>
            <?php if ($fromDistrict !== '' || $toDistrict !== ''): ?>
                <a href="index.php?page=admin_district_distances" class="btn btn-outline">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Distance ID</th>
                        <th>From District</th>
                        <th>To District</th>
                        <th>Distance (KM)</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($distances)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-8 text-on-surface-variant">No inter-district distance reference data loaded.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($distances as $dist): ?>
                            <tr>
                                <td>#DIST-<?= sprintf('%04d', $dist['id']); ?></td>
                                <td><strong><?= sanitize($dist['from_district']); ?></strong></td>
                                <td><strong><?= sanitize($dist['to_district']); ?></strong></td>
                                <td><span class="badge badge-info"><?= number_format($dist['distance_km'], 2); ?> KM</span></td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="openEditDistanceModal(<?= $dist['id']; ?>, '<?= addslashes(sanitize($dist['from_district'])); ?>', '<?= addslashes(sanitize($dist['to_district'])); ?>', <?= $dist['distance_km']; ?>)">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal 1: Add Distance Pair -->
<div id="addDistanceModal" class="modal-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:500px; padding:24px; background:#fff; border-radius:var(--radius-lg);">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Add Inter-District Distance</h3>
            <button type="button" onclick="document.getElementById('addDistanceModal').style.display='none'" class="text-2xl font-bold">&times;</button>
        </div>
        <form method="POST" action="index.php?admin_action=add_district_distance">
            <div class="mb-4">
                <label class="form-label">From District *</label>
                <input type="text" name="from_district" class="input-field" required placeholder="e.g. Nuwara Eliya">
            </div>
            <div class="mb-4">
                <label class="form-label">To District *</label>
                <input type="text" name="to_district" class="input-field" required placeholder="e.g. Colombo">
            </div>
            <div class="mb-6">
                <label class="form-label">Distance in KM *</label>
                <input type="number" step="0.1" name="distance_km" class="input-field" required placeholder="e.g. 170.5">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('addDistanceModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Distance</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Edit Distance Pair -->
<div id="editDistanceModal" class="modal-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:500px; padding:24px; background:#fff; border-radius:var(--radius-lg);">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Edit District Distance</h3>
            <button type="button" onclick="document.getElementById('editDistanceModal').style.display='none'" class="text-2xl font-bold">&times;</button>
        </div>
        <form method="POST" action="index.php?admin_action=update_district_distance">
            <input type="hidden" name="distance_id" id="edit_dist_id">
            <div class="mb-4">
                <label class="form-label">From District</label>
                <input type="text" id="edit_from_district" class="input-field" readonly>
            </div>
            <div class="mb-4">
                <label class="form-label">To District</label>
                <input type="text" id="edit_to_district" class="input-field" readonly>
            </div>
            <div class="mb-6">
                <label class="form-label">Distance in KM *</label>
                <input type="number" step="0.1" name="distance_km" id="edit_distance_km" class="input-field" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('editDistanceModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Distance</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditDistanceModal(id, from, to, km) {
    document.getElementById('edit_dist_id').value = id;
    document.getElementById('edit_from_district').value = from;
    document.getElementById('edit_to_district').value = to;
    document.getElementById('edit_distance_km').value = km;
    document.getElementById('editDistanceModal').style.display = 'flex';
}
</script>
