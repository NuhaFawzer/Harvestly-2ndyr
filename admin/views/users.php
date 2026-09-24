<?php
$currentRole = $_GET['role'] ?? 'all';
$currentStatus = $_GET['status'] ?? 'all';
$error = $_GET['error'] ?? null;
?>
<div class="page-content">
    <div class="section-header">
        <div class="section-title-group">
            <h1>User Management</h1>
            <p>Create, view, edit details, manage status, and delete Buyers, Farmers, and Courier Companies</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-modal-target="modal-create-user">
                ➕ Create New User
            </button>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: #d1e7dd; color: #0f5132; padding: 12px 16px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px;">
            ✓ <?= sanitize($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="background: var(--color-error-container); color: var(--color-error); padding: 12px 16px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px;">
            ⚠️ <?= sanitize($error); ?>
        </div>
    <?php endif; ?>

    <div class="card-table-wrapper">
        <div class="table-toolbar">
            <div class="search-box">
                🔍 <input type="text" placeholder="Search user name or email..." data-table-search="users-table">
            </div>

            <form action="index.php" method="GET" class="filter-group">
                <input type="hidden" name="page" value="admin_users">
                <select name="role" class="filter-select" onchange="this.form.submit()">
                    <option value="all" <?= ($currentRole==='all')?'selected':''; ?>>All Roles</option>
                    <option value="buyer" <?= ($currentRole==='buyer')?'selected':''; ?>>Buyer</option>
                    <option value="farmer" <?= ($currentRole==='farmer')?'selected':''; ?>>Farmer</option>
                    <option value="courier" <?= ($currentRole==='courier')?'selected':''; ?>>Courier Partner (Company)</option>
                </select>

                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="all" <?= ($currentStatus==='all')?'selected':''; ?>>All Statuses</option>
                    <option value="active" <?= ($currentStatus==='active')?'selected':''; ?>>Active / Approved</option>
                    <option value="pending" <?= ($currentStatus==='pending')?'selected':''; ?>>Pending</option>
                    <option value="suspended" <?= ($currentStatus==='suspended')?'selected':''; ?>>Suspended</option>
                </select>
            </form>
        </div>

        <table class="data-table" id="users-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name / Company</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>District</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 32px; color: var(--color-outline);">No users found matching filters.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#USR-<?= sprintf('%04d', $u['id']); ?></td>
                            <td><strong><?= sanitize($u['name']); ?></strong></td>
                            <td><?= sanitize($u['email']); ?></td>
                            <td><?= sanitize($u['phone']); ?></td>
                            <td><span class="badge badge-info"><?= sanitize($u['role']); ?></span></td>
                            <td><?= sanitize($u['district'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if (in_array($u['status'], ['active', 'approved'])): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php elseif ($u['status'] === 'pending'): ?>
                                    <span class="badge badge-pending">Pending</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><?= ucfirst(sanitize($u['status'])); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                    <!-- View Details Button -->
                                    <button type="button" class="btn btn-outline btn-sm" data-modal-target="modal-view-<?= $u['role']; ?>-<?= $u['id']; ?>">
                                        👁️ View
                                    </button>

                                    <!-- Edit Details Button -->
                                    <button type="button" class="btn btn-outline btn-sm" data-modal-target="modal-edit-<?= $u['role']; ?>-<?= $u['id']; ?>">
                                        ✏️ Edit
                                    </button>

                                    <!-- Suspend / Reactivate Quick Action -->
                                    <?php if (in_array($u['status'], ['active', 'approved'])): ?>
                                        <form action="index.php?admin_action=update_user_status" method="POST" style="display:inline;">
                                            <input type="hidden" name="user_type" value="<?= strtolower($u['role']); ?>">
                                            <input type="hidden" name="user_id" value="<?= $u['id']; ?>">
                                            <input type="hidden" name="status" value="suspended">
                                            <button type="submit" class="btn btn-outline btn-sm" style="color: var(--color-warning);">Suspend</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="index.php?admin_action=update_user_status" method="POST" style="display:inline;">
                                            <input type="hidden" name="user_type" value="<?= strtolower($u['role']); ?>">
                                            <input type="hidden" name="user_id" value="<?= $u['id']; ?>">
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="btn btn-primary btn-sm">Reactivate</button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-danger btn-sm" data-modal-target="modal-delete-<?= $u['role']; ?>-<?= $u['id']; ?>">
                                        🗑️ Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal: READ/VIEW USER DETAILS -->
                        <div class="modal-backdrop" id="modal-view-<?= $u['role']; ?>-<?= $u['id']; ?>">
                            <div class="modal-card">
                                <div class="modal-header">
                                    <div class="modal-title">User Details - #USR-<?= sprintf('%04d', $u['id']); ?></div>
                                    <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
                                </div>
                                <div class="modal-body" style="font-size: 14px; gap: 12px;">
                                    <div><strong>Full Name / Company:</strong> <?= sanitize($u['name']); ?></div>
                                    <div><strong>Email Address:</strong> <?= sanitize($u['email']); ?></div>
                                    <div><strong>Phone Number:</strong> <?= sanitize($u['phone']); ?></div>
                                    <div><strong>Role:</strong> <?= sanitize($u['role']); ?></div>
                                    <div><strong>District / Location:</strong> <?= sanitize($u['district'] ?? 'N/A'); ?></div>
                                    <div><strong>Full Address:</strong> <?= sanitize($u['detail'] ?? 'N/A'); ?></div>

                                    <?php if ($u['nic_number']): ?>
                                        <div><strong>NIC Number:</strong> <code><?= sanitize($u['nic_number']); ?></code></div>
                                    <?php endif; ?>

                                        <?php if (isset($u['contact_person']) && $u['contact_person']): ?>
                                            <div><strong>Contact Person:</strong> <?= sanitize($u['contact_person']); ?></div>
                                        <?php endif; ?>

                                        <?php if ($u['document_path']): ?>
                                            <div style="margin-top: 8px;">
                                                <strong>Verification Attachment:</strong>
                                                <a href="<?= baseUrl(sanitize($u['document_path'])); ?>" target="_blank" style="color: var(--color-primary); font-weight: 700; display: block; margin-top: 4px;">
                                                    📄 View Uploaded Verification Document
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <div><strong>Account Status:</strong> <span class="badge badge-info"><?= ucfirst(sanitize($u['status'])); ?></span></div>
                                        <div><strong>Registered Date:</strong> <?= date('Y-m-d H:i:s', strtotime($u['created_at'])); ?></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline btn-sm" data-modal-close>Close</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal: UPDATE/EDIT USER DETAILS -->
                            <div class="modal-backdrop" id="modal-edit-<?= $u['role']; ?>-<?= $u['id']; ?>">
                                <div class="modal-card">
                                    <div class="modal-header">
                                        <div class="modal-title">Edit User - <?= sanitize($u['name']); ?></div>
                                        <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
                                    </div>
                                    <form action="index.php?admin_action=update_user_details" method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="role" value="<?= strtolower($u['role']); ?>">
                                            <input type="hidden" name="user_id" value="<?= $u['id']; ?>">

                                            <div class="form-group">
                                                <label class="form-label" for="edit-name-<?= $u['id']; ?>">Name / Company Name</label>
                                                <input type="text" id="edit-name-<?= $u['id']; ?>" name="name" class="form-control" value="<?= sanitize($u['name']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="edit-email-<?= $u['id']; ?>">Email Address</label>
                                                <input type="email" id="edit-email-<?= $u['id']; ?>" name="email" class="form-control" value="<?= sanitize($u['email']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="edit-phone-<?= $u['id']; ?>">Phone Number</label>
                                                <input type="text" id="edit-phone-<?= $u['id']; ?>" name="phone" class="form-control" value="<?= sanitize($u['phone']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="edit-dist-<?= $u['id']; ?>">District</label>
                                                <input type="text" id="edit-dist-<?= $u['id']; ?>" name="district" class="form-control" value="<?= sanitize($u['district'] ?? 'Colombo'); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="edit-addr-<?= $u['id']; ?>">Address / Farm Location</label>
                                                <textarea id="edit-addr-<?= $u['id']; ?>" name="address" class="form-control" rows="2" required><?= sanitize($u['detail'] ?? ''); ?></textarea>
                                            </div>

                                            <?php if (!empty($u['nic_number'])): ?>
                                                <div class="form-group">
                                                    <label class="form-label" for="edit-nic-<?= $u['id']; ?>">NIC Number</label>
                                                    <input type="text" id="edit-nic-<?= $u['id']; ?>" name="nic_number" class="form-control" value="<?= sanitize($u['nic_number']); ?>">
                                                </div>
                                            <?php endif; ?>

                                            <?php if (isset($u['contact_person']) && $u['contact_person']): ?>
                                                <div class="form-group">
                                                    <label class="form-label" for="edit-contact-<?= $u['id']; ?>">Contact Person</label>
                                                    <input type="text" id="edit-contact-<?= $u['id']; ?>" name="contact_person" class="form-control" value="<?= sanitize($u['contact_person'] ?? $u['name']); ?>">
                                                </div>
                                            <?php endif; ?>

                                        <div class="form-group">
                                            <label class="form-label" for="edit-status-<?= $u['id']; ?>">Account Status</label>
                                            <select id="edit-status-<?= $u['id']; ?>" name="status" class="form-control" required>
                                                <option value="active" <?= (in_array($u['status'], ['active','approved']))?'selected':''; ?>>Active / Approved</option>
                                                <option value="pending" <?= ($u['status']==='pending')?'selected':''; ?>>Pending Verification</option>
                                                <option value="suspended" <?= ($u['status']==='suspended')?'selected':''; ?>>Suspended</option>
                                                <option value="rejected" <?= ($u['status']==='rejected')?'selected':''; ?>>Rejected</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline btn-sm" data-modal-close>Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Save User Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal: DELETE USER CONFIRMATION -->
                        <div class="modal-backdrop" id="modal-delete-<?= $u['role']; ?>-<?= $u['id']; ?>">
                            <div class="modal-card">
                                <div class="modal-header">
                                    <div class="modal-title" style="color: var(--color-error);">Confirm Delete User</div>
                                    <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
                                </div>
                                <form action="index.php?admin_action=delete_user" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="role" value="<?= strtolower($u['role']); ?>">
                                        <input type="hidden" name="user_id" value="<?= $u['id']; ?>">
                                        <p style="font-size: 14px; color: var(--color-on-surface);">
                                            Are you sure you want to permanently delete user account <strong><?= sanitize($u['name']); ?></strong> (<?= sanitize($u['email']); ?>)?
                                        </p>
                                        <p style="font-size: 12px; color: var(--color-error); margin-top: 8px;">
                                            ⚠️ This action cannot be undone. All database records associated with this user will be removed.
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline btn-sm" data-modal-close>Cancel</button>
                                        <button type="submit" class="btn btn-danger btn-sm">Permanently Delete User</button>
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

<!-- Modal: CREATE NEW USER -->
<div class="modal-backdrop" id="modal-create-user">
    <div class="modal-card" style="max-width: 540px;">
        <div class="modal-header">
            <div class="modal-title">Create New User Account</div>
            <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
        </div>
        <form action="index.php?admin_action=create_user" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="c-role">User Role</label>
                    <select id="c-role" name="role" class="form-control" required onchange="toggleRoleFields(this.value)">
                        <option value="buyer">Buyer</option>
                        <option value="farmer">Farmer</option>
                        <option value="courier">Courier Partner (Company)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="c-name">Full Name / Company Name</label>
                    <input type="text" id="c-name" name="name" class="form-control" placeholder="e.g. Kasun Perera / Lanka Express" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="c-email">Email Address</label>
                    <input type="email" id="c-email" name="email" class="form-control" placeholder="name@domain.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="c-pass">Initial Password</label>
                    <input type="password" id="c-pass" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="c-phone">Phone Number</label>
                    <input type="text" id="c-phone" name="phone" class="form-control" placeholder="+94 77 123 4567" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="c-dist">District</label>
                    <select id="c-dist" name="district" class="form-control" required>
                        <option value="Colombo">Colombo</option>
                        <option value="Gampaha">Gampaha</option>
                        <option value="Kandy">Kandy</option>
                        <option value="Nuwara Eliya">Nuwara Eliya</option>
                        <option value="Matale">Matale</option>
                        <option value="Jaffna">Jaffna</option>
                        <option value="Galle">Galle</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="c-addr">Address / Farm Location</label>
                    <textarea id="c-addr" name="address" class="form-control" rows="2" placeholder="Full street address..." required></textarea>
                </div>

                <div id="role-extra-farmer" style="display:none;">
                    <div class="form-group">
                        <label class="form-label" for="c-nic">NIC Number (Farmer)</label>
                        <input type="text" id="c-nic" name="nic_number" class="form-control" placeholder="199012345678">
                    </div>
                </div>

                <div id="role-extra-courier" style="display:none;">
                    <div class="form-group">
                        <label class="form-label" for="c-contact-p">Contact Person</label>
                        <input type="text" id="c-contact-p" name="contact_person" class="form-control" placeholder="Contact person name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="c-status">Account Activation Status</label>
                    <select id="c-status" name="status" class="form-control" required>
                        <option value="active">Active / Approved</option>
                        <option value="pending">Pending Verification</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-sm" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Create User Account</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleRoleFields(role) {
    const farmerBox = document.getElementById('role-extra-farmer');
    const courierBox = document.getElementById('role-extra-courier');
    if (role === 'farmer') {
        farmerBox.style.display = 'block';
        courierBox.style.display = 'none';
    } else if (role === 'courier') {
        farmerBox.style.display = 'none';
        courierBox.style.display = 'block';
    } else {
        farmerBox.style.display = 'none';
        courierBox.style.display = 'none';
    }
}
</script>
