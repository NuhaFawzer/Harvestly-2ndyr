<?php

require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/layout.php';

/*
|--------------------------------------------------------------------------
| Determine which farmer is being viewed
|--------------------------------------------------------------------------
| Farmer:
|   /Farmer/profile.php
|
| Buyer:
|   /Farmer/profile.php?farmer_id=5
|
*/

$view_farmer_id = isset($_GET['farmer_id'])
    ? (int)$_GET['farmer_id']
    : $farmer_id;


/*
|--------------------------------------------------------------------------
| Check whether this is the logged-in farmer's own profile
|--------------------------------------------------------------------------
*/

$is_own_profile = ($view_farmer_id === $farmer_id);

$show_contact = (
    !$is_own_profile &&
    isset($_GET['show_contact']) &&
    (string)$_GET['show_contact'] === '1'
);


/*
|--------------------------------------------------------------------------
| Update profile
|--------------------------------------------------------------------------
| Only the logged-in farmer can update their own profile.
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Never allow another user to update this profile
    if (!$is_own_profile) {
        flash('error', 'You are not allowed to edit this profile.');
        redirect('profile.php?farmer_id=' . $view_farmer_id);
    }

    $full = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $farm = trim($_POST['farm_name'] ?? '');

    $a1 = trim($_POST['pickup_address_line1'] ?? '');
    $a2 = trim($_POST['pickup_address_line2'] ?? '');
    $city = trim($_POST['pickup_city_town'] ?? '');
    $postal = trim($_POST['pickup_postal_code'] ?? '');

    $conn->begin_transaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | Update users table
        |--------------------------------------------------------------------------
        */

        $u = $conn->prepare(
            'UPDATE users
             SET full_name = ?, phone = ?
             WHERE user_id = ?'
        );

        $u->bind_param(
            'ssi',
            $full,
            $phone,
            $farmer_id
        );

        $u->execute();
        $u->close();


        /*
        |--------------------------------------------------------------------------
        | Update farmer_profiles table
        |--------------------------------------------------------------------------
        */

        $f = $conn->prepare(
            'UPDATE farmer_profiles
             SET
                farm_name = ?,
                pickup_address_line1 = ?,
                pickup_address_line2 = ?,
                pickup_city_town = ?,
                pickup_postal_code = ?
             WHERE farmer_id = ?'
        );

        $f->bind_param(
            'sssssi',
            $farm,
            $a1,
            $a2,
            $city,
            $postal,
            $farmer_id
        );

        $f->execute();
        $f->close();


        $conn->commit();

        flash('success', 'Profile updated.');

        redirect('profile.php');

    } catch (Throwable $e) {

        $conn->rollback();

        throw $e;
    }
}


/*
|--------------------------------------------------------------------------
| Get farmer profile
|--------------------------------------------------------------------------
*/

$st = $conn->prepare(
    'SELECT
        u.user_id,
        u.full_name,
        u.email,
        u.phone,
        u.role,

        fp.farmer_id,
        fp.farm_name,
        fp.pickup_address_line1,
        fp.pickup_address_line2,
        fp.pickup_city_town,
        fp.pickup_postal_code,
        fp.district_id,
        fp.verification_status,

        d.district_name

     FROM users u

     LEFT JOIN farmer_profiles fp
        ON fp.farmer_id = u.user_id

     LEFT JOIN districts d
        ON d.district_id = fp.district_id

     WHERE u.user_id = ?

     LIMIT 1'
);

$st->bind_param(
    'i',
    $view_farmer_id
);

$st->execute();

$p = $st->get_result()->fetch_assoc();

$st->close();


/*
|--------------------------------------------------------------------------
| Farmer not found
|--------------------------------------------------------------------------
*/

if (!$p) {

    echo '<div style="
        max-width:700px;
        margin:50px auto;
        padding:30px;
        background:#fff;
        border-radius:12px;
        text-align:center;
        box-shadow:0 2px 10px rgba(0,0,0,.08);
    ">';

    echo '<h2>Farmer Not Found</h2>';
    echo '<p>The requested farmer profile could not be found.</p>';

    echo '</div>';

    page_bottom();

    exit;
}


/*
|--------------------------------------------------------------------------
| Verification documents
|--------------------------------------------------------------------------
|
| Only show verification documents to the farmer themselves.
| Buyers should NOT see private verification documents.
|--------------------------------------------------------------------------
*/

$dr = null;

if ($is_own_profile) {

    $docs = $conn->prepare(
        'SELECT
            document_type,
            original_file_name,
            status

         FROM verification_documents

         WHERE user_id = ?

         ORDER BY created_at DESC'
    );

    $docs->bind_param(
        'i',
        $view_farmer_id
    );

    $docs->execute();

    $dr = $docs->get_result();
}


/*
|--------------------------------------------------------------------------
| Page title
|--------------------------------------------------------------------------
*/

if ($is_own_profile) {

    page_top(
        'Farmer Profile',
        'profile'
    );

} elseif ($show_contact) {

    page_top(
        'Farmer Contact',
        'profile'
    );

} else {

    page_top(
        'Farmer Store',
        'profile'
    );
}

?>

<div class="page-title">

    <div>

        <?php if ($is_own_profile): ?>

            <h1>Farmer Profile</h1>

            <p>
                Manage permitted farmer profile information.
            </p>

        <?php else: ?>

            <h1>
                <?= e($p['farm_name'] ?: $p['full_name']) ?>
            </h1>

            <p>
                <?= $show_contact ? 'Farmer contact information' : 'Farmer information' ?>
            </p>

        <?php endif; ?>

    </div>

</div>


<?php if (!$is_own_profile): ?>

    <!-- =========================================================
         BUYER VIEW
         ========================================================= -->

    <div class="card">

        <h2>Farmer Information</h2>

        <div class="form-grid">

            <div class="field">

                <label>Farmer Name</label>

                <input
                    class="input"
                    value="<?= e($p['full_name'] ?? '') ?>"
                    disabled
                >

            </div>


            <div class="field">

                <label>Farm Name</label>

                <input
                    class="input"
                    value="<?= e($p['farm_name'] ?? '') ?>"
                    disabled
                >

            </div>


            <div class="field">

                <label>District</label>

                <input
                    class="input"
                    value="<?= e($p['district_name'] ?? '') ?>"
                    disabled
                >

            </div>


            <div class="field">

                <label>City / Town</label>

                <input
                    class="input"
                    value="<?= e($p['pickup_city_town'] ?? '') ?>"
                    disabled
                >

            </div>


            <div class="field">

                <label>Verification</label>

                <input
                    class="input"
                    value="<?= e($p['verification_status'] ?? 'Verified') ?>"
                    disabled
                >

            </div>

        </div>

    </div>

    <?php if ($show_contact): ?>

        <!-- =========================================================
             BUYER CONTACT VIEW
             ========================================================= -->

        <div class="card">

            <h2>Farmer Contact</h2>

            <div class="form-grid">

                <div class="field">

                    <label>Farmer Name</label>

                    <input
                        class="input"
                        value="<?= e($p['full_name'] ?? '') ?>"
                        disabled
                    >

                </div>

                <div class="field">

                    <label>Farm Name</label>

                    <input
                        class="input"
                        value="<?= e($p['farm_name'] ?? '') ?>"
                        disabled
                    >

                </div>

                <div class="field">

                    <label>Email</label>

                    <input
                        class="input"
                        value="<?= e($p['email'] ?? '') ?>"
                        disabled
                    >

                </div>

                <div class="field">

                    <label>Phone</label>

                    <input
                        class="input"
                        value="<?= e($p['phone'] ?? '') ?>"
                        disabled
                    >

                </div>

                <div class="field">

                    <label>Pickup Address</label>

                    <input
                        class="input"
                        value="<?= e($p['pickup_address_line1'] ?? '') ?>"
                        disabled
                    >

                </div>

                <div class="field">

                    <label>City / Town</label>

                    <input
                        class="input"
                        value="<?= e($p['pickup_city_town'] ?? '') ?>"
                        disabled
                    >

                </div>

                <div class="field">

                    <label>District</label>

                    <input
                        class="input"
                        value="<?= e($p['district_name'] ?? '') ?>"
                        disabled
                    >

                </div>

                <div class="field">

                    <label>Postal Code</label>

                    <input
                        class="input"
                        value="<?= e($p['pickup_postal_code'] ?? '') ?>"
                        disabled
                    >

                </div>

            </div>

        </div>

    <?php endif; ?>


<?php else: ?>

    <!-- =========================================================
         FARMER OWN PROFILE
         ========================================================= -->

    <form
        class="card"
        method="post"
    >

        <div class="form-grid">


            <div class="field">

                <label>Full Name</label>

                <input
                    class="input"
                    name="full_name"
                    value="<?= e($p['full_name'] ?? '') ?>"
                >

            </div>


            <div class="field">

                <label>Email</label>

                <input
                    class="input"
                    value="<?= e($p['email'] ?? '') ?>"
                    disabled
                >

            </div>


            <div class="field">

                <label>Phone</label>

                <input
                    class="input"
                    name="phone"
                    value="<?= e($p['phone'] ?? '') ?>"
                >

            </div>


            <div class="field">

                <label>Farm Name</label>

                <input
                    class="input"
                    name="farm_name"
                    value="<?= e($p['farm_name'] ?? '') ?>"
                >

            </div>


            <div class="field">

                <label>Pickup Address</label>

                <input
                    class="input"
                    name="pickup_address_line1"
                    value="<?= e($p['pickup_address_line1'] ?? '') ?>"
                >

            </div>


            <div class="field">

                <label>Address Line 2</label>

                <input
                    class="input"
                    name="pickup_address_line2"
                    value="<?= e($p['pickup_address_line2'] ?? '') ?>"
                >

            </div>


            <div class="field">

                <label>City / Town</label>

                <input
                    class="input"
                    name="pickup_city_town"
                    value="<?= e($p['pickup_city_town'] ?? '') ?>"
                >

            </div>


            <div class="field">

                <label>Postal Code</label>

                <input
                    class="input"
                    name="pickup_postal_code"
                    value="<?= e($p['pickup_postal_code'] ?? '') ?>"
                >

            </div>


            <div class="field">

                <label>District</label>

                <input
                    class="input"
                    value="<?= e($p['district_name'] ?? '') ?>"
                    disabled
                >

            </div>


            <div class="field">

                <label>Verification</label>

                <input
                    class="input"
                    value="<?= e($p['verification_status'] ?? '') ?>"
                    disabled
                >

            </div>


        </div>


        <button
            type="submit"
            class="btn"
        >
            Save Profile
        </button>

    </form>


    <!-- =========================================================
         VERIFICATION DOCUMENTS
         ========================================================= -->

    <div class="card">

        <h2>Supporting Verification Documents</h2>

        <?php if ($dr): ?>

            <?php if ($dr->num_rows > 0): ?>

                <?php while ($d = $dr->fetch_assoc()): ?>

                    <p>

                        <strong>
                            <?= e($d['document_type']) ?>
                        </strong>

                        —

                        <?= e(
                            $d['original_file_name']
                            ?: 'Document'
                        ) ?>

                        <span class="badge">
                            <?= e($d['status']) ?>
                        </span>

                    </p>

                <?php endwhile; ?>

            <?php else: ?>

                <p>
                    No verification documents available.
                </p>

            <?php endif; ?>

        <?php endif; ?>

    </div>

<?php endif; ?>


<?php

page_bottom();

?>