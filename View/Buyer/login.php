<?php

require_once __DIR__ . '/../../config/app.php';
redirect('index.php?page=login');
exit;

/*
 * The Controller sends $message to this View.
 * This prevents "Undefined variable $message" warnings.
 */

$message = isset($message) ? $message : "";

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Harvestly - Buyer Login</title>


    <!-- Existing Buyer CSS -->

    <link
        rel="stylesheet"
        href="<?= e(BASE_URL) ?>/css/Buyer/buyer-login.css"
    >


    <!-- Google Fonts -->

    <!-- Material Symbols -->

        <script src="<?= e(BASE_URL) ?>/js/icon-fallback.js" defer></script>
</head>


<body>


<div class="login-page">


    <!-- =====================================================
         LEFT HERO SECTION
    ====================================================== -->

    <div class="hero-side">

        <div class="hero-image"></div>

        <div class="hero-overlay">

            <div class="hero-text">

                <h2>
                    Farm to Table,
                    Authentically.
                </h2>

                <p>
                    Experience the freshest produce
                    sourced directly from Sri Lanka's
                    finest rural fields, delivered with
                    modern efficiency.
                </p>

            </div>

        </div>

    </div>



    <!-- =====================================================
         RIGHT LOGIN SECTION
    ====================================================== -->

    <div class="login-side">


        <!-- Mobile Hero -->

        <div class="mobile-hero">

            <div class="mobile-hero-image"></div>

            <div class="mobile-hero-gradient"></div>

        </div>



        <div class="login-content">


            <!-- =================================================
                 LOGIN CARD
            ================================================== -->

            <div class="login-card">


                <!-- LOGIN HEADER -->

                <div class="login-header">


                    <!-- Harvestly Logo -->

                    <div class="harvestly-logo">
                        <img src="<?= e(BASE_URL) ?>/assets/harvestly-logo.jpeg" alt="Harvestly" style="height:42px;width:auto;max-width:190px;display:block;object-fit:contain;">
                    </div>


                    <h1>
                        Welcome Back
                    </h1>


                    <p>
                        Login to continue shopping fresh
                        vegetables from trusted Sri Lankan
                        farmers.
                    </p>


                </div>



                <!-- =================================================
                     PHP MESSAGE
                ================================================== -->

                <?php if ($message !== ""): ?>

                    <div class="php-message">

                        <?php
                        echo htmlspecialchars($message);
                        ?>

                    </div>

                <?php endif; ?>



                <!-- =================================================
                     LOGIN FORM
                ================================================== -->

                <form
                    action="<?= e(BASE_URL) ?>/Controller/Buyer/AuthController.php"
                    method="POST"
                    id="loginForm"
                >


                    <!-- EMAIL -->

                    <div class="form-group">

                        <label for="email">
                            Email Address
                        </label>


                        <div class="input-wrapper">


                            <span class="material-symbols-outlined input-icon">
                                mail
                            </span>


                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="example@gmail.com"
                                autocomplete="email"
                                required
                            >


                        </div>


                        <small
                            class="error-message"
                            id="emailError"
                        ></small>


                    </div>



                    <!-- PASSWORD -->

                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>


                        <div class="input-wrapper">


                            <span class="material-symbols-outlined input-icon">
                                lock
                            </span>


                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                required
                            >


                            <button
                                type="button"
                                class="password-toggle"
                                id="passwordToggle"
                            >

                                <span class="material-symbols-outlined">
                                    visibility
                                </span>

                            </button>


                        </div>


                        <small
                            class="error-message"
                            id="passwordError"
                        ></small>


                    </div>



                    <!-- =================================================
                         REMEMBER ME / FORGOT PASSWORD
                    ================================================== -->

                    <div class="remember-row">


                        <label class="remember-label">

                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                            >

                            <span>
                                Remember Me
                            </span>

                        </label>



                        <a
                            href="<?= e(BASE_URL) ?>/Controller/Buyer/ForgotPasswordController.php"
                            class="forgot-link"
                        >
                            Forgot Password?
                        </a>


                    </div>



                    <!-- LOGIN BUTTON -->

                    <button
                        type="submit"
                        class="login-button"
                    >
                        Login
                    </button>


                </form>



                <!-- =================================================
                     DIVIDER
                ================================================== -->

                <div class="divider">

                    <span></span>

                    <strong>
                        OR
                    </strong>

                    <span></span>

                </div>



                <!-- =================================================
                     OTHER LOGIN OPTIONS
                ================================================== -->

                <div class="secondary-links">


                    <!-- BUYER REGISTRATION -->

                    <a
                        href="<?= e(BASE_URL) ?>/Controller/Buyer/RegistrationController.php"
                        class="secondary-button"
                    >

                        <span class="material-symbols-outlined">
                            person_add
                        </span>

                        <span>
                            Register as Buyer
                        </span>

                    </a>



                    <!-- FARMER LOGIN -->

                    <a
                        href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"
                        class="secondary-button"
                    >

                        <span class="material-symbols-outlined">
                            agriculture
                        </span>

                        <span>
                            Farmer Login
                        </span>

                    </a>



                    <!-- COURIER LOGIN -->

                    <a
                        href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"
                        class="secondary-button"
                    >

                        <span class="material-symbols-outlined">
                            local_shipping
                        </span>

                        <span>
                            Courier Partner Login
                        </span>

                    </a>



                    <!-- ADMIN LOGIN -->

                    <a
                        href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php"
                        class="secondary-button"
                    >

                        <span class="material-symbols-outlined">
                            admin_panel_settings
                        </span>

                        <span>
                            Administrator Login
                        </span>

                    </a>


                </div>


            </div>



            <!-- =================================================
                 FEATURES
            ================================================== -->

            <div class="features">


                <!-- FARM FRESH -->

                <div class="feature-card">

                    <div class="feature-icon farm-icon">

                        <span class="material-symbols-outlined">
                            eco
                        </span>

                    </div>


                    <div>

                        <h4>
                            Farm Fresh
                        </h4>

                        <p>
                            Direct from Sri Lankan fields.
                        </p>

                    </div>

                </div>



                <!-- SECURE PAYMENTS -->

                <div class="feature-card">

                    <div class="feature-icon secure-icon">

                        <span class="material-symbols-outlined">
                            verified_user
                        </span>

                    </div>


                    <div>

                        <h4>
                            Secure Payments
                        </h4>

                        <p>
                            100% safe online transactions.
                        </p>

                    </div>

                </div>



                <!-- FAST DELIVERY -->

                <div class="feature-card">

                    <div class="feature-icon delivery-icon">

                        <span class="material-symbols-outlined">
                            speed
                        </span>

                    </div>


                    <div>

                        <h4>
                            Fast Delivery
                        </h4>

                        <p>
                            Islandwide quick shipping.
                        </p>

                    </div>

                </div>


            </div>


        </div>



        <!-- =================================================
             FOOTER
        ================================================== -->

        <footer class="login-footer">


            <p>
                © 2026 Harvestly.
                Bridging Sri Lankan Fields to Your Table.
            </p>


            <div>

                <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                    Privacy Policy
                </a>

                <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                    Terms of Service
                </a>

                <a href="<?= e(BASE_URL) ?>/Controller/Buyer/DashboardController.php">
                    Contact Us
                </a>

            </div>


        </footer>


    </div>


</div>



<!-- Existing Buyer JavaScript -->

<script src="<?= e(BASE_URL) ?>/js/Buyer/buyer-login.js"></script>


</body>

</html>