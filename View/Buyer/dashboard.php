<?php

$baseUrl = $baseUrl ?? url('');

$buyerName = trim((string)($buyerName ?? ''));
if ($buyerName === '') {
    $buyerName = trim((string)($_SESSION['user_name'] ?? ''));
}
if ($buyerName === '') {
    $buyerName = trim((string)($_SESSION['user_email'] ?? 'Buyer'));
}

$notificationCount =
    $notificationCount ?? 0;

$cartCount =
    $cartCount ?? 0;

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
        Harvestly - Buyer Dashboard
    </title>


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="<?= e($baseUrl) ?>/css/Buyer/buyer-dashboard.css"
    >


    <!-- Fonts -->

    <!-- Material Symbols -->

            <link rel="stylesheet" href="<?= e($baseUrl) ?>/css/Buyer/buyer-header-polish.css">

<script src="<?= e($baseUrl) ?>/js/icon-fallback.js" defer></script>
</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="navbar">

    <div class="navbar-inner">


        <!-- LOGO -->

        <a
            href="<?= e(buyerRoute('DashboardController.php')) ?>"
            class="logo"
        >
            <img src="<?= e($baseUrl) ?>/assets/harvestly-logo-horizontal.png" alt="Harvestly" class="harvestly-brand-logo">
        </a>



        <!-- DESKTOP NAVIGATION -->

        <div class="nav-links">

            <a
                href="<?= e(buyerRoute('DashboardController.php')) ?>"
                class="nav-link active"
            >
                Home
            </a>


            <a
                href="<?= e(buyerRoute('ProductController.php')) ?>"
                class="nav-link"
            >
                Products
            </a>

            <a href="<?= e(buyerRoute('OrdersController.php')) ?>" class="nav-link">
                My Orders
            </a>

            <a href="<?= e(buyerRoute('ProfileController.php')) ?>" class="nav-link">
                Profile
            </a>

            <a href="<?= e(buyerRoute('ProfileController.php')) ?>#settings" class="nav-link">
                Settings
            </a>


        </div>



        <!-- RIGHT NAVIGATION -->

        <div class="nav-actions">


            <!-- SEARCH -->

            <div class="search-box">

                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search products or farmers..."
                    autocomplete="off"
                >


                <button
                    type="button"
                    id="searchButton"
                    class="search-button"
                    aria-label="Search"
                    title="Search"
                >

                    <span class="material-symbols-outlined">
                        search
                    </span>

                </button>

            </div>



            <?php include __DIR__ . '/header-icons.php'; ?>


            <!-- AUTH -->

            <form action="<?= e(url('index.php?action=logout')) ?>" method="post" class="auth-buttons">
                <?= csrfField() ?>
                <button type="submit" class="logout-button">Logout</button>
            </form>



            <!-- MOBILE MENU -->

            <button
                type="button"
                class="mobile-menu-button"
                id="mobileMenuButton"
                aria-label="Open menu"
            >

                <span class="material-symbols-outlined">
                    menu
                </span>

            </button>


        </div>

    </div>



    <!-- =================================================
         MOBILE NAVIGATION
    ================================================== -->

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

        <a href="<?= e(buyerRoute('OrdersController.php')) ?>">
            My Orders
        </a>

        <a href="<?= e(buyerRoute('ProfileController.php')) ?>">
            Profile
        </a>

        <a href="<?= e(buyerRoute('ProfileController.php')) ?>#settings">
            Settings
        </a>


        <form action="<?= e(url('index.php?action=logout')) ?>" method="post" class="mobile-logout-form">
            <?= csrfField() ?>
            <button type="submit">Logout</button>
        </form>


        <a
            href="<?= e(buyerRoute('CartController.php')) ?>"
        >
            Shopping Cart

            <?php if ($cartCount > 0): ?>

                (
                <?php
                echo htmlspecialchars(
                    $cartCount
                );
                ?>
                )

            <?php endif; ?>

        </a>

    </div>

</nav>



<!-- =====================================================
     MAIN
===================================================== -->

<main class="main-container">


    <!-- =================================================
         HERO
    ================================================== -->

    <section class="hero-banner">


        <!-- BACKGROUND IMAGE -->

        <div class="hero-image"></div>


        <!-- OVERLAY -->

        <div class="hero-overlay">


            <div class="hero-content">


                <h1>

                    Welcome back,

                    <?php
                    echo htmlspecialchars(
                        $buyerName
                    );
                    ?>

                    !

                </h1>


                <p>

                    Discover the freshest produce from local
                    Sri Lankan farmers, delivered straight to
                    your door.

                </p>


                <div class="hero-buttons">


                    <a
                        href="<?= e(buyerRoute('ProductController.php')) ?>"
                        class="shop-button"
                    >
                        Shop Now
                    </a>


                    <a
                        href="#offers"
                        class="offers-button"
                    >
                        View Offers
                    </a>


                </div>


            </div>


        </div>

    </section>



    <!-- =================================================
         CATEGORIES
    ================================================== -->

    <section
        id="categories"
        class="dashboard-section"
    >

        <div class="section-inner">

            <h2>
                Fresh Categories
            </h2>

            <p>
                Browse fresh vegetables and produce from
                trusted Sri Lankan farmers.
            </p>


            <a
                href="<?= e(buyerRoute('ProductController.php')) ?>"
                class="shop-button"
            >
                Browse Products
            </a>

        </div>

    </section>



    <!-- =================================================
         FARMERS
    ================================================== -->

    <section
        id="farmers"
        class="dashboard-section"
    >

        <div class="section-inner">

            <h2>
                Trusted Local Farmers
            </h2>

            <p>
                Buy directly from verified farmers across
                Sri Lanka.
            </p>

        </div>

    </section>



    <!-- =================================================
         OFFERS
    ================================================== -->

    <section
        id="offers"
        class="dashboard-section"
    >

        <div class="section-inner">

            <h2>
                Fresh Offers
            </h2>

            <p>
                Discover fresh offers and seasonal produce
                available from local farms.
            </p>


            <a
                href="<?= e(buyerRoute('ProductController.php')) ?>"
                class="shop-button"
            >
                Shop Now
            </a>

        </div>

    </section>



    <!-- =================================================
         ABOUT
    ================================================== -->

    <section
        id="about"
        class="dashboard-section"
    >

        <div class="section-inner">

            <h2>
                About Harvestly
            </h2>

            <p>
                Harvestly connects Sri Lankan buyers directly
                with trusted local farmers, bringing fresh
                farm produce straight to your table.
            </p>

        </div>

    </section>



    <!-- =================================================
         FOOTER
    ================================================== -->

    <footer class="footer">

        <div class="footer-inner">


            <!-- BRAND -->

            <div class="footer-brand">

                <span class="footer-logo">
                    Harvestly
                </span>


                <p>
                    © 2026 Harvestly.
                    Bridging Sri Lankan Fields to Your Table.
                </p>

            </div>



            <!-- LINKS -->

            <div class="footer-links">


                <a href="#about">
                    About Harvestly
                </a>


                <a
                        href="<?= e(buyerRoute('ProductController.php')) ?>"
                >
                    Quick Links
                </a>


                <a href="#contact">
                    Contact Us
                </a>


                <a href="#privacy">
                    Privacy Policy
                </a>


                <a href="#terms">
                    Terms of Service
                </a>


            </div>


        </div>

    </footer>


</main>



<script src="<?= e($baseUrl) ?>/js/Buyer/buyer-dashboard.js"></script>


</body>

</html>