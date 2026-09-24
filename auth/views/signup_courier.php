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
            <h1 style="font-size: 24px; font-weight: 800; color: var(--color-on-surface); margin-top: 8px;">Courier Partner Fleet Registration</h1>
            <p style="font-size: 14px; color: var(--color-on-surface-variant);">Register your logistics company for Administrator approval</p>
        </div>

        <div style="background-color: var(--color-surface-container-low); padding: 12px 16px; border-radius: var(--radius-md); border-left: 4px solid var(--color-primary); font-size: 12px; color: var(--color-on-surface-variant); margin-bottom: 20px;">
            ℹ️ <strong>Company Logistics Policy:</strong> Courier Partner organizations or companies can register on Harvestly. Individual drivers are not supported.
        </div>

        <?php if ($error): ?>
            <div style="background-color: var(--color-error-container); color: var(--color-error); padding: 12px 16px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                ⚠️ <?= $error; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=signup_courier" method="POST" enctype="multipart/form-data" onsubmit="return validateCourierForm();">
            <?= csrfField(); ?>
            <div class="form-group">
                <label class="form-label" for="c-comp">Company / Organisation Name *</label>
                <input type="text" id="c-comp" name="company_name" class="form-control" placeholder="e.g. Lanka Agro Logistics Pvt Ltd" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="c-contact">Contact Person Name *</label>
                    <input type="text" id="c-contact" name="contact_person" class="form-control" placeholder="Nimal Silva" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="c-phone">Contact Number *</label>
                    <input type="text" id="c-phone" name="phone" class="form-control" placeholder="+94 11 234 5678" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="c-email">Corporate Email Address *</label>
                <input type="email" id="c-email" name="email" class="form-control" placeholder="logistics@company.lk" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="c-pass">Password *</label>
                    <input type="password" id="c-pass" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="c-confirm-pass">Confirm Password *</label>
                    <input type="password" id="c-confirm-pass" name="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="c-district">Primary Fleet Operations District (25 Sri Lanka Districts) *</label>
                <select id="c-district" name="district" class="form-control" required>
                    <option value="">Select District...</option>
                    <?php foreach ($districts as $dist): ?>
                        <option value="<?= $dist; ?>" <?= ($dist === 'Colombo') ? 'selected' : ''; ?>><?= $dist; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="c-address">Organisation Business Address *</label>
                <textarea id="c-address" name="business_address" class="form-control" rows="2" placeholder="Full registered company address..." required></textarea>
            </div>

            <div class="form-group" style="background: var(--color-surface-container-low); padding: 16px; border-radius: var(--radius-md); border: 1px dashed var(--color-outline-variant);">
                <label class="form-label" for="c-cert">Upload Business / Identity Verification Document (PDF, JPG, PNG)</label>
                <input type="file" id="c-cert" name="verification_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <span style="font-size: 11px; color: var(--color-outline); margin-top: 4px; display: block;">Supporting verification document. Registration submitted successfully. Your account is awaiting Administrator approval.</span>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 16px; padding: 12px;">
                Submit Company Fleet Application
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--color-on-surface-variant);">
            Already registered? <a href="index.php?page=login" style="color: var(--color-primary); font-weight: 700;">Login here</a>
        </div>
    </div>
</div>

<script>
function validateCourierForm() {
    var pass = document.getElementById('c-pass').value;
    var confirmPass = document.getElementById('c-confirm-pass').value;
    if (pass !== confirmPass) {
        alert('Passwords do not match. Please verify your password entry.');
        return false;
    }
    return true;
}
</script>
