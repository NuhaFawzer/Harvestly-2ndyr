<?php

require_once __DIR__ . '/../../config/app.php';
redirect('index.php?page=signup_buyer');
exit;

$message = isset($message) ? $message : "";
$messageType = isset($messageType) ? $messageType : "";

$formData = isset($formData) ? $formData : [
    "fullName" => "",
    "email" => "",
    "phone" => "",
    "district" => "",
    "city" => ""
];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Harvestly - Create Buyer Account</title>

    <!-- Your own CSS -->
    <link
        rel="stylesheet"
        href="/Harvestly/css/Buyer/buyer-registration.css"
    >

    <!-- Fonts -->
    <!-- Icons -->
        <script src="/Harvestly/js/icon-fallback.js" defer></script>
</head>

<body>


<!-- =====================================================
     NAVBAR
     ===================================================== -->

<nav class="navbar">

    <div class="navbar-container">

        <a
            href="/Harvestly/Controller/Buyer/LandingController.php"
            class="logo"
        >
            <img src="/Harvestly/assets/harvestly-logo.jpeg" alt="Harvestly" style="height:36px;width:auto;display:block;object-fit:contain;">
        </a>

    </div>

</nav>


<!-- =====================================================
     MAIN
     ===================================================== -->

<main class="main-container">


    <!-- =================================================
         LEFT IMAGE
         ================================================= -->

    <section class="image-section">

        <img
            src="/Harvestly/assets/register-garden.jpg"
            alt="Sri Lankan family shopping for fresh vegetables"
            class="main-image"
        >

        <div class="image-overlay">

            <h2>
                Fresh from the Farm to Your Table
            </h2>

            <p>
                Join thousands of Sri Lankan families enjoying
                direct access to the freshest local produce,
                supporting our farmers and eating healthier
                every day.
            </p>

        </div>

    </section>


    <!-- =================================================
         MOBILE IMAGE
         ================================================= -->

    <section class="mobile-image-section">

        <img
            src="/Harvestly/assets/register-garden.jpg"
            alt="Fresh vegetables"
        >

        <div class="mobile-image-overlay">

            <h2>
                Fresh from the Farm
            </h2>

        </div>

    </section>


    <!-- =================================================
         REGISTRATION FORM
         ================================================= -->

    <section class="form-section">

        <div class="registration-card">


            <!-- HEADER -->

            <div class="form-header">

                <h1>
                    Create Buyer Account
                </h1>

                <p>
                    Join Harvestly and buy fresh vegetables
                    directly from trusted Sri Lankan farmers.
                </p>

            </div>


            <!-- PHP MESSAGE -->

            <?php if ($message !== ""): ?>

                <div
                    class="php-message <?php echo htmlspecialchars($messageType); ?>"
                >

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php endif; ?>


            <!-- FORM -->

            <form
                action="/Harvestly/Controller/Buyer/RegistrationController.php"
                method="POST"
                id="registrationForm"
                novalidate
            >


                <!-- =====================================
                     FULL NAME
                     ===================================== -->

                <div class="form-group">

                    <label for="fullName">
                        FULL NAME
                    </label>

                    <div class="input-wrapper">

                        <span class="material-symbols-outlined">
                            person
                        </span>

                        <input
                            type="text"
                            id="fullName"
                            name="fullName"
                            placeholder="Nimal Perera"
                            value="<?php echo htmlspecialchars($formData['fullName'], ENT_QUOTES, 'UTF-8'); ?>"
                        >

                    </div>

                    <small
                        class="error-message"
                        id="fullNameError"
                    ></small>

                </div>


                <!-- =====================================
                     EMAIL
                     ===================================== -->

                <div class="form-group">

                    <label for="email">
                        EMAIL ADDRESS
                    </label>

                    <div class="input-wrapper">

                        <span class="material-symbols-outlined">
                            mail
                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="nimal@gmail.com"
                            value="<?php echo htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8'); ?>"
                        >

                    </div>

                    <small
                        class="error-message"
                        id="emailError"
                    ></small>

                </div>


                <!-- =====================================
                     PHONE
                     ===================================== -->

                <div class="form-group">

                    <label for="phone">
                        PHONE NUMBER
                    </label>

                    <div class="input-wrapper">

                        <span class="material-symbols-outlined">
                            call
                        </span>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            placeholder="+94 77 123 4567"
                            value="<?php echo htmlspecialchars($formData['phone'], ENT_QUOTES, 'UTF-8'); ?>"
                        >

                    </div>

                    <small
                        class="error-message"
                        id="phoneError"
                    ></small>

                </div>


                <!-- =====================================
                     DISTRICT + CITY
                     ===================================== -->

                <div class="two-column">


                    <!-- DISTRICT -->

                    <div class="form-group">

                        <label for="district">
                            DISTRICT
                        </label>

                        <select
                            id="district"
                            name="district"
                        >

                            <option
                                value=""
                                disabled
                                <?php echo $formData["district"] === "" ? "selected" : ""; ?>
                            >
                                Select District
                            </option>

                            <option
                                value="Colombo"
                                <?php echo $formData["district"] === "Colombo" ? "selected" : ""; ?>
                            >
                                Colombo
                            </option>

                            <option
                                value="Gampaha"
                                <?php echo $formData["district"] === "Gampaha" ? "selected" : ""; ?>
                            >
                                Gampaha
                            </option>

                            <option
                                value="Kandy"
                                <?php echo $formData["district"] === "Kandy" ? "selected" : ""; ?>
                            >
                                Kandy
                            </option>

                            <option
                                value="Kurunegala"
                                <?php echo $formData["district"] === "Kurunegala" ? "selected" : ""; ?>
                            >
                                Kurunegala
                            </option>

                            <option
                                value="Nuwara Eliya"
                                <?php echo $formData["district"] === "Nuwara Eliya" ? "selected" : ""; ?>
                            >
                                Nuwara Eliya
                            </option>

                            <option
                                value="Badulla"
                                <?php echo $formData["district"] === "Badulla" ? "selected" : ""; ?>
                            >
                                Badulla
                            </option>

                            <option
                                value="Galle"
                                <?php echo $formData["district"] === "Galle" ? "selected" : ""; ?>
                            >
                                Galle
                            </option>

                            <option
                                value="Jaffna"
                                <?php echo $formData["district"] === "Jaffna" ? "selected" : ""; ?>
                            >
                                Jaffna
                            </option>

                            <option
                                value="Matara"
                                <?php echo $formData["district"] === "Matara" ? "selected" : ""; ?>
                            >
                                Matara
                            </option>

                            <option
                                value="Anuradhapura"
                                <?php echo $formData["district"] === "Anuradhapura" ? "selected" : ""; ?>
                            >
                                Anuradhapura
                            </option>

                        </select>

                        <small
                            class="error-message"
                            id="districtError"
                        ></small>

                    </div>


                    <!-- CITY -->

                    <div class="form-group">

                        <label for="city">
                            CITY
                        </label>

                        <select
                            id="city"
                            name="city"
                        >

                            <option
                                value=""
                                disabled
                                selected
                            >
                                Select your city
                            </option>

                        </select>

                        <small
                            class="error-message"
                            id="cityError"
                        ></small>

                    </div>

                </div>


                <!-- =====================================
                     PASSWORD
                     ===================================== -->

                <div class="form-group">

                    <label for="password">
                        PASSWORD
                    </label>

                    <div class="input-wrapper">

                        <span class="material-symbols-outlined">
                            lock
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-target="password"
                        >

                            <span class="material-symbols-outlined">
                                visibility_off
                            </span>

                        </button>

                    </div>

                    <small
                        class="error-message"
                        id="passwordError"
                    ></small>

                </div>


                <!-- =====================================
                     CONFIRM PASSWORD
                     ===================================== -->

                <div class="form-group">

                    <label for="confirmPassword">
                        CONFIRM PASSWORD
                    </label>

                    <div class="input-wrapper">

                        <span class="material-symbols-outlined">
                            lock
                        </span>

                        <input
                            type="password"
                            id="confirmPassword"
                            name="confirmPassword"
                            placeholder="••••••••"
                        >

                    </div>

                    <small
                        class="error-message"
                        id="confirmPasswordError"
                    ></small>

                </div>


                <!-- =====================================
                     TERMS
                     ===================================== -->

                <div class="terms-row">

                    <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                    >

                    <label for="terms">

                        I agree to the

                        <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                            Terms & Conditions
                        </a>

                        and

                        <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                            Privacy Policy
                        </a>

                    </label>

                </div>

                <small
                    class="error-message"
                    id="termsError"
                ></small>


                <!-- =====================================
                     SUBMIT
                     ===================================== -->

                <button
                    type="submit"
                    class="create-account-btn"
                >

                    Create Account

                </button>

            </form>


            <!-- =====================================
                 DIVIDER
                 ===================================== -->

            <div class="divider">

                <span></span>

                <strong>
                    OR
                </strong>

                <span></span>

            </div>


            <!-- =====================================
                 LINKS
                 ===================================== -->

            <div class="account-links">

                <p>

                    Already have an account?

                    <a href="/Harvestly/Controller/Buyer/AuthController.php">
                        Login
                    </a>

                </p>


                <div class="registration-links">

                    <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                        Register as Farmer
                    </a>

                    <span>
                        •
                    </span>

                    <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                        Courier Partner Registration
                    </a>

                </div>

            </div>

        </div>

    </section>

</main>


<!-- =====================================================
     BENEFITS
     ===================================================== -->

<section class="benefits-section">

    <div class="benefits-container">


        <!-- BENEFIT 1 -->

        <div class="benefit-card">

            <div class="benefit-icon green-one">

                <span class="material-symbols-outlined">
                    nutrition
                </span>

            </div>

            <h3>
                Fresh Farm Vegetables
            </h3>

            <p>
                Sourced directly from local farms
                ensuring the highest quality and freshness.
            </p>

        </div>


        <!-- BENEFIT 2 -->

        <div class="benefit-card">

            <div class="benefit-icon green-two">

                <span class="material-symbols-outlined">
                    verified_user
                </span>

            </div>

            <h3>
                Secure Payments
            </h3>

            <p>
                Multiple secure payment options to
                guarantee a safe transaction every time.
            </p>

        </div>


        <!-- BENEFIT 3 -->

        <div class="benefit-card">

            <div class="benefit-icon green-three">

                <span class="material-symbols-outlined">
                    local_shipping
                </span>

            </div>

            <h3>
                Islandwide Delivery
            </h3>

            <p>
                Fast and reliable delivery network
                covering all districts in Sri Lanka.
            </p>

        </div>


        <!-- BENEFIT 4 -->

        <div class="benefit-card">

            <div class="benefit-icon green-four">

                <span class="material-symbols-outlined">
                    handshake
                </span>

            </div>

            <h3>
                Trusted Farmers
            </h3>

            <p>
                We work directly with verified Sri Lankan
                farmers to empower local agriculture.
            </p>

        </div>

    </div>

</section>


<!-- =====================================================
     FOOTER
     ===================================================== -->

<footer class="footer">

    <div class="footer-container">


        <div class="footer-brand">

            <h3>
                Harvestly
            </h3>

            <p>
                Bridging Sri Lankan Fields to Your Table.
            </p>

            <p>
                © 2026 Harvestly Sri Lanka.
            </p>

        </div>


        <div class="footer-column">

            <h4>
                Company
            </h4>

            <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                About Us
            </a>

            <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                Careers
            </a>

            <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                Press
            </a>

        </div>


        <div class="footer-column">

            <h4>
                Support
            </h4>

            <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                Help Center
            </a>

            <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                Contact Us
            </a>

            <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                Shipping Info
            </a>

        </div>


        <div class="footer-column">

            <h4>
                Legal
            </h4>

            <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                Privacy Policy
            </a>

            <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                Terms & Conditions
            </a>

            <a href="/Harvestly/Controller/Buyer/DashboardController.php">
                Cookie Policy
            </a>

        </div>

    </div>

</footer>


<script src="/Harvestly/js/Buyer/buyer-registration.js"></script>

</body>

</html>