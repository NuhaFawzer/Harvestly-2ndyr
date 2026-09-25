<?php

$product = isset($product) ? $product : [];

$images = isset($images) ? $images : [];

$farmerImage = isset($farmerImage) ? $farmerImage : "";


if (empty($product)) {

    header(
        "Location: " . url('Controller/Buyer/ProductController.php')
    );

    exit;
}


if (empty($images)) {

    $images = [
        $product["image"] ?? ""
    ];
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
        <?php echo htmlspecialchars($product["name"]); ?> - Harvestly
    </title>


    <link
        rel="stylesheet"
        href="<?= e(BASE_URL) ?>/css/Buyer/product-details.css"
    >


        <script src="<?= e(BASE_URL) ?>/js/icon-fallback.js" defer></script>
</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="navbar">

    <div class="navbar-inner">


        <a
            href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"
            class="logo"
        >
            <img src="<?= e(BASE_URL) ?>/assets/harvestly-logo.jpeg" alt="Harvestly" style="height:32px;width:auto;display:block;object-fit:contain;">
        </a>


        <div class="nav-links">

            <a
                href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"
            >
                Home
            </a>


            <a
                href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php"
                class="active"
            >
                Products
            </a>


        </div>


        <div class="nav-actions">

            <button
                type="button"
                class="mobile-menu"
                id="mobileMenu"
            >

                <span class="material-symbols-outlined">
                    menu
                </span>

            </button>

        </div>

    </div>


    <div
        class="mobile-nav"
        id="mobileNav"
    >

        <a
            href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"
        >
            Home
        </a>


        <a
            href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php"
        >
            Products
        </a>


    </div>

</nav>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="main-container">


    <!-- BREADCRUMB -->

    <nav class="breadcrumb">

        <a
            href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"
        >
            Home
        </a>

        <span class="material-symbols-outlined">
            chevron_right
        </span>

        <a
            href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php"
        >
            Products
        </a>

        <span class="material-symbols-outlined">
            chevron_right
        </span>

        <span>
            Vegetables
        </span>

        <span class="material-symbols-outlined">
            chevron_right
        </span>

        <strong>
            <?php echo htmlspecialchars($product["name"]); ?>
        </strong>

    </nav>


    <!-- PRODUCT HERO -->

    <section class="product-hero">


        <!-- =================================================
             GALLERY
        ================================================== -->

        <div class="gallery">


            <div class="main-image">

                <img
                    id="mainProductImage"
                    src="<?php echo htmlspecialchars($images[0]); ?>"
                    alt="<?php echo htmlspecialchars($product["name"]); ?>"
                >


                <div class="badges">

                    <span class="fresh-badge">

                        <span class="material-symbols-outlined">
                            local_fire_department
                        </span>

                        Fresh Today

                    </span>


                    <span class="organic-badge">

                        <span class="material-symbols-outlined">
                            eco
                        </span>

                        Organic

                    </span>

                </div>

            </div>


            <!-- THUMBNAILS -->

            <div class="thumbnails">

                <?php foreach ($images as $index => $image): ?>

                    <button
                        type="button"
                        class="thumbnail <?php echo $index === 0 ? "selected" : ""; ?>"
                        data-image="<?php echo htmlspecialchars($image); ?>"
                    >

                        <img
                            src="<?php echo htmlspecialchars($image); ?>"
                            alt="Product image <?php echo $index + 1; ?>"
                        >

                    </button>

                <?php endforeach; ?>

            </div>

        </div>


        <!-- =================================================
             PRODUCT INFO
        ================================================== -->

        <div class="product-info">


            <!-- TITLE -->

            <div class="title-row">

                <h1>
                    <?php
                    echo htmlspecialchars(
                        $product["name"]
                    );
                    ?>
                </h1>


                <button
                    type="button"
                    class="favorite-btn"
                    id="favoriteBtn"
                >

                    <span class="material-symbols-outlined">
                        favorite_border
                    </span>

                </button>

            </div>


            <!-- RATING -->

            <div class="rating-row">

                <div class="stars">

                    <span class="material-symbols-outlined filled">
                        star
                    </span>

                    <span class="material-symbols-outlined filled">
                        star
                    </span>

                    <span class="material-symbols-outlined filled">
                        star
                    </span>

                    <span class="material-symbols-outlined filled">
                        star
                    </span>

                    <span class="material-symbols-outlined">
                        star_half
                    </span>

                </div>


                <strong>
                    <?php echo htmlspecialchars($product["rating"]); ?>
                </strong>


                <span>
                    (<?php echo htmlspecialchars($product["reviews"]); ?> Reviews)
                </span>

            </div>


            <!-- PRICE -->

            <div class="price-row">

                <span class="price">
                    LKR <?php echo htmlspecialchars($product["price"]); ?>
                </span>


                <span class="unit">
                    / <?php echo htmlspecialchars($product["unit"]); ?>
                </span>

            </div>


            <!-- STOCK -->

            <div class="stock-card">


                <div class="stock-item">

                    <span class="material-symbols-outlined">
                        inventory_2
                    </span>


                    <div>

                        <small>
                            Available Stock
                        </small>


                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $product["stock"]
                            );
                            ?>
                            kg
                        </strong>

                    </div>

                </div>


                <div class="stock-item">

                    <span class="material-symbols-outlined">
                        calendar_month
                    </span>


                    <div>

                        <small>
                            Harvest Date
                        </small>


                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $product["harvest_date"]
                            );
                            ?>
                        </strong>

                    </div>

                </div>

            </div>


            <!-- PRODUCT DETAILS -->

            <div class="product-meta-card">

                <div class="product-meta-grid">

                    <div class="product-meta-item">
                        <small>Category</small>
                        <strong><?php echo htmlspecialchars($product["category"] ?? "Not specified"); ?></strong>
                    </div>

                    <div class="product-meta-item">
                        <small>Listing Type</small>
                        <strong><?php echo htmlspecialchars($product["listing_type"] ?? "Not specified"); ?></strong>
                    </div>

                    <div class="product-meta-item">
                        <small>Growing Method</small>
                        <strong><?php echo htmlspecialchars($product["growing_method"] ?? ($product["organic"] ? "Organic" : "Conventional")); ?></strong>
                    </div>

                    <div class="product-meta-item">
                        <small>Quality Grade</small>
                        <strong><?php
                            $qualityGrade = trim((string)($product["quality_grade"] ?? ""));
                            if ($qualityGrade === "" || strcasecmp($qualityGrade, "Farmer Declared") === 0) {
                                echo "Farmer Declared";
                            } else {
                                echo "Farmer Declared: " . htmlspecialchars($qualityGrade);
                            }
                        ?></strong>
                    </div>

                    <div class="product-meta-item">
                        <small>Freshness</small>
                        <strong><?php echo !empty($product["fresh"]) ? "Fresh Today" : "Standard Freshness"; ?></strong>
                    </div>

                    <div class="product-meta-item">
                        <small>Shelf Life</small>
                        <strong><?php echo htmlspecialchars($product["shelf_life"] ?? "Not specified"); ?></strong>
                    </div>
                    <div class="product-meta-item">
                        <small>Best Before</small>
                        <strong><?php echo htmlspecialchars($product["best_before"] ?? "Not specified"); ?></strong>
                    </div>

                </div>

            </div>


            <!-- DESCRIPTION -->

            <p class="description">

                <?php
                echo htmlspecialchars(
                    $product["description"]
                );
                ?>

            </p>


            <!-- =================================================
                 DELIVERY DESTINATION
            ================================================== -->

            <div class="destination-card">

                <div class="destination-field">
                    <label for="destinationDistrict">Destination District</label>
                    <select id="destinationDistrict" name="destination_district">
                        <option value="">Select District</option>
                        <?php
                        $districts = [
                            "Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo",
                            "Galle", "Gampaha", "Hambantota", "Jaffna", "Kalutara",
                            "Kandy", "Kegalle", "Kilinochchi", "Kurunegala", "Mannar",
                            "Matale", "Matara", "Monaragala", "Mullaitivu", "Nuwara Eliya",
                            "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"
                        ];
                        foreach ($districts as $district):
                        ?>
                            <option value="<?php echo htmlspecialchars($district); ?>"><?php echo htmlspecialchars($district); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="estimated-fee">
                    <small>Estimated Delivery Fee</small>
                    <strong id="estimatedDeliveryFee">Calculated at checkout</strong>
                </div>
            </div>


            <!-- =================================================
                 ACTIONS
            ================================================== -->

            <div class="actions">


                <!-- QUANTITY -->

                <div class="quantity">


                    <button
                        type="button"
                        id="decreaseBtn"
                    >

                        <span class="material-symbols-outlined">
                            remove
                        </span>

                    </button>


                    <input
                        type="number"
                        id="quantity"
                        min="1"
                        max="<?php
                            echo htmlspecialchars(
                                $product["stock"]
                            );
                        ?>"
                        value="1"
                    >


                    <button
                        type="button"
                        id="increaseBtn"
                    >

                        <span class="material-symbols-outlined">
                            add
                        </span>

                    </button>

                </div>


                <!-- ADD TO CART -->

                <a
                    id="addCartBtn"
                    class="add-cart-btn"
                    href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php?action=add_to_cart&id=<?php echo urlencode($product["id"]); ?>&qty=1&source=details"
                >

                    <span class="material-symbols-outlined">
                        shopping_cart
                    </span>

                    Add to Cart

                </a>


                <!-- BUY NOW -->

                <a
                    id="buyNowBtn"
                    class="buy-now-btn"
                    href="<?= e(BASE_URL) ?>/Controller/Buyer/ProductController.php?action=add_to_cart&id=<?php echo urlencode($product["id"]); ?>&qty=1&source=details"
                >

                    <span class="material-symbols-outlined">
                        bolt
                    </span>

                    Buy Now

                </a>

            </div>


            <!-- DELIVERY -->

            <div class="delivery-card">

                <div class="delivery-icon">

                    <span class="material-symbols-outlined">
                        local_shipping
                    </span>

                </div>


                <div>

                    <h4>
                        Delivery Information
                    </h4>


                    <p>
                        <?php
                        echo htmlspecialchars(
                            $product["delivery"]
                        );
                        ?>
                    </p>

                </div>

            </div>


            <!-- FARMER -->

            <div class="farmer-card">

                <div class="farmer-top">


                    <?php if ($farmerImage !== ""): ?>

                        <img
                            src="<?php echo htmlspecialchars($farmerImage); ?>"
                            alt="Farmer"
                        >

                    <?php endif; ?>


                    <div class="farmer-details">

                        <h3>
                            <?php
                            echo htmlspecialchars(
                                $product["farmer"]
                            );
                            ?>
                        </h3>


                        <p>

                            <span class="material-symbols-outlined">
                                location_on
                            </span>


                            <?php
                            echo htmlspecialchars(
                                $product["farm"]
                            );
                            ?>

                        </p>

                    </div>


                    <div class="farmer-rating">

                        <div>

                            <span class="material-symbols-outlined">
                                star
                            </span>

                            <?php
                            echo htmlspecialchars(
                                $product["farmer_rating"]
                            );
                            ?>

                        </div>


                        <small>

                            <?php
                            echo htmlspecialchars(
                                $product["experience"]
                            );
                            ?>

                        </small>

                    </div>

                </div>


                <div class="farmer-buttons">

                    <a
                        href="<?php echo htmlspecialchars(buyerRoute('FarmerStoreController.php', 'farmer=' . rawurlencode($product['farmer']))); ?>"
                        class="view-store"
                    >
                        View Store
                    </a>


                    <a
                        href="<?php echo htmlspecialchars(buyerRoute('FarmerContactController.php', 'farmer=' . rawurlencode($product['farmer']))); ?>"
                        class="contact-btn"
                    >

                        <span class="material-symbols-outlined">
                            chat
                        </span>

                        Contact

                    </a>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         INFORMATION TABS
    ====================================================== -->

    <section class="information-section">


        <div class="tabs">

            <button
                type="button"
                class="tab active"
                data-tab="nutrition"
            >
                Nutritional Information
            </button>


            <button
                type="button"
                class="tab"
                data-tab="description"
            >
                Description
            </button>


            <button
                type="button"
                class="tab"
                data-tab="storage"
            >
                Storage Instructions
            </button>

        </div>


        <!-- NUTRITION -->

        <div
            class="tab-content active"
            id="nutrition"
        >

            <div class="nutrition-grid">


                <div class="nutrition-box vitamin-a">

                    <span class="material-symbols-outlined">
                        visibility
                    </span>

                    <strong>
                        Vitamin A
                    </strong>

                    <span>
                        High (Beta-Carotene)
                    </span>

                </div>


                <div class="nutrition-box vitamin-c">

                    <span class="material-symbols-outlined">
                        healing
                    </span>

                    <strong>
                        Vitamin C
                    </strong>

                    <span>
                        Immune Support
                    </span>

                </div>


                <div class="nutrition-box fiber">

                    <span class="material-symbols-outlined">
                        grass
                    </span>

                    <strong>
                        Fiber
                    </strong>

                    <span>
                        Digestion Health
                    </span>

                </div>


                <div class="nutrition-box calories">

                    <span class="material-symbols-outlined">
                        monitor_weight
                    </span>

                    <strong>
                        Calories
                    </strong>

                    <span>
                        41 kcal / 100g
                    </span>

                </div>

            </div>


            <div class="organic-info">

                <h3>
                    Certified Organic Growing
                </h3>


                <p>
                    These carrots are cultivated using traditional
                    methods integrated with modern sustainable practices.
                    No synthetic fertilizers or pesticides are utilized,
                    ensuring a product that is safe for you and the environment.
                </p>


                <ul>

                    <li>

                        <span class="material-symbols-outlined">
                            check_circle
                        </span>

                        100% Pesticide Free

                    </li>


                    <li>

                        <span class="material-symbols-outlined">
                            check_circle
                        </span>

                        Natural Compost Fertilizer

                    </li>


                    <li>

                        <span class="material-symbols-outlined">
                            check_circle
                        </span>

                        Non-GMO Heirloom Seeds

                    </li>

                </ul>

            </div>

        </div>


        <!-- DESCRIPTION -->

        <div
            class="tab-content"
            id="description"
        >

            <div class="simple-tab-card">

                <h3>
                    Product Description
                </h3>


                <p>

                    <?php
                    echo htmlspecialchars(
                        $product["description"]
                    );
                    ?>

                </p>

            </div>

        </div>


        <!-- STORAGE -->

        <div
            class="tab-content"
            id="storage"
        >

            <div class="simple-tab-card">

                <h3>
                    Storage Instructions
                </h3>


                <p>
                    Store vegetables in a cool refrigerator.
                    Remove excess moisture before storage and
                    keep them in a clean vegetable container.
                </p>

            </div>

        </div>

    </section>

</main>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="footer">

    <div class="footer-inner">


        <div class="footer-brand">

            <span>
                Harvestly
            </span>


            <p>
                © 2026 Harvestly.
                Bridging Sri Lankan Fields to Your Table.
            </p>

        </div>


        <div class="footer-column">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                About Harvestly
            </a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Quick Links
            </a>

        </div>


        <div class="footer-column">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Contact Us
            </a>

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Privacy Policy
            </a>

        </div>


        <div class="footer-column">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Terms of Service
            </a>

        </div>

    </div>

</footer>


<script
    src="<?= e(BASE_URL) ?>/js/Buyer/product-details.js"
></script>

</body>

</html>