/* =====================================================
   HARVESTLY BUYER REGISTRATION
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
                "registrationForm"
            );

        const fullName =
            document.getElementById(
                "fullName"
            );

        const email =
            document.getElementById(
                "email"
            );

        const phone =
            document.getElementById(
                "phone"
            );

        const district =
            document.getElementById(
                "district"
            );

        const city =
            document.getElementById(
                "city"
            );

        const password =
            document.getElementById(
                "password"
            );

        const confirmPassword =
            document.getElementById(
                "confirmPassword"
            );

        const terms =
            document.getElementById(
                "terms"
            );


        /* =================================================
           SRI LANKAN CITIES
           ================================================= */

        const cities = {

            Colombo: [
                "Colombo",
                "Dehiwala",
                "Moratuwa",
                "Kotte",
                "Maharagama",
                "Homagama",
                "Avissawella"
            ],

            Gampaha: [
                "Gampaha",
                "Negombo",
                "Wattala",
                "Ja-Ela",
                "Kelaniya",
                "Minuwangoda"
            ],

            Kandy: [
                "Kandy",
                "Peradeniya",
                "Katugastota",
                "Gampola",
                "Nawalapitiya"
            ],

            Kurunegala: [
                "Kurunegala",
                "Kuliyapitiya",
                "Narammala",
                "Wariyapola"
            ],

            "Nuwara Eliya": [
                "Nuwara Eliya",
                "Hatton",
                "Talawakele"
            ],

            Badulla: [
                "Badulla",
                "Bandarawela",
                "Haputale",
                "Mahiyanganaya"
            ],

            Galle: [
                "Galle",
                "Hikkaduwa",
                "Ambalangoda",
                "Elpitiya"
            ],

            Jaffna: [
                "Jaffna",
                "Chavakachcheri",
                "Point Pedro"
            ],

            Matara: [
                "Matara",
                "Weligama",
                "Akuressa",
                "Dikwella"
            ],

            Anuradhapura: [
                "Anuradhapura",
                "Kekirawa",
                "Medawachchiya"
            ]

        };


        /* =================================================
           DISTRICT → CITY
           ================================================= */

        if (district && city) {

            district.addEventListener(
                "change",
                function () {

                    const selectedDistrict =
                        district.value;


                    city.innerHTML =
                        '<option value="" disabled selected>Select your city</option>';


                    if (
                        cities[
                            selectedDistrict
                        ]
                    ) {

                        cities[
                            selectedDistrict
                        ].forEach(
                            function (cityName) {

                                const option =
                                    document.createElement(
                                        "option"
                                    );

                                option.value =
                                    cityName;

                                option.textContent =
                                    cityName;

                                city.appendChild(
                                    option
                                );

                            }
                        );

                    }

                }
            );

        }


        /* =================================================
           PASSWORD SHOW / HIDE
           ================================================= */

        const toggleButtons =
            document.querySelectorAll(
                ".password-toggle"
            );


        toggleButtons.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const targetID =
                            button.dataset.target;

                        const target =
                            document.getElementById(
                                targetID
                            );

                        const icon =
                            button.querySelector(
                                ".material-symbols-outlined"
                            );


                        if (
                            target.type ===
                            "password"
                        ) {

                            target.type =
                                "text";

                            icon.textContent =
                                "visibility";

                        } else {

                            target.type =
                                "password";

                            icon.textContent =
                                "visibility_off";

                        }

                    }
                );

            }
        );


        /* =================================================
           ERROR HELPERS
           ================================================= */

        function setError(
            input,
            errorElement,
            message
        ) {

            if (input) {

                input.classList.add(
                    "input-error"
                );

            }

            if (errorElement) {

                errorElement.textContent =
                    message;

            }

        }


        function clearError(
            input,
            errorElement
        ) {

            if (input) {

                input.classList.remove(
                    "input-error"
                );

            }

            if (errorElement) {

                errorElement.textContent =
                    "";

            }

        }


        /* =================================================
           LIVE VALIDATION
           ================================================= */

        fullName.addEventListener(
            "input",
            function () {

                clearError(
                    fullName,
                    document.getElementById(
                        "fullNameError"
                    )
                );

            }
        );


        email.addEventListener(
            "input",
            function () {

                clearError(
                    email,
                    document.getElementById(
                        "emailError"
                    )
                );

            }
        );


        phone.addEventListener(
            "input",
            function () {

                clearError(
                    phone,
                    document.getElementById(
                        "phoneError"
                    )
                );

            }
        );


        password.addEventListener(
            "input",
            function () {

                clearError(
                    password,
                    document.getElementById(
                        "passwordError"
                    )
                );

            }
        );


        confirmPassword.addEventListener(
            "input",
            function () {

                clearError(
                    confirmPassword,
                    document.getElementById(
                        "confirmPasswordError"
                    )
                );

            }
        );


        /* =================================================
           FORM VALIDATION
           ================================================= */

        if (form) {

            form.addEventListener(
                "submit",
                function (event) {

                    let valid = true;


                    /* FULL NAME */

                    const nameValue =
                        fullName.value.trim();


                    if (
                        nameValue === ""
                    ) {

                        setError(
                            fullName,
                            document.getElementById(
                                "fullNameError"
                            ),
                            "Full name is required."
                        );

                        valid = false;

                    } else if (
                        nameValue.length < 3
                    ) {

                        setError(
                            fullName,
                            document.getElementById(
                                "fullNameError"
                            ),
                            "Please enter your full name."
                        );

                        valid = false;

                    }


                    /* EMAIL */

                    const emailValue =
                        email.value.trim();


                    const emailPattern =
                        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


                    if (
                        emailValue === ""
                    ) {

                        setError(
                            email,
                            document.getElementById(
                                "emailError"
                            ),
                            "Email address is required."
                        );

                        valid = false;

                    } else if (
                        !emailPattern.test(
                            emailValue
                        )
                    ) {

                        setError(
                            email,
                            document.getElementById(
                                "emailError"
                            ),
                            "Please enter a valid email."
                        );

                        valid = false;

                    }


                    /* PHONE */

                    const phoneValue =
                        phone.value.trim();


                    const phonePattern =
                        /^(\+94|0)?[0-9]{9,10}$/;


                    const cleanPhone =
                        phoneValue
                            .replace(/\s/g, "");


                    if (
                        cleanPhone === ""
                    ) {

                        setError(
                            phone,
                            document.getElementById(
                                "phoneError"
                            ),
                            "Phone number is required."
                        );

                        valid = false;

                    } else if (
                        !phonePattern.test(
                            cleanPhone
                        )
                    ) {

                        setError(
                            phone,
                            document.getElementById(
                                "phoneError"
                            ),
                            "Enter a valid Sri Lankan phone number."
                        );

                        valid = false;

                    }


                    /* DISTRICT */

                    if (
                        district.value === ""
                    ) {

                        document.getElementById(
                            "districtError"
                        ).textContent =
                            "Please select your district.";

                        valid = false;

                    }


                    /* CITY */

                    if (
                        city.value === ""
                    ) {

                        document.getElementById(
                            "cityError"
                        ).textContent =
                            "Please select your city.";

                        valid = false;

                    }


                    /* PASSWORD */

                    if (
                        password.value === ""
                    ) {

                        setError(
                            password,
                            document.getElementById(
                                "passwordError"
                            ),
                            "Password is required."
                        );

                        valid = false;

                    } else if (
                        password.value.length < 6
                    ) {

                        setError(
                            password,
                            document.getElementById(
                                "passwordError"
                            ),
                            "Password must contain at least 6 characters."
                        );

                        valid = false;

                    }


                    /* CONFIRM PASSWORD */

                    if (
                        confirmPassword.value === ""
                    ) {

                        setError(
                            confirmPassword,
                            document.getElementById(
                                "confirmPasswordError"
                            ),
                            "Please confirm your password."
                        );

                        valid = false;

                    } else if (
                        password.value !==
                        confirmPassword.value
                    ) {

                        setError(
                            confirmPassword,
                            document.getElementById(
                                "confirmPasswordError"
                            ),
                            "Passwords do not match."
                        );

                        valid = false;

                    }


                    /* TERMS */

                    if (
                        !terms.checked
                    ) {

                        document.getElementById(
                            "termsError"
                        ).textContent =
                            "You must accept the Terms & Conditions.";

                        valid = false;

                    } else {

                        document.getElementById(
                            "termsError"
                        ).textContent =
                            "";

                    }


                    /* STOP SUBMIT */

                    if (!valid) {

                        event.preventDefault();

                        const firstError =
                            document.querySelector(
                                ".input-error"
                            );


                        if (firstError) {

                            firstError.scrollIntoView({
                                behavior: "smooth",
                                block: "center"
                            });

                            firstError.focus();

                        }

                    }

                }
            );

        }

    }
);