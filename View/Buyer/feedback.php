<?php
$orderId = $orderId ?? 'ORD-2026-001';
$farmerName = $farmerName ?? "Sunil's Organic Farm";
$reviewMessage = $reviewMessage ?? '';
$complaintMessage = $complaintMessage ?? '';
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Harvestly - Order Feedback</title>

    <link rel="stylesheet"
          href="<?= e(BASE_URL) ?>/css/Buyer/feedback.css">

    <script src="<?= e(BASE_URL) ?>/js/icon-fallback.js" defer></script>
</head>

<body>


<!-- =========================
     NAVBAR
========================= -->

<header class="navbar">

    <div class="navbar-container">

        <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php" class="logo">

            <img src="<?= e(BASE_URL) ?>/assets/harvestly-logo.jpeg" alt="Harvestly" style="height:34px;width:auto;display:block;object-fit:contain;">

        </a>


        <nav class="desktop-nav">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">Home</a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">Products</a>

        </nav>


        <div class="nav-actions">

            <button
                type="button"
                class="menu-btn"
                id="menuBtn">

                <span class="material-symbols-outlined">
                    menu
                </span>

            </button>

        </div>

    </div>


    <nav
        class="mobile-nav"
        id="mobileNav">

        <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">Home</a>

        <a href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php">Products</a>

    </nav>

</header>



<!-- =========================
     MAIN
========================= -->

<main class="main-container">


    <section class="page-heading">

        <h1>
            Order Feedback
        </h1>

        <p>
            Help us maintain quality by sharing your experience for Order <?= htmlspecialchars($orderId) ?>.
        </p>

    </section>



    <div class="content-grid">


        <!-- =========================
             LEFT - REVIEW
        ========================= -->

        <section class="review-card">

            <h2>
                Rate Your Harvest
            </h2>


            <?php if ($reviewMessage): ?>

                <div class="message success-message">

                    <?= htmlspecialchars($reviewMessage) ?>

                </div>

            <?php endif; ?>


            <form method="POST"
                  action="<?= e(BASE_URL) ?>/Controller/Buyer/FeedbackController.php"
                  id="reviewForm">

                <div class="form-group review-order-picker">
                    <label for="reviewOrder">Completed Order</label>
                    <select name="order_id" id="reviewOrder" required>
                        <option value="" selected disabled>Select a completed order</option>
                        <?php foreach (($reviewableOrders ?? []) as $reviewOrder): ?>
                            <option value="<?= htmlspecialchars($reviewOrder['order_number']) ?>">
                                <?= htmlspecialchars($reviewOrder['order_number']) ?> — Delivered <?= htmlspecialchars(date('M d, Y', strtotime($reviewOrder['delivered_at']))) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($reviewableOrders)): ?>
                        <small class="rule-note">Only completed orders delivered within the last 14 days can be reviewed.</small>
                    <?php else: ?>
                        <small class="rule-note">Reviews are available for 14 days after delivery.</small>
                    <?php endif; ?>
                </div>

                <!-- Farmer Rating -->

                <div class="rating-section">

                    <label>
                        Farmer Rating
                        (<?= htmlspecialchars($farmerName) ?>)
                    </label>


                    <div
                        class="stars"
                        data-rating="farmer">

                        <button
                            type="button"
                            class="star"
                            data-value="1">

                            ★

                        </button>

                        <button
                            type="button"
                            class="star"
                            data-value="2">

                            ★

                        </button>

                        <button
                            type="button"
                            class="star"
                            data-value="3">

                            ★

                        </button>

                        <button
                            type="button"
                            class="star"
                            data-value="4">

                            ★

                        </button>

                        <button
                            type="button"
                            class="star"
                            data-value="5">

                            ★

                        </button>

                    </div>


                    <input
                        type="hidden"
                        name="farmer_rating"
                        id="farmerRating"
                        value="0">


                    <textarea
                        name="quality_comment"
                        placeholder="How was the quality of the produce?"
                        rows="4"></textarea>

                </div>



                <!-- Delivery Rating -->

                <div class="rating-section">

                    <label>
                        Delivery Experience
                    </label>


                    <div
                        class="stars"
                        data-rating="delivery">

                        <button
                            type="button"
                            class="star"
                            data-value="1">

                            ★

                        </button>

                        <button
                            type="button"
                            class="star"
                            data-value="2">

                            ★

                        </button>

                        <button
                            type="button"
                            class="star"
                            data-value="3">

                            ★

                        </button>

                        <button
                            type="button"
                            class="star"
                            data-value="4">

                            ★

                        </button>

                        <button
                            type="button"
                            class="star"
                            data-value="5">

                            ★

                        </button>

                    </div>


                    <input
                        type="hidden"
                        name="delivery_rating"
                        id="deliveryRating"
                        value="0">


                    <textarea
                        name="delivery_comment"
                        placeholder="Any comments on the delivery speed or packaging?"
                        rows="3"></textarea>

                </div>



                <div class="review-submit">

                    <button
                        type="submit"
                        name="submit_review"
                        class="primary-btn">

                        Submit Review

                    </button>

                </div>

            </form>

        </section>



        <!-- =========================
             RIGHT - COMPLAINT
        ========================= -->

        <section class="complaint-card">


            <div class="complaint-heading">

                <span class="material-symbols-outlined">
                    report_problem
                </span>

                <h2>
                    Submit a Complaint
                </h2>

            </div>


            <p class="complaint-description">

                Please describe the issue in one clear sentence so our team can review your complaint quickly.

            </p>


            <?php if ($complaintMessage): ?>

                <div class="message complaint-message">

                    <?= htmlspecialchars($complaintMessage) ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                action="<?= e(BASE_URL) ?>/Controller/Buyer/FeedbackController.php"
                enctype="multipart/form-data"
                id="complaintForm">


                <div class="form-group complaint-order-picker">
                    <label for="complaintOrder">Order</label>
                    <select name="order_id" id="complaintOrder" required>
                        <option value="" selected disabled>Select a recently delivered order</option>
                        <?php foreach (($complaintOrders ?? []) as $complaintOrder): ?>
                            <option value="<?= htmlspecialchars($complaintOrder['order_number']) ?>">
                                <?= htmlspecialchars($complaintOrder['order_number']) ?> — Delivered <?= htmlspecialchars(date('M d, Y H:i', strtotime($complaintOrder['delivered_at']))) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="rule-note">Submit an order-related complaint with details and optional evidence for Admin review.</small>
                </div>

                <!-- Category -->

                <div class="form-group">

                    <label for="category">
                        Category
                    </label>

                    <select
                        name="category"
                        id="category">

                        <option
                            value=""
                            selected
                            disabled>

                            Select an issue...

                        </option>

                        <option value="Product Quality">Product Quality</option>
                        <option value="Damaged Product">Damaged Product</option>
                        <option value="Wrong Product">Wrong Product</option>
                        <option value="Incorrect Quantity">Incorrect Quantity</option>

                        <option value="Delivery Issue">Delivery Issue</option>

                        <option value="Other">Other</option>

                    </select>

                </div>



                <!-- Details -->

                <div class="form-group">

                    <label for="details">
                        Description
                    </label>

                    <textarea
                        name="details"
                        id="details"
                        rows="5"
                        placeholder="Please describe the issue clearly in one sentence..."></textarea>

                </div>



                <!-- Upload -->

                <div class="form-group">

                    <label>
                        Attach Photos (Optional)
                    </label>


                    <label
                        class="upload-box"
                        for="photos">

                        <span class="material-symbols-outlined">
                            add_a_photo
                        </span>

                        <span>
                            Drag and drop or click to upload
                        </span>

                        <small id="fileName">
                            No file selected
                        </small>

                    </label>


                    <input
                        type="file"
                        name="photos[]"
                        id="photos"
                        accept="image/*"
                        multiple>

                </div>



                <button
                    type="submit"
                    name="submit_complaint"
                    class="complaint-btn">

                    File Complaint

                </button>


            </form>

        </section>

    </div>

    <section class="history-grid">
        <div class="history-card">
            <div class="history-heading"><h2>My Reviews</h2><span><?= count($reviews ?? []) ?></span></div>
            <?php if (empty($reviews)): ?>
                <p class="empty-history">No reviews submitted yet.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <article class="history-item">
                        <div><strong><?= htmlspecialchars($review['order_number'] ?? 'Order') ?></strong><span class="status-pill"><?= htmlspecialchars($review['status'] ?? 'Pending') ?></span></div>
                        <div class="rating-line">Farmer: <?= str_repeat('★', (int)$review['farmer_rating']) ?> &nbsp; Delivery: <?= str_repeat('★', (int)$review['delivery_rating']) ?></div>
                        <?php if (!empty($review['quality_comment'])): ?><p><?= htmlspecialchars($review['quality_comment']) ?></p><?php endif; ?>
                        <?php if (!empty($review['delivery_comment'])): ?><p><?= htmlspecialchars($review['delivery_comment']) ?></p><?php endif; ?>
                        <small><?= htmlspecialchars(date('M d, Y', strtotime($review['created_at']))) ?></small>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="history-card">
            <div class="history-heading"><h2>My Complaints</h2><span><?= count($complaints ?? []) ?></span></div>
            <?php if (empty($complaints)): ?>
                <p class="empty-history">No complaints submitted yet.</p>
            <?php else: ?>
                <?php foreach ($complaints as $complaint): ?>
                    <?php
                        $complaintStatus = strtoupper((string)($complaint['status'] ?? 'OPEN'));
                        $isEditable = $complaintStatus === 'OPEN'
                            && !empty($complaint['created_at'])
                            && strtotime($complaint['created_at']) >= strtotime('-24 hours');
                        $complaintId = (int)($complaint['id'] ?? 0);
                        $complaintCategory = (string)($complaint['category'] ?? 'Other');
                        $complaintDetails = (string)($complaint['details'] ?? '');
                    ?>
                    <article class="history-item complaint-history-item">
                        <div class="complaint-history-top">
                            <strong>CMP-<?= str_pad((string)$complaintId, 4, '0', STR_PAD_LEFT) ?></strong>
                            <span class="status-pill"><?= htmlspecialchars($complaintStatus) ?></span>
                        </div>

                        <p><strong><?= htmlspecialchars($complaintCategory) ?></strong></p>
                        <p><?= htmlspecialchars($complaintDetails) ?></p>
                        <small><?= htmlspecialchars(date('M d, Y H:i', strtotime($complaint['created_at']))) ?></small>

                        <?php if ($isEditable): ?>
                            <div class="complaint-crud-actions">
                                <form method="POST" class="complaint-edit-form">
                                    <input type="hidden" name="complaint_id" value="<?= $complaintId ?>">

                                    <label>
                                        Category
                                        <select name="category" required>
                                            <?php foreach (['Product Quality', 'Damaged Product', 'Wrong Product', 'Incorrect Quantity', 'Delivery Issue', 'Other'] as $option): ?>
                                                <option value="<?= htmlspecialchars($option) ?>" <?= $complaintCategory === $option ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($option) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>

                                    <label>
                                        Description
                                        <textarea name="details" rows="3" maxlength="1000" required><?= htmlspecialchars($complaintDetails) ?></textarea>
                                    </label>

                                    <div class="complaint-action-row">
                                        <button type="submit" name="update_complaint" class="complaint-edit-btn">Update</button>
                                    </div>
                                </form>

                                <form method="POST" class="complaint-delete-form" onsubmit="return confirm('Delete this complaint?');">
                                    <input type="hidden" name="complaint_id" value="<?= $complaintId ?>">
                                    <button type="submit" name="delete_complaint" class="complaint-delete-btn">Delete</button>
                                </form>
                            </div>
                            <small class="crud-note">Open complaints can be updated or deleted within 24 hours.</small>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

</main>



<!-- =========================
     FOOTER
========================= -->

<footer class="footer">

    <div class="footer-container">


        <div class="footer-brand">

            <h2>
                Harvestly
            </h2>

            <p>
                © 2026 Harvestly.
                Bridging Sri Lankan Fields to Your Table.
            </p>

        </div>


        <div class="footer-links">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                About Harvestly
            </a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Quick Links
            </a>

        </div>


        <div class="footer-links">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Contact Us
            </a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Privacy Policy
            </a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Terms of Service
            </a>

        </div>

    </div>

</footer>


<script src="<?= e(BASE_URL) ?>/js/Buyer/feedback.js"></script>

</body>

</html>