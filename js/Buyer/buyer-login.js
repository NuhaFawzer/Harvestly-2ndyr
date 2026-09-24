/* =====================================================
   HARVESTLY BUYER LOGIN
   PURE JAVASCRIPT
   NO FRAMEWORK
   ===================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const form =
            document.getElementById(
                "loginForm"
            );

        const email =
            document.getElementById(
                "email"
            );

        const password =
            document.getElementById(
                "password"
            );

        const emailError =
            document.getElementById(
                "emailError"
            );

        const passwordError =
            document.getElementById(
                "passwordError"
            );

        const passwordToggle =
            document.getElementById(
                "passwordToggle"
            );


        /* =================================================
           PASSWORD SHOW / HIDE
           ================================================= */

        if (passwordToggle) {

            passwordToggle.addEventListener(
                "click",
                function () {

                    if (
                        password.type ===
                        "password"
                    ) {

                        password.type =
                            "text";

                        passwordToggle
                            .querySelector(
                                ".material-symbols-outlined"
                            )
                            .textContent =
                            "visibility_off";

                    } else {

                        password.type =
                            "password";

                        passwordToggle
                            .querySelector(
                                ".material-symbols-outlined"
                            )
                            .textContent =
                            "visibility";

                    }

                }
            );

        }


        /* =================================================
           EMAIL VALIDATION
           ================================================= */

        if (email) {

            email.addEventListener(
                "input",
                function () {

                    emailError.textContent =
                        "";

                    email.classList.remove(
                        "input-error"
                    );

                }
            );

        }


        /* =================================================
           PASSWORD VALIDATION
           ================================================= */

        if (password) {

            password.addEventListener(
                "input",
                function () {

                    passwordError.textContent =
                        "";

                    password.classList.remove(
                        "input-error"
                    );

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


                    /* EMAIL */

                    const emailValue =
                        email.value.trim();


                    if (
                        emailValue === ""
                    ) {

                        emailError.textContent =
                            "Email address is required.";

                        email.classList.add(
                            "input-error"
                        );

                        valid = false;

                    } else {

                        const emailPattern =
                            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


                        if (
                            !emailPattern.test(
                                emailValue
                            )
                        ) {

                            emailError.textContent =
                                "Please enter a valid email address.";

                            email.classList.add(
                                "input-error"
                            );

                            valid = false;

                        }

                    }


                    /* PASSWORD */

                    const passwordValue =
                        password.value;


                    if (
                        passwordValue === ""
                    ) {

                        passwordError.textContent =
                            "Password is required.";

                        password.classList.add(
                            "input-error"
                        );

                        valid = false;

                    } else if (
                        passwordValue.length < 6
                    ) {

                        passwordError.textContent =
                            "Password must contain at least 6 characters.";

                        password.classList.add(
                            "input-error"
                        );

                        valid = false;

                    }


                    /* STOP FORM */

                    if (!valid) {

                        event.preventDefault();

                        return;

                    }


                    /*
                     * PHP will receive the form here.
                     * Do not preventDefault() when valid.
                     */

                }
            );

        }


        /* =================================================
           REMEMBER ME
           ================================================= */

        const remember =
            document.getElementById(
                "remember"
            );


        if (remember) {

            const savedEmail =
                localStorage.getItem(
                    "harvestlyRememberEmail"
                );


            if (savedEmail) {

                email.value =
                    savedEmail;

                remember.checked =
                    true;

            }


            form.addEventListener(
                "submit",
                function () {

                    if (
                        remember.checked
                    ) {

                        localStorage.setItem(
                            "harvestlyRememberEmail",
                            email.value.trim()
                        );

                    } else {

                        localStorage.removeItem(
                            "harvestlyRememberEmail"
                        );

                    }

                }
            );

        }

    }
);