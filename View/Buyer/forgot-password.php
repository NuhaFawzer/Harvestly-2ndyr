<?php

require_once __DIR__ . '/../../config/app.php';
redirect('index.php?page=forgot_password');
exit;

$message = $message ?? '';
$messageType = $messageType ?? '';
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Forgot Password - Harvestly</title>

    <!-- CSS -->
    <link
        rel="stylesheet"
        href="<?= e(BASE_URL) ?>/css/Buyer/forgot-password.css"
    >

    <!-- Google Fonts -->
    <!-- Material Symbols -->
        <script src="<?= e(BASE_URL) ?>/js/icon-fallback.js" defer></script>
</head>


<body>


<!-- =====================================================
     PAGE
     ===================================================== -->

<div class="forgot-page">


    <!-- =================================================
         LEFT SIDE - DESKTOP IMAGE
         ================================================= -->

    <section class="desktop-image-section">

        <div class="desktop-image"></div>

        <div class="desktop-image-overlay"></div>

        <div class="brand-message">

            <h2>
                Secure Your Harvest.
            </h2>

            <p>
                Recover your account to continue bringing
                fresh Sri Lankan produce to the table.
            </p>

        </div>

    </section>


    <!-- =================================================
         MOBILE IMAGE
         ================================================= -->

    <section class="mobile-image-section">

        <div class="mobile-image"></div>

        <div class="mobile-image-overlay"></div>

    </section>


    <!-- =================================================
         RIGHT SIDE
         ================================================= -->

    <section class="content-section">

        <div class="content-wrapper">


            <!-- =================================================
                 LOGO + HEADER
                 ================================================= -->

            <div class="page-header">

                <img
                    class="harvestly-logo"
                    src="<?= e(BASE_URL) ?>/assets/harvestly-logo.jpeg"
                    alt="Harvestly Logo"
                >

                <h1>
                    Forgot Your Password?
                </h1>

                <p>
                    Enter your registered email address
                    and we'll send you a password reset link.
                </p>

            </div>


            <!-- =================================================
                 RESET FORM CARD
                 ================================================= -->

            <div class="reset-card">


                <?php if ($message !== ""): ?>

                    <div
                        class="php-message <?php echo $messageType; ?>"
                    >

                        <?php
                        echo htmlspecialchars($message);
                        ?>

                    </div>

                <?php endif; ?>


                <form
                    action="<?= e(BASE_URL) ?>/Controller/Buyer/ForgotPasswordController.php"
                    method="POST"
                    id="forgotPasswordForm"
                    novalidate
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
                                value="<?php echo htmlspecialchars(isset($_POST["email"]) ? $_POST["email"] : ""); ?>"
                            >

                        </div>

                        <small
                            class="error-message"
                            id="emailError"
                        ></small>

                    </div>


                    <!-- SEND BUTTON -->

                    <button
                        type="submit"
                        class="reset-button"
                    >

                        <span>
                            Send Reset Link
                        </span>

                        <span class="material-symbols-outlined">
                            arrow_forward
                        </span>

                    </button>

                </form>

            </div>


            <!-- =================================================
                 NEXT STEPS
                 ================================================= -->

            <div class="next-steps">

                <span class="material-symbols-outlined info-icon">
                    info
                </span>

                <div class="next-steps-content">

                    <p class="next-title">
                        Next Steps
                    </p>

                    <p>
                        • Check your inbox after submitting.
                    </p>

                    <p>
                        • Also check your Spam/Junk folder.
                    </p>

                    <p>
                        • The reset link expires in 30 minutes.
                    </p>

                </div>

            </div>


            <!-- =================================================
                 NAVIGATION LINKS
                 ================================================= -->

            <div class="navigation-links">


                <a
                    href="<?= e(BASE_URL) ?>/Controller/Buyer/AuthController.php"
                    class="navigation-link"
                >

                    <span class="material-symbols-outlined">
                        arrow_back
                    </span>

                    <span>
                        Back to Login
                    </span>

                </a>


                <a
                    href="<?= e(BASE_URL) ?>/Controller/Buyer/RegistrationController.php"
                    class="navigation-link"
                >

                    <span>
                        Create New Buyer Account
                    </span>

                    <span class="material-symbols-outlined">
                        open_in_new
                    </span>

                </a>

            </div>


            <!-- =================================================
                 SECURITY TIPS
                 ================================================= -->

            <div class="security-grid">


                <!-- CARD 1 -->

                <div class="security-card">

                    <span class="material-symbols-outlined security-icon">
                        password
                    </span>

                    <span>
                        Use a strong password
                    </span>

                </div>


                <!-- CARD 2 -->

                <div class="security-card">

                    <span class="material-symbols-outlined security-icon">
                        gpp_bad
                    </span>

                    <span>
                        Never share your password
                    </span>

                </div>


                <!-- CARD 3 -->

                <div class="security-card disabled-security">

                    <span class="material-symbols-outlined security-icon">
                        phonelink_lock
                    </span>

                    <span>
                        Enable 2FA
                        <br>
                        (Coming Soon)
                    </span>

                </div>


            </div>

        </div>

    </section>

</div>


<!-- JavaScript -->

<script src="<?= e(BASE_URL) ?>/js/Buyer/forgot-password.js"></script>

</body>

</html>