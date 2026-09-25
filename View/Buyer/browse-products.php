<?php

$baseUrl = $baseUrl ?? url('');

$products = $products ?? [];

$search = $search ?? "";

$district =
    $district ?? "All Districts";

$maxPrice =
    $maxPrice ?? 2000;

$organic =
    $organic ?? false;

$fresh =
    $fresh ?? false;

$stock =
    $stock ?? false;

$sort =
    $sort ?? "Newest";

$listingType = $listingType ?? "All Listing Types";
$growingMethod = $growingMethod ?? "All Growing Methods";

$added =
    $added ?? false;

$filterDistricts = $filterDistricts ?? [];
$listingOptions = $listingOptions ?? ["All Listing Types", "Available Now", "Harvest Soon", "Seasonal"];
$growingOptions = $growingOptions ?? ["All Growing Methods", "Organic", "Conventional", "Mixed"];

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
        Harvestly - Browse Products
    </title>


    <link
        rel="stylesheet"
        href="<?= e($baseUrl) ?>/css/Buyer/browse-products.css"
    >


            <link rel="stylesheet" href="<?= e($baseUrl) ?>/css/Buyer/buyer-header-polish.css">

<script src="<?= e($baseUrl) ?>/js/icon-fallback.js" defer></script>
</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="navbar">

    <div class="navbar-inner">


        <a
            href="<?= e(buyerRoute('DashboardController.php')) ?>"
            class="logo"
        >
            <img src="<?= e($baseUrl) ?>/assets/harvestly-logo-horizontal.png" alt="Harvestly" style="height:32px;width:auto;display:block;object-fit:contain;">
        </a>


        <div class="desktop-nav">

            <a
                href="<?= e(buyerRoute('DashboardController.php')) ?>"
            >
                Home
            </a>


            <a
                href="<?= e(buyerRoute('ProductController.php')) ?>"
                class="active"
            >
                Products
            </a>


        </div>


        <div class="nav-actions">

            <?php include __DIR__ . '/header-icons.php'; ?>

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
            href="<?= e(buyerRoute('DashboardController.php')) ?>"
        >
            Home
        </a>


        <a
            href="<?= e(buyerRoute('ProductController.php')) ?>"
        >
            Products
        </a>


    </div>

</nav>



<!-- =====================================================
     MAIN
===================================================== -->

<main class="main-container">


    <!-- HEADER -->

    <header class="page-header">

        <h1>
            Browse Fresh Vegetables
        </h1>


        <p>
            Explore fresh vegetables directly from trusted
            Sri Lankan farmers.
        </p>

    </header>


    <!-- ADDED MESSAGE -->

    <?php if ($added): ?>

        <div
            style="
                margin-bottom:20px;
                padding:14px 18px;
                border-radius:8px;
                background:#e8f5e9;
                color:#14532d;
                font-weight:600;
            "
        >

            Product added to cart successfully.


            <a
                href="<?= e(buyerRoute('CartController.php')) ?>"
                style="
                    margin-left:10px;
                    text-decoration:underline;
                "
            >
                View Cart
            </a>

        </div>

    <?php endif; ?>



    <div class="browse-layout">


        <!-- =================================================
             FILTER SIDEBAR
        ================================================== -->

        <aside class="filters-sidebar">


            <form
                method="GET"
                action="<?= e(buyerRoute('ProductController.php')) ?>"
                class="filter-card"
            >


                <h2>
                    Filters
                </h2>

                <p class="filter-description">Refine products by district, price, listing type, growing method, freshness, and stock.</p>


                <!-- SEARCH -->

                <div class="filter-group">

                    <label for="search">
                        Search
                    </label>


                    <div class="filter-search">

                        <span class="material-symbols-outlined">
                            search
                        </span>


                        <input
                            type="text"
                            id="search"
                            name="search"
                            placeholder="Product or Farmer..."
                            value="<?php
                                echo htmlspecialchars(
                                    $search
                                );
                            ?>"
                        >

                    </div>

                </div>


                <!-- DISTRICT -->

                <div class="filter-group">

                    <label for="district">
                        District
                    </label>


                    <select
                        id="district"
                        name="district"
                    >

                        <option value="All Districts" <?php echo $district === "All Districts" ? "selected" : ""; ?>>All Districts</option>

<?php foreach ($filterDistricts as $filterDistrict): ?>
    <option value="<?php echo htmlspecialchars($filterDistrict); ?>" <?php echo strcasecmp((string)$district, (string)$filterDistrict) === 0 ? "selected" : ""; ?>>
        <?php echo htmlspecialchars($filterDistrict); ?>
    </option>
<?php endforeach; ?>

</select>

                </div>


                <!-- PRICE -->

                <div class="filter-group">

                    <label for="maxPrice">
                        Maximum Price (LKR)
                    </label>


                    <input
                        type="range"
                        id="maxPrice"
                        name="maxPrice"
                        min="100"
                        max="2000"
                        value="<?php
                            echo htmlspecialchars(
                                $maxPrice
                            );
                        ?>"
                        oninput="document.getElementById('priceValue').textContent = Number(this.value).toLocaleString()"
                    >


                    <div class="price-values">

                        <span>
                            100
                        </span>


                        <span id="priceValue">

                            <?php
                            echo number_format(
                                $maxPrice
                            );
                            ?>

                        </span>


                        <span>
                            2,000
                        </span>

                    </div>

                </div>


                <!-- LISTING TYPE -->

                <div class="filter-group">

                    <label for="listingType">
                        Listing Type
                    </label>

                    <select id="listingType" name="listingType">
                        <?php foreach ($listingOptions as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>" <?php echo $listingType === $type ? "selected" : ""; ?>>
                                <?php echo htmlspecialchars($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <!-- GROWING METHOD -->

                <div class="filter-group">

                    <label for="growingMethod">
                        Growing Method
                    </label>

                    <select id="growingMethod" name="growingMethod">
                        <?php foreach ($growingOptions as $method): ?>
                            <option value="<?php echo htmlspecialchars($method); ?>" <?php echo $growingMethod === $method ? "selected" : ""; ?>>
                                <?php echo htmlspecialchars($method); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <!-- CHECKBOXES -->

                <div class="checkbox-group">


                    <label>

                        <input
                            type="checkbox"
                            name="organic"
                            value="1"
                            <?php
                            echo $organic
                                ? "checked"
                                : "";
                            ?>
                        >

                        <span>
                            Organic Certified
                        </span>

                    </label>


                    <label>

                        <input
                            type="checkbox"
                            name="fresh"
                            value="1"
                            <?php
                            echo $fresh
                                ? "checked"
                                : "";
                            ?>
                        >

                        <span>
                            Fresh Today
                        </span>

                    </label>


                    <label>

                        <input
                            type="checkbox"
                            name="stock"
                            value="1"
                            <?php
                            echo $stock
                                ? "checked"
                                : "";
                            ?>
                        >

                        <span>
                            In Stock
                        </span>

                    </label>

                </div>


                <!-- PRESERVE SORT -->

                <input
                    type="hidden"
                    name="sort"
                    value="<?php
                        echo htmlspecialchars(
                            $sort
                        );
                    ?>"
                >


                <!-- APPLY -->

                <button
                    type="submit"
                    class="register-btn"
                    style="
                        width:100%;
                        border:none;
                        margin-top:20px;
                        cursor:pointer;
                    "
                >
                    Apply Filters
                </button>


                <!-- CLEAR -->

                <a
                    href="<?= e(buyerRoute('ProductController.php')) ?>"
                    style="
                        display:block;
                        text-align:center;
                        margin-top:12px;
                    "
                >
                    Clear Filters
                </a>


            </form>

        </aside>



        <!-- =================================================
             PRODUCTS
        ================================================== -->

        <section class="products-area">


            <!-- =================================================
                 SORT
            ================================================== -->

            <div class="active-filter-summary">
                <div>
                    <strong>Filters applied:</strong>
                    <span class="filter-chip"><?php echo htmlspecialchars($district); ?></span>
                    <span class="filter-chip">Up to LKR <?php echo number_format((float)$maxPrice); ?></span>
                    <span class="filter-chip"><?php echo htmlspecialchars($listingType); ?></span>
                    <span class="filter-chip"><?php echo htmlspecialchars($growingMethod); ?></span>
                    <?php if ($organic): ?><span class="filter-chip">Organic Certified</span><?php endif; ?>
                    <?php if ($fresh): ?><span class="filter-chip">Fresh Today</span><?php endif; ?>
                    <?php if ($stock): ?><span class="filter-chip">In Stock</span><?php endif; ?>
                    <?php if ($search !== ''): ?><span class="filter-chip">Search: <?php echo htmlspecialchars($search); ?></span><?php endif; ?>
                </div>
                <span class="filter-help">Use Apply Filters to update the products below.</span>
            </div>

            <div class="sort-bar">


                <span class="result-count">

                    Showing
                    <?php echo count($products); ?>
                    results

                </span>


                <form
                    method="GET"
                    action="<?= e(buyerRoute('ProductController.php')) ?>"
                    class="sort-control"
                >


                    <!-- Preserve search -->

                    <input
                        type="hidden"
                        name="search"
                        value="<?php
                            echo htmlspecialchars(
                                $search
                            );
                        ?>"
                    >


                    <!-- Preserve district -->

                    <input
                        type="hidden"
                        name="district"
                        value="<?php
                            echo htmlspecialchars(
                                $district
                            );
                        ?>"
                    >


                    <!-- Preserve listing type -->

                    <input type="hidden" name="listingType" value="<?php echo htmlspecialchars($listingType); ?>">
                    <input type="hidden" name="growingMethod" value="<?php echo htmlspecialchars($growingMethod); ?>">

                    <!-- Preserve price -->

                    <input
                        type="hidden"
                        name="maxPrice"
                        value="<?php
                            echo htmlspecialchars(
                                $maxPrice
                            );
                        ?>"
                    >


                    <!-- Preserve organic -->

                    <?php if ($organic): ?>

                        <input
                            type="hidden"
                            name="organic"
                            value="1"
                        >

                    <?php endif; ?>


                    <!-- Preserve fresh -->

                    <?php if ($fresh): ?>

                        <input
                            type="hidden"
                            name="fresh"
                            value="1"
                        >

                    <?php endif; ?>


                    <!-- Preserve stock -->

                    <?php if ($stock): ?>

                        <input
                            type="hidden"
                            name="stock"
                            value="1"
                        >

                    <?php endif; ?>


                    <label for="sortSelect">
                        Sort by:
                    </label>


                    <select
                        id="sortSelect"
                        name="sort"
                        onchange="this.form.submit()"
                    >

                        <option
                            value="Newest"
                            <?php
                            echo $sort === "Newest"
                                ? "selected"
                                : "";
                            ?>
                        >
                            Newest
                        </option>


                        <option
                            value="Price: Low to High"
                            <?php
                            echo $sort === "Price: Low to High"
                                ? "selected"
                                : "";
                            ?>
                        >
                            Price: Low to High
                        </option>


                        <option
                            value="Price: High to Low"
                            <?php
                            echo $sort === "Price: High to Low"
                                ? "selected"
                                : "";
                            ?>
                        >
                            Price: High to Low
                        </option>


                        <option
                            value="Best Rated"
                            <?php
                            echo $sort === "Best Rated"
                                ? "selected"
                                : "";
                            ?>
                        >
                            Best Rated
                        </option>


                        <option
                            value="Popular"
                            <?php
                            echo $sort === "Popular"
                                ? "selected"
                                : "";
                            ?>
                        >
                            Popular
                        </option>

                    </select>

                </form>

            </div>



            <!-- =================================================
                 PRODUCT GRID
            ================================================== -->

            <div class="product-grid">


                <?php if (count($products) > 0): ?>


                    <?php foreach ($products as $product): ?>


                        <article
                            class="product-card"
                        >


                            <!-- IMAGE -->

                            <div class="product-image">


                                <img
                                    src="<?php
                                        echo htmlspecialchars(
                                            $product["image"]
                                        );
                                    ?>"
                                    alt="<?php
                                        echo htmlspecialchars(
                                            $product["name"]
                                        );
                                    ?>"
                                >


                                <div class="product-badges">


                                    <?php if ($product["fresh"]): ?>

                                        <span class="fresh-badge">
                                            FRESH TODAY
                                        </span>

                                    <?php endif; ?>


                                    <?php if ($product["organic"]): ?>

                                        <span class="organic-badge">
                                            ORGANIC
                                        </span>

                                    <?php endif; ?>


                                </div>


                                <button
                                    type="button"
                                    class="favorite-btn"
                                    aria-label="Favorite"
                                >

                                    <span class="material-symbols-outlined">
                                        favorite
                                    </span>

                                </button>


                            </div>



                            <!-- CONTENT -->

                            <div class="product-content">


                                <div class="product-title-row">


                                    <h3>

                                        <?php
                                        echo htmlspecialchars(
                                            $product["name"]
                                        );
                                        ?>

                                    </h3>


                                    <span class="product-price">

                                        LKR

                                        <?php
                                        echo htmlspecialchars(
                                            $product["price"]
                                        );
                                        ?>


                                        <small>

                                            /

                                            <?php
                                            echo htmlspecialchars(
                                                $product["unit"]
                                            );
                                            ?>

                                        </small>

                                    </span>


                                </div>


                                <div class="farmer-info">


                                    <span class="material-symbols-outlined">
                                        storefront
                                    </span>


                                    <span>

                                        <?php
                                        echo htmlspecialchars(
                                            $product["farmer"]
                                        );
                                        ?>

                                    </span>


                                </div>


                                <div class="rating">


                                    <span class="material-symbols-outlined star">
                                        star
                                    </span>


                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $product["rating"]
                                        );
                                        ?>

                                    </strong>


                                    <span>

                                        (
                                        <?php
                                        echo htmlspecialchars(
                                            $product["reviews"]
                                        );
                                        ?>
                                        reviews)

                                    </span>


                                </div>



                                <!-- BUTTONS -->

                                <div class="product-buttons">


                                    <!-- ADD -->

                                    <a
                                        href="<?= e(buyerRoute('ProductController.php', 'action=add_to_cart&id=' . urlencode($product['id']))) ?>"
                                        class="add-button"
                                    >

                                        <span class="material-symbols-outlined">
                                            shopping_cart
                                        </span>

                                        Add

                                    </a>


                                    <!-- DETAILS -->

                                    <a
                                        href="<?= e(buyerRoute('ProductController.php', 'action=details&id=' . urlencode($product['id']))) ?>"
                                        class="details-button"
                                    >

                                        Details

                                    </a>


                                </div>

                            </div>

                        </article>


                    <?php endforeach; ?>


                <?php else: ?>


                    <div class="no-products">

                        <span class="material-symbols-outlined">
                            search_off
                        </span>


                        <h3>
                            No products found
                        </h3>


                        <p>
                            Try changing your filters.
                        </p>

                    </div>


                <?php endif; ?>


            </div>

        </section>

    </div>

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


        <div class="footer-links">

            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                About Harvestly
            </a>


            <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                Quick Links
            </a>


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



<script src="<?= e($baseUrl) ?>/js/Buyer/browse-products.js"></script>




</body>

</html>