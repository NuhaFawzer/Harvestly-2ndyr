<?php

$cartItems = $cartItems ?? [];

$deliveryFee = $deliveryFee ?? 0;

$subtotal = $subtotal ?? 0;

$total = $total ?? 0;

$totalQuantity = $totalQuantity ?? 0;

$farmerGroups = [];
foreach ($cartItems as $cartItem) {
    $seller = trim((string)($cartItem['seller'] ?? 'Unknown Farmer'));
    if (!isset($farmerGroups[$seller])) {
        $farmerGroups[$seller] = [];
    }
    $farmerGroups[$seller][] = $cartItem;
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

    <title>
        Harvestly - Your Cart
    </title>


    <!-- Fonts -->

    <!-- Material Symbols -->

    <!-- CSS -->

    <link
        rel="stylesheet"
        href="<?= e(BASE_URL) ?>/css/Buyer/cart.css"
    >

    <script src="<?= e(BASE_URL) ?>/js/icon-fallback.js" defer></script>
</head>


<body>


<!-- =====================================================
     HEADER
===================================================== -->

<header class="cart-header">

    <div class="header-inner">


        <!-- LOGO -->

        <a
            href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"
            class="brand"
        >

            <img src="<?= e(BASE_URL) ?>/assets/harvestly-logo.jpeg" alt="Harvestly" class="harvestly-brand-logo">

        </a>


        <!-- CONTINUE SHOPPING -->

        <a
            href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php"
            class="continue-shopping"
        >

            <span class="material-symbols-outlined">
                arrow_back
            </span>

            Continue Shopping

        </a>

        <div class="cart-header-actions">
            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/NotificationsController.php" class="cart-header-icon" title="Notifications" aria-label="Notifications">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M18 8C18 4.686 15.314 2 12 2C8.686 2 6 4.686 6 8C6 13 4 15 3 16H21C20 15 18 13 18 8Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 20C10.5 21 11.2 21.5 12 21.5C12.8 21.5 13.5 21 14 20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </a>
            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/CartController.php" class="cart-header-icon active" title="Shopping Cart" aria-label="Shopping Cart">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M3 4H5L7.2 14.5C7.4 15.4 8.2 16 9.1 16H17.5C18.3 16 19 15.5 19.3 14.8L21 9H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="9.5" cy="20" r="1.5" stroke="currentColor" stroke-width="1.8"/>
                    <circle cx="17" cy="20" r="1.5" stroke="currentColor" stroke-width="1.8"/>
                </svg>
            </a>
        </div>

    </div>

</header>



<!-- =====================================================
     MAIN
===================================================== -->

<main class="cart-main">


    <!-- PAGE HEADER -->

    <section class="page-heading">

        <h1>
            Your Cart
        </h1>

        <p>
            Review your items before proceeding to checkout.
        </p>

    </section>



    <!-- =================================================
         CART LAYOUT
    ================================================== -->

    <div class="cart-layout">


        <!-- =================================================
             CART ITEMS
        ================================================== -->

        <section class="cart-items-section">

            <div
                class="cart-items"
                id="cartItems"
            >


                <?php if (count($cartItems) > 0): ?>


                    <?php foreach ($farmerGroups as $farmerName => $farmerItems): ?>

                        <section class="farmer-cart-group" data-farmer="<?php echo htmlspecialchars($farmerName); ?>">

                            <div class="farmer-cart-group-header">
                                <div>
                                    <span class="farmer-group-label">Farmer</span>
                                    <h2><?php echo htmlspecialchars($farmerName); ?></h2>
                                </div>
                                <span class="farmer-group-count"><?php echo count($farmerItems); ?> product<?php echo count($farmerItems) === 1 ? '' : 's'; ?></span>
                            </div>

                            <div class="farmer-cart-items">

                    <?php foreach ($farmerItems as $item): ?>


                        <article
                            class="cart-item"
                            data-item-id="<?php echo htmlspecialchars($item["id"]); ?>"
                            data-price="<?php echo htmlspecialchars($item["price"]); ?>"
                        >


                            <!-- IMAGE -->

                            <img
                                src="<?php echo htmlspecialchars($item["image"]); ?>"
                                alt="<?php echo htmlspecialchars($item["name"]); ?>"
                                class="cart-product-image"
                            >


                            <!-- CONTENT -->

                            <div class="cart-item-content">


                                <div class="cart-item-header">


                                    <div>

                                        <h2>

                                            <?php
                                            echo htmlspecialchars(
                                                $item["name"]
                                            );
                                            ?>

                                        </h2>


                                        <p class="seller">

                                            Sold by:

                                            <span>

                                                <?php
                                                echo htmlspecialchars(
                                                    $item["seller"]
                                                );
                                                ?>

                                            </span>

                                        </p>

                                    </div>


                                    <!-- REMOVE -->

                                    <button
                                        type="button"
                                        class="remove-item"
                                        data-remove="<?php echo htmlspecialchars($item["id"]); ?>"
                                        aria-label="Remove item"
                                        title="Remove item"
                                    >

                                        <span class="material-symbols-outlined">
                                            delete
                                        </span>

                                    </button>


                                </div>



                                <!-- ITEM BOTTOM -->

                                <div class="cart-item-bottom">


                                    <!-- QUANTITY -->

                                    <div class="quantity-control">


                                        <!-- MINUS -->

                                        <button
                                            type="button"
                                            class="quantity-btn decrease"
                                            data-id="<?php echo htmlspecialchars($item["id"]); ?>"
                                            aria-label="Decrease quantity"
                                        >

                                            <span class="material-symbols-outlined">
                                                remove
                                            </span>

                                        </button>


                                        <!-- CURRENT QUANTITY -->

                                        <span class="quantity">

                                            <?php
                                            echo htmlspecialchars(
                                                $item["quantity"]
                                            );
                                            ?>

                                        </span>


                                        <!-- PLUS -->

                                        <button
                                            type="button"
                                            class="quantity-btn increase"
                                            data-id="<?php echo htmlspecialchars($item["id"]); ?>"
                                            aria-label="Increase quantity"
                                        >

                                            <span class="material-symbols-outlined">
                                                add
                                            </span>

                                        </button>


                                    </div>



                                    <!-- PRICE -->

                                    <div class="item-price">


                                        <?php if (!empty($item["old_price"])): ?>

                                            <span class="old-price">

                                                LKR
                                                <?php
                                                echo number_format(
                                                    $item["old_price"],
                                                    2
                                                );
                                                ?>

                                            </span>

                                        <?php endif; ?>


                                        <strong class="current-price">

                                            LKR

                                            <span class="item-total-price">

                                                <?php
                                                echo number_format(
                                                    $item["quantity"] *
                                                    $item["price"],
                                                    2
                                                );
                                                ?>

                                            </span>


                                            <small>
                                                /
                                                <?php
                                                echo htmlspecialchars(
                                                    $item["unit"]
                                                );
                                                ?>
                                            </small>

                                        </strong>

                                    </div>


                                </div>

                            </div>

                        </article>


                    <?php endforeach; ?>

                            </div>

                        </section>

                <?php endforeach; ?>


                <?php endif; ?>



                <!-- EMPTY CART -->

                <div
                    class="empty-cart"
                    id="emptyCart"

                    <?php
                    echo count($cartItems) > 0
                        ? 'style="display:none;"'
                        : '';
                    ?>
                >

                    <div class="empty-cart-icon">

                        <span class="material-symbols-outlined">
                            shopping_cart
                        </span>

                    </div>


                    <h2>
                        Your cart is empty
                    </h2>


                    <p>
                        Add some fresh products from local farmers.
                    </p>


                    <a
                        href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php"
                    >
                        Start Shopping
                    </a>

                </div>

            </div>

        </section>



        <!-- =================================================
             ORDER SUMMARY
        ================================================== -->

        <aside class="order-summary">

            <div class="summary-card">


                <h2>
                    Order Summary
                </h2>


                <div class="summary-divider"></div>



                <!-- SUBTOTAL -->

                <div class="summary-row">

                    <span>

                        Subtotal

                        (
                        <span id="itemCount">

                            <?php
                            echo $totalQuantity;
                            ?>

                        </span>

                        items)

                    </span>


                    <strong id="subtotal">

                        LKR

                        <?php
                        echo number_format(
                            $subtotal,
                            2
                        );
                        ?>

                    </strong>

                </div>



                <!-- DELIVERY -->

                <div class="summary-row">

                    <span>
                        Delivery Fee
                    </span>


                    <strong id="deliveryFee">

                        LKR

                        <?php
                        echo number_format(
                            $deliveryFee,
                            2
                        );
                        ?>

                    </strong>

                </div>



                <!-- TOTAL -->

                <div class="summary-total">

                    <span>
                        Total
                    </span>


                    <strong id="grandTotal">

                        LKR

                        <?php
                        echo number_format(
                            $total,
                            2
                        );
                        ?>

                    </strong>

                </div>


                <p class="tax-note">
                    Includes all applicable taxes
                </p>



                <!-- CHECKOUT -->

                <button
                    type="button"
                    class="checkout-button"
                    id="checkoutButton"

                    <?php
                    echo count($cartItems) === 0
                        ? "disabled"
                        : "";
                    ?>
                >

                    Proceed to Checkout

                    <span class="material-symbols-outlined">
                        arrow_forward
                    </span>

                </button>



                <!-- SECURE -->

                <div class="secure-checkout">

                    <span class="material-symbols-outlined">
                        verified_user
                    </span>

                    Secure Checkout

                </div>


            </div>

        </aside>


    </div>

</main>



<!-- JS -->

<script
    src="<?= e(BASE_URL) ?>/js/Buyer/cart.js"
></script>


</body>

</html>