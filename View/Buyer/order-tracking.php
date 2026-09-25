<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($pageTitle ?? 'Order Tracking') ?>
        - Harvestly
    </title>

    <link
        rel="stylesheet"
        href="<?= $baseUrl ?>/css/Buyer/order-tracking.css"
    >

            <link rel="stylesheet" href="<?= e(BASE_URL) ?>/css/Buyer/buyer-header-polish.css">

<script src="<?= e(BASE_URL) ?>/js/icon-fallback.js" defer></script>
</head>

<body data-base-url="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>" data-order-id="<?= htmlspecialchars($tracking['id'] ?? '', ENT_QUOTES) ?>">

<?php if (empty($tracking)): ?>

    <header class="tracking-header">

        <a
            href="<?= $baseUrl ?>/Controller/Buyer/OrdersController.php"
            class="back-button"
        >

            <span class="back-icon">
                ←
            </span>

            <span>
                Back to Orders
            </span>

        </a>


        <a
            href="<?= $baseUrl ?>/Controller/Buyer/DashboardController.php"
            class="logo"
        >

            <img src="<?= e(BASE_URL) ?>/assets/harvestly-logo-horizontal.png" alt="Harvestly" style="height:34px;width:auto;display:block;object-fit:contain;">

        </a>

        <?php include __DIR__ . '/header-icons.php'; ?>

    </header>


    <main class="tracking-container">

        <div class="tracking-card">

            <h1>
                Order Not Found
            </h1>

            <p>
                We could not find the requested order.
            </p>

            <br>

            <a
                href="<?= $baseUrl ?>/Controller/Buyer/OrdersController.php"
                class="complaint-button"
            >
                Back to My Orders
            </a>

        </div>

    </main>


<?php else: ?>


<header class="tracking-header">

    <a
        href="<?= $baseUrl ?>/Controller/Buyer/OrdersController.php"
        class="back-button"
    >

        <span class="back-icon">
            ←
        </span>

        <span>
            Back to Orders
        </span>

    </a>


    <a
        href="<?= $baseUrl ?>/Controller/Buyer/DashboardController.php"
        class="logo"
    >

        <img src="<?= e(BASE_URL) ?>/assets/harvestly-logo-horizontal.png" alt="Harvestly" style="height:34px;width:auto;display:block;object-fit:contain;">

    </a>


    <?php include __DIR__ . '/header-icons.php'; ?>

</header>


<div class="tracking-shell">

    <aside class="buyer-sidebar">
        <div class="sidebar-card">
            <ul>
                <li><a href="<?= $baseUrl ?>/Controller/Buyer/DashboardController.php"><span class="material-symbols-outlined nav-icon">dashboard</span><span>Dashboard</span></a></li>
                <li><a href="<?= $baseUrl ?>/Controller/Buyer/ProductController.php"><span class="material-symbols-outlined nav-icon">storefront</span><span>Browse Products</span></a></li>
                <li><a href="<?= $baseUrl ?>/Controller/Buyer/CartController.php"><span class="material-symbols-outlined nav-icon">shopping_cart</span><span>Cart</span></a></li>
                <li><a href="<?= $baseUrl ?>/Controller/Buyer/OrdersController.php" class="active"><span class="material-symbols-outlined nav-icon">receipt_long</span><span>My Orders</span></a></li>
                <li><a href="<?= $baseUrl ?>/Controller/Buyer/FeedbackController.php"><span class="material-symbols-outlined nav-icon">rate_review</span><span>Reviews</span></a></li>
                <li><a href="<?= $baseUrl ?>/Controller/Buyer/FeedbackController.php"><span class="material-symbols-outlined nav-icon">report_problem</span><span>Complaints</span></a></li>
                <li><a href="<?= $baseUrl ?>/Controller/Buyer/NotificationsController.php"><span class="material-symbols-outlined nav-icon">notifications</span><span>Notifications</span></a></li>
                <li><a href="<?= $baseUrl ?>/Controller/Buyer/ProfileController.php"><span class="material-symbols-outlined nav-icon">person</span><span>Profile</span></a></li>
                <li><a href="<?= $baseUrl ?>/Controller/Buyer/LogoutController.php"><span class="material-symbols-outlined nav-icon">logout</span><span>Logout</span></a></li>
            </ul>
        </div>
    </aside>

    <main class="tracking-container">


    <section class="page-header">

        <div>

            <span class="tracking-label">
                ORDER TRACKING
            </span>

            <h1>

                Order #<?= htmlspecialchars($tracking['id']) ?>

            </h1>

            <p>

                Estimated Delivery:

                <?= htmlspecialchars($tracking['delivery']) ?>

            </p>

        </div>


        <span
            class="header-status <?= htmlspecialchars(
                strtolower(
                    str_replace(
                        ' ',
                        '-',
                        $tracking['status']
                    )
                )
            ) ?>"
        >

            <?= htmlspecialchars($tracking['status']) ?>

        </span>

    </section>


    <div class="tracking-layout">


        <!-- LEFT -->

        <section class="tracking-main">

            <div class="tracking-card">

                <h2>
                    Tracking Details
                </h2>


                <div class="timeline">

                    <?php foreach (
                        $tracking['steps']
                        as $step
                    ): ?>

                        <div
                            class="timeline-step <?= htmlspecialchars($step['state']) ?>"
                        >

                            <div class="timeline-line"></div>


                            <div class="step-circle">

                                <?= htmlspecialchars($step['icon']) ?>

                            </div>


                            <div class="step-content">

                                <h3>

                                    <?= htmlspecialchars($step['title']) ?>

                                </h3>

                                <p>

                                    <?= htmlspecialchars($step['time']) ?>

                                </p>


                                <?php if (
                                    $step['state'] === 'active'
                                ): ?>

                                    <span class="active-message">

                                        <?php if (
                                            strtolower($tracking['status'])
                                            === 'delivered'
                                        ): ?>

                                            Your order has been delivered successfully.

                                        <?php else: ?>

                                            Your package is currently being processed
                                            and moved through the delivery network.

                                        <?php endif; ?>

                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>


        <!-- RIGHT -->

        <aside class="tracking-sidebar">


            <!-- ORDER SUMMARY -->

            <div class="summary-card">

                <h3>
                    Order Summary
                </h3>


                <div class="summary-items">

                    <?php foreach (
                        $tracking['items']
                        as $item
                    ): ?>

                        <div class="summary-item">

                            <img
                                src="<?= htmlspecialchars($item['image']) ?>"
                                alt="<?= htmlspecialchars($item['name']) ?>"
                            >

                            <div>

                                <strong>

                                    <?= htmlspecialchars($item['name']) ?>

                                </strong>

                                <span>

                                    <?= htmlspecialchars($item['qty']) ?>

                                </span>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>


                <div class="summary-total">

                    <span>
                        Total
                    </span>

                    <strong>

                        LKR
                        <?= number_format(
                            (float)$tracking['total']
                        ) ?>

                    </strong>

                </div>

            </div>


            <!-- HELP -->

            <div class="help-card">

                <h3>
                    Need Help?
                </h3>


                <button
                    id="confirmReceived"
                    class="confirm-button"
                    <?= $tracking['can_confirm']
                        ? ''
                        : 'disabled'
                    ?>
                >

                    <span>
                        ✓
                    </span>

                    Confirm Received

                </button>


                <p class="confirm-note">

                    <?= $tracking['can_confirm']
                        ? 'Your order has been delivered.'
                        : 'Available once delivered.'
                    ?>

                </p>

                <?php if ($tracking['can_confirm']): ?>
                    <a
                        href="<?= $baseUrl ?>/Controller/Buyer/FeedbackController.php?order_id=<?= urlencode($tracking['id']) ?>"
                        class="feedback-button"
                    >
                        <span>★</span>
                        Leave Feedback
                    </a>
                <?php endif; ?>


                <button
                    type="button"
                    class="complaint-button"
                    onclick="openComplaint()"
                >

                    <span>
                        ⚠
                    </span>

                    Submit a Complaint

                </button>

            </div>


        </aside>

    </div>

</main>

</div>


<!-- COMPLAINT MODAL -->

<div
    id="complaintModal"
    class="modal"
>

    <div class="modal-content">

        <button
            type="button"
            class="close-modal"
            onclick="closeComplaint()"
        >
            ×
        </button>


        <h2>
            Submit a Complaint
        </h2>

        <p>
            Tell us what went wrong with your order.
        </p>


        <form id="complaintForm">
            <input type="hidden" name="order_id" value="<?= htmlspecialchars($tracking['id'] ?? '', ENT_QUOTES) ?>">

            <label>
                Complaint Category
            </label>

            <select name="category" required>

                <option value="">
                    Select category
                </option>

                <option>
                    Damaged Product
                </option>

                <option>
                    Missing Product
                </option>

                <option>
                    Late Delivery
                </option>

                <option>
                    Wrong Product
                </option>

                <option>
                    Product Quality
                </option>

                <option>
                    Other
                </option>

            </select>


            <label>
                Description
            </label>

            <textarea
                name="details"
                rows="4"
                placeholder="Describe your issue..."
                required
            ></textarea>


            <label>
                Upload Image
            </label>

            <input
                type="file"
                name="evidence"
                accept="image/*"
            >


            <button
                type="submit"
                class="submit-complaint"
            >
                Submit Complaint
            </button>

        </form>

    </div>

</div>


<?php endif; ?>


<script src="<?= $baseUrl ?>/js/Buyer/order-tracking.js"></script>

</body>

</html>