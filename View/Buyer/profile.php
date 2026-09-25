<?php
$buyer = isset($buyer) ? $buyer : [];
$orderStats = isset($orderStats) ? $orderStats : ['total'=>0,'delivered'=>0,'pending'=>0,'cancelled'=>0];
$success = isset($success) ? $success : '';
$error = isset($error) ? $error : '';

$profileImage = trim((string)($buyer['profile_image'] ?? ''));
$profileImage = $profileImage !== ''
    ? $profileImage
    : url('assets/harvestly-logo.jpeg');

$buyerName = (string)($buyer['name'] ?? '');
$buyerEmail = (string)($buyer['email'] ?? '');
$buyerPhone = (string)($buyer['phone'] ?? '');
$buyerCity = (string)($buyer['city'] ?? '');
$buyerDistrict = (string)($buyer['district'] ?? '');
$buyerAddress = (string)($buyer['address'] ?? '');
$buyerJoined = (string)(
    $buyer['joined']
    ?? $buyer['created_at']
    ?? $buyer['registered_at']
    ?? ''
);

if ($buyerJoined !== '') {
    $timestamp = strtotime($buyerJoined);
    $buyerJoined = $timestamp !== false
        ? date('F Y', $timestamp)
        : $buyerJoined;
} else {
    $buyerJoined = 'Not available';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Harvestly - Buyer Profile</title>

    <link
        rel="stylesheet"
        href="<?= e(BASE_URL) ?>/css/Buyer/buyer-profile.css"
    >

        <script src="<?= e(BASE_URL) ?>/js/icon-fallback.js" defer></script>
</head>

<body>

<!-- ================= HEADER ================= -->

<nav class="top-navbar">

    <div class="navbar-inner">

        <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php" class="brand">

            <img src="<?= e(BASE_URL) ?>/assets/harvestly-logo.jpeg" alt="Harvestly" style="height:34px;width:auto;display:block;object-fit:contain;">

        </a>

        <div class="desktop-nav">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">Home</a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php">Products</a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php#categories">Categories</a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/LandingController.php#farmers">Farmers</a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/LandingController.php#about">About</a>

        </div>

        <div class="nav-actions">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/CartController.php" class="icon-btn">

                <span class="material-symbols-outlined">
                    shopping_cart
                </span>

                <span class="cart-count">3</span>

            </a>

            <button class="icon-btn">

                <span class="material-symbols-outlined">
                    notifications
                </span>

            </button>

            <div class="user-menu">

                <img
                    src="<?php echo htmlspecialchars($profileImage, ENT_QUOTES, "UTF-8"); ?>"
                    alt="Profile"
                    id="navProfileImage"
                >

                <span>
                    <?php echo htmlspecialchars($buyerName, ENT_QUOTES, "UTF-8"); ?>
                </span>

                <a
                    href="<?= e(BASE_URL) ?>/Controller/Buyer/LogoutController.php"
                    class="logout-link"
                    title="Logout"
                >
                    <span class="material-symbols-outlined">logout</span>
                </a>

            </div>

        </div>

    </div>

</nav>


<!-- ================= DASHBOARD ================= -->

<div class="dashboard-container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="sidebar-card">

            <ul>

                <li><a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"><span class="material-symbols-outlined">dashboard</span><span>Dashboard</span></a></li>
                <li><a href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php"><span class="material-symbols-outlined">storefront</span><span>Browse Products</span></a></li>
                <li><a href="<?= e(BASE_URL) ?>/Controller/Buyer/CartController.php"><span class="material-symbols-outlined">shopping_cart</span><span>Cart</span></a></li>
                <li><a href="<?= e(BASE_URL) ?>/Controller/Buyer/OrdersController.php" ><span class="material-symbols-outlined">receipt_long</span><span>My Orders</span></a></li>
                <li><a href="<?= e(BASE_URL) ?>/Controller/Buyer/FeedbackController.php"><span class="material-symbols-outlined">rate_review</span><span>Reviews</span></a></li>
                <li><a href="<?= e(BASE_URL) ?>/Controller/Buyer/FeedbackController.php"><span class="material-symbols-outlined">report_problem</span><span>Complaints</span></a></li>
                <li><a href="<?= e(BASE_URL) ?>/Controller/Buyer/NotificationsController.php"><span class="material-symbols-outlined">notifications</span><span>Notifications</span></a></li>
                <li><a href="<?= e(BASE_URL) ?>/Controller/Buyer/ProfileController.php" class="active"><span class="material-symbols-outlined">person</span><span>Profile</span></a></li>
                <li><a href="<?= e(BASE_URL) ?>/Controller/Buyer/LogoutController.php"><span class="material-symbols-outlined">logout</span><span>Logout</span></a></li>

            </ul>

        </div>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="main-content">

        <div class="page-heading">

            <h1>My Profile</h1>

            <p>
                Manage your Harvestly buyer account and delivery details.
            </p>

        </div>


        <?php if ($success): ?>

            <div class="success-message">

                <span class="material-symbols-outlined">
                    check_circle
                </span>

                <?php echo $success; ?>

            </div>

        <?php endif; ?>

        <?php if ($error): ?>

            <div class="success-message profile-error-message">

                <span class="material-symbols-outlined">
                    error
                </span>

                <?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?>

            </div>

        <?php endif; ?>


        <!-- PROFILE CARD -->

        <section class="profile-card" id="settings">

            <div class="profile-header">

                <div class="profile-image-wrapper">

                    <img
                        src="<?php echo htmlspecialchars($profileImage, ENT_QUOTES, "UTF-8"); ?>"
                        id="profilePreview"
                        alt="Buyer Profile"
                    >

                    <label
                        for="profileImage"
                        class="camera-button"
                    >

                        <span class="material-symbols-outlined">
                            photo_camera
                        </span>

                    </label>

                </div>

                <div class="profile-title">

                    <h2>
                        <?php echo htmlspecialchars($buyerName, ENT_QUOTES, "UTF-8"); ?>
                    </h2>

                    <p>
                        Buyer Account
                    </p>

                    <span class="member-badge">

                        <span class="material-symbols-outlined">
                            verified
                        </span>

                        Verified Buyer

                    </span>

                </div>

            </div>


            <!-- FORM -->

            <form
                method="POST"
                enctype="multipart/form-data"
                id="profileForm"
            >

                <input
                    type="file"
                    name="profile_image"
                    id="profileImage"
                    accept="image/png,image/jpeg,image/webp"
                    hidden
                >


                <div class="section-title">

                    <span class="material-symbols-outlined">
                        person
                    </span>

                    <div>

                        <h3>Personal Information</h3>

                        <p>
                            Update your basic account information.
                        </p>

                    </div>

                </div>


                <div class="form-grid">

                    <div class="form-group">

                        <label>Full Name</label>

                        <input
                            type="text"
                            name="name"
                            value="<?php echo htmlspecialchars($buyerName, ENT_QUOTES, "UTF-8"); ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Email Address</label>

                        <input
                            type="email"
                            name="email"
                            value="<?php echo htmlspecialchars($buyerEmail, ENT_QUOTES, "UTF-8"); ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Phone Number</label>

                        <input
                            type="text"
                            name="phone"
                            value="<?php echo htmlspecialchars($buyerPhone, ENT_QUOTES, "UTF-8"); ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label>City</label>

                        <select name="city">

                            <option
                                <?php echo $buyer["city"] === "Colombo" ? "selected" : ""; ?>
                            >
                                Colombo
                            </option>

                            <option
                                <?php echo $buyer["city"] === "Kandy" ? "selected" : ""; ?>
                            >
                                Kandy
                            </option>

                            <option
                                <?php echo $buyer["city"] === "Galle" ? "selected" : ""; ?>
                            >
                                Galle
                            </option>

                            <option
                                <?php echo $buyer["city"] === "Ratnapura" ? "selected" : ""; ?>
                            >
                                Ratnapura
                            </option>

                            <option
                                <?php echo $buyer["city"] === "Kurunegala" ? "selected" : ""; ?>
                            >
                                Kurunegala
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>District</label>

                        <select name="district" required>

                            <option value="Ampara" <?php echo $buyerDistrict === "Ampara" ? "selected" : ""; ?>>Ampara</option>
                            <option value="Anuradhapura" <?php echo $buyerDistrict === "Anuradhapura" ? "selected" : ""; ?>>Anuradhapura</option>
                            <option value="Badulla" <?php echo $buyerDistrict === "Badulla" ? "selected" : ""; ?>>Badulla</option>
                            <option value="Batticaloa" <?php echo $buyerDistrict === "Batticaloa" ? "selected" : ""; ?>>Batticaloa</option>
                            <option value="Colombo" <?php echo $buyerDistrict === "Colombo" ? "selected" : ""; ?>>Colombo</option>
                            <option value="Galle" <?php echo $buyerDistrict === "Galle" ? "selected" : ""; ?>>Galle</option>
                            <option value="Gampaha" <?php echo $buyerDistrict === "Gampaha" ? "selected" : ""; ?>>Gampaha</option>
                            <option value="Hambantota" <?php echo $buyerDistrict === "Hambantota" ? "selected" : ""; ?>>Hambantota</option>
                            <option value="Jaffna" <?php echo $buyerDistrict === "Jaffna" ? "selected" : ""; ?>>Jaffna</option>
                            <option value="Kalutara" <?php echo $buyerDistrict === "Kalutara" ? "selected" : ""; ?>>Kalutara</option>
                            <option value="Kandy" <?php echo $buyerDistrict === "Kandy" ? "selected" : ""; ?>>Kandy</option>
                            <option value="Kegalle" <?php echo $buyerDistrict === "Kegalle" ? "selected" : ""; ?>>Kegalle</option>
                            <option value="Kilinochchi" <?php echo $buyerDistrict === "Kilinochchi" ? "selected" : ""; ?>>Kilinochchi</option>
                            <option value="Kurunegala" <?php echo $buyerDistrict === "Kurunegala" ? "selected" : ""; ?>>Kurunegala</option>
                            <option value="Mannar" <?php echo $buyerDistrict === "Mannar" ? "selected" : ""; ?>>Mannar</option>
                            <option value="Matale" <?php echo $buyerDistrict === "Matale" ? "selected" : ""; ?>>Matale</option>
                            <option value="Matara" <?php echo $buyerDistrict === "Matara" ? "selected" : ""; ?>>Matara</option>
                            <option value="Monaragala" <?php echo $buyerDistrict === "Monaragala" ? "selected" : ""; ?>>Monaragala</option>
                            <option value="Mullaitivu" <?php echo $buyerDistrict === "Mullaitivu" ? "selected" : ""; ?>>Mullaitivu</option>
                            <option value="Nuwara Eliya" <?php echo $buyerDistrict === "Nuwara Eliya" ? "selected" : ""; ?>>Nuwara Eliya</option>
                            <option value="Polonnaruwa" <?php echo $buyerDistrict === "Polonnaruwa" ? "selected" : ""; ?>>Polonnaruwa</option>
                            <option value="Puttalam" <?php echo $buyerDistrict === "Puttalam" ? "selected" : ""; ?>>Puttalam</option>
                            <option value="Ratnapura" <?php echo $buyerDistrict === "Ratnapura" ? "selected" : ""; ?>>Ratnapura</option>
                            <option value="Trincomalee" <?php echo $buyerDistrict === "Trincomalee" ? "selected" : ""; ?>>Trincomalee</option>
                            <option value="Vavuniya" <?php echo $buyerDistrict === "Vavuniya" ? "selected" : ""; ?>>Vavuniya</option>

                        </select>

                    </div>


                    <div class="form-group full-width">

                        <label>Address</label>

                        <textarea
                            name="address"
                            rows="3"
                            required
                        ><?php echo htmlspecialchars($buyerAddress, ENT_QUOTES, "UTF-8"); ?></textarea>

                    </div>

                </div>


                <button
                    type="button"
                    class="delete-account-btn"
                    onclick="deleteBuyerAccount()"
                >
                    <span class="material-symbols-outlined">delete</span>
                    Delete Account
                </button>

                <div class="form-actions">

                    <button
                        type="button"
                        class="cancel-btn"
                        onclick="resetProfile()"
                    >

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="save-btn"
                    >

                        <span class="material-symbols-outlined">
                            save
                        </span>

                        Save Changes

                    </button>

                </div>

            </form>

            <form
                method="POST"
                id="deleteAccountForm"
                action="<?= e(BASE_URL) ?>/Controller/Buyer/ProfileController.php"
                hidden
            >
                <input type="hidden" name="action" value="delete_account">
            </form>

        </section>


        <!-- ORDER SUMMARY -->

        <section class="stats-section">

            <div class="section-title">

                <span class="material-symbols-outlined">
                    shopping_bag
                </span>

                <div>

                    <h3>Order History</h3>

                    <p>
                        Overview of your Harvestly purchases.
                    </p>

                </div>

            </div>


            <div class="stats-grid">

                <div class="stat-card">

                    <div class="stat-icon">

                        <span class="material-symbols-outlined">
                            shopping_bag
                        </span>

                    </div>

                    <div>

                        <span>Total Orders</span>

                        <strong>
                            <?php echo $orderStats["total"]; ?>
                        </strong>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">

                        <span class="material-symbols-outlined">
                            check_circle
                        </span>

                    </div>

                    <div>

                        <span>Delivered</span>

                        <strong>
                            <?php echo $orderStats["delivered"]; ?>
                        </strong>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">

                        <span class="material-symbols-outlined">
                            local_shipping
                        </span>

                    </div>

                    <div>

                        <span>Pending</span>

                        <strong>
                            <?php echo $orderStats["pending"]; ?>
                        </strong>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">

                        <span class="material-symbols-outlined">
                            cancel
                        </span>

                    </div>

                    <div>

                        <span>Cancelled</span>

                        <strong>
                            <?php echo $orderStats["cancelled"]; ?>
                        </strong>

                    </div>

                </div>

            </div>

        </section>


        <!-- ACCOUNT INFO -->

        <section class="account-card">

            <div>

                <span class="material-symbols-outlined">
                    calendar_month
                </span>

                <div>

                    <strong>Member Since</strong>

                    <p>
                        <?php echo htmlspecialchars($buyerJoined, ENT_QUOTES, "UTF-8"); ?>
                    </p>

                </div>

            </div>


            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/OrdersController.php">

                View Order History

                <span class="material-symbols-outlined">
                    arrow_forward
                </span>

            </a>

        </section>

    </main>

</div>


<footer class="footer">

    <div>

        <strong>Harvestly</strong>

        <p>
            © 2026 Harvestly. Bridging Sri Lankan Fields to Your Table.
        </p>

    </div>

</footer>


<script src="<?= e(BASE_URL) ?>/js/Buyer/buyer-profile.js"></script>

</body>

</html>