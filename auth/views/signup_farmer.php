<?php
$error = isset($_GET['error']) ? sanitize($_GET['error']) : null;
$districts = [
    'Ampara', 'Anuradhapura', 'Badulla', 'Batticaloa', 'Colombo', 'Galle', 'Gampaha',
    'Hambantota', 'Jaffna', 'Kalutara', 'Kandy', 'Kegalle', 'Kilinochchi', 'Kurunegala',
    'Mannar', 'Matale', 'Matara', 'Monaragala', 'Mullaitivu', 'Nuwara Eliya', 'Polonnaruwa',
    'Puttalam', 'Ratnapura', 'Trincomalee', 'Vavuniya'
];
?>
<div class="page-content" style="max-width: 580px; margin: 50px auto; padding: 0 20px;">
    <div style="background-color: var(--color-surface); border: 1px solid var(--color-outline-variant); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-md);">
        <div style="margin-bottom: 24px;">
            <a href="index.php?page=role_select" style="font-size: 13px; color: var(--color-outline); font-weight: 600; text-decoration: none;">&larr; Change Role</a>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--color-on-surface); margin-top: 8px;">Farmer Registration</h1>
            <p style="font-size: 14px; color: var(--color-on-surface-variant);">Register your farm with NIC identity verification for Administrator approval</p>
        </div>

        <?php if ($error): ?>
            <div style="background-color: var(--color-error-container); color: var(--color-error); padding: 12px 16px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                ⚠️ <?= $error; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=signup_farmer" method="POST" enctype="multipart/form-data" onsubmit="return validateFarmerForm();">
            <?= csrfField(); ?>
            <div class="form-group">
                <label class="form-label" for="f-name">Full Name / Farmer Name *</label>
                <input type="text" id="f-name" name="full_name" class="form-control" placeholder="e.g. Sunil Perera" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="f-email">Email Address *</label>
                <input type="email" id="f-email" name="email" class="form-control" placeholder="sunil@farm.lk" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="f-pass">Password *</label>
                    <input type="password" id="f-pass" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="f-confirm-pass">Confirm Password *</label>
                    <input type="password" id="f-confirm-pass" name="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="f-phone">Phone Number *</label>
                    <input type="text" id="f-phone" name="phone" class="form-control" placeholder="+94 77 123 4567" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="f-nic">National Identity Card (NIC) *</label>
                    <input type="text" id="f-nic" name="nic_number" class="form-control" placeholder="198214500123" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="f-district">Operating / Pickup District (25 Sri Lanka Districts) *</label>
                <select id="f-district" name="district" class="form-control" required>
                    <option value="">Select District...</option>
                    <?php foreach ($districts as $dist): ?>
                        <option value="<?= $dist; ?>" <?= ($dist === 'Nuwara Eliya') ? 'selected' : ''; ?>><?= $dist; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="f-address">Farm Location Address *</label>
                <textarea id="f-address" name="farm_address" class="form-control" rows="2" placeholder="Full farm physical address..." required></textarea>
            </div>

            <div class="form-group" style="background: var(--color-surface-container-low); padding: 16px; border-radius: var(--radius-md); border: 1px dashed var(--color-outline-variant);">
                <label class="form-label" for="f-doc">Upload NIC Identity Verification Document (JPG, PNG, PDF) *</label>
                <input type="file" id="f-doc" name="id_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                <span style="font-size: 11px; color: var(--color-outline); margin-top: 4px; display: block;">Max file size: 5MB. Registration submitted successfully. Your account is awaiting Administrator approval.</span>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 16px; padding: 12px;">
                Submit Application for Admin Review
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--color-on-surface-variant);">
            Already registered? <a href="index.php?page=login" style="color: var(--color-primary); font-weight: 700;">Login here</a>
        </div>
    </div>
</div>

<script>
function validateFarmerForm() {
    var pass = document.getElementById('f-pass').value;
    var confirmPass = document.getElementById('f-confirm-pass').value;
    if (pass !== confirmPass) {
        alert('Passwords do not match. Please verify your password entry.');
        return false;
    }
    return true;
}
</script>
