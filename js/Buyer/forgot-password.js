/* =====================================================
   HARVESTLY - FORGOT PASSWORD
   PURE JAVASCRIPT
   NO FRAMEWORK
   ===================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =================================================
           ELEMENTS
           ================================================= */

        const form =
            document.getElementById(
                "forgotPasswordForm"
            );

        const email =
            document.getElementById(
                "email"
            );

        const emailError =
            document.getElementById(
                "emailError"
            );


        /* =================================================
           CLEAR EMAIL ERROR
           ================================================= */

        if (email) {

            email.addEventListener(
                "input",
                function () {

                    email.classList.remove(
                        "input-error"
                    );

                    emailError.textContent =
                        "";

                }
            );

        }


        /* =================================================
           FORM SUBMIT
           ================================================= */

        if (form) {

            form.addEventListener(
                "submit",
                function (event) {

                    let valid = true;


                    const emailValue =
                        email.value.trim();


                    /* EMPTY EMAIL */

                    if (
                        emailValue === ""
                    ) {

                        email.classList.add(
                            "input-error"
                        );

                        emailError.textContent =
                            "Please enter your email address.";

                        valid = false;

                    }


                    /* INVALID EMAIL */

                    else {

                        const emailPattern =
                            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


                        if (
                            !emailPattern.test(
                                emailValue
                            )
                        ) {

                            email.classList.add(
                                "input-error"
                            );

                            emailError.textContent =
                                "Please enter a valid email address.";

                            valid = false;

                        }

                    }


                    /* STOP FORM */

                    if (!valid) {

                        event.preventDefault();

                        email.focus();

                        return;

                    }


                    /*
                     * Do NOT preventDefault here.
                     *
                     * PHP will receive the email.
                     */

                }
            );

        }


        /* =================================================
           ENTER KEY
           ================================================= */

        if (email) {

            email.addEventListener(
                "keydown",
                function (event) {

                    if (
                        event.key ===
                        "Enter"
                    ) {

                        form.requestSubmit();

                    }

                }
            );

        }

    }
);