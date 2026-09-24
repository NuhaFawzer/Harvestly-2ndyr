document.addEventListener("DOMContentLoaded", function () {

    /* =========================
       MOBILE MENU
    ========================= */

    const menuBtn = document.getElementById("menuBtn");
    const mobileNav = document.getElementById("mobileNav");

    if (menuBtn && mobileNav) {

        menuBtn.addEventListener("click", function () {

            mobileNav.classList.toggle("show");

        });

    }


    /* =========================
       STAR RATINGS
    ========================= */

    const ratingGroups =
        document.querySelectorAll(".stars");


    ratingGroups.forEach(function (group) {

        const stars =
            group.querySelectorAll(".star");

        const ratingType =
            group.getAttribute("data-rating");


        stars.forEach(function (star) {

            star.addEventListener("click", function () {

                const selectedRating =
                    parseInt(
                        star.getAttribute("data-value")
                    );


                /* Update stars */

                stars.forEach(function (item) {

                    const value =
                        parseInt(
                            item.getAttribute("data-value")
                        );

                    if (value <= selectedRating) {

                        item.classList.add("active");

                    } else {

                        item.classList.remove("active");

                    }

                });


                /* Update hidden input */

                if (ratingType === "farmer") {

                    document.getElementById(
                        "farmerRating"
                    ).value = selectedRating;

                }


                if (ratingType === "delivery") {

                    document.getElementById(
                        "deliveryRating"
                    ).value = selectedRating;

                }

            });

        });

    });


    /* =========================
       REVIEW VALIDATION
    ========================= */

    const reviewForm =
        document.getElementById("reviewForm");


    if (reviewForm) {

        reviewForm.addEventListener("submit", function (event) {

            const farmerRating =
                parseInt(
                    document.getElementById(
                        "farmerRating"
                    ).value
                );


            const deliveryRating =
                parseInt(
                    document.getElementById(
                        "deliveryRating"
                    ).value
                );


            if (
                farmerRating === 0 ||
                deliveryRating === 0
            ) {

                event.preventDefault();

                alert(
                    "Please rate both the farmer and delivery experience."
                );

            }

        });

    }


    /* =========================
       FILE UPLOAD
    ========================= */

    const fileInput =
        document.getElementById("photos");

    const fileName =
        document.getElementById("fileName");


    if (fileInput && fileName) {

        fileInput.addEventListener("change", function () {

            if (fileInput.files.length === 0) {

                fileName.textContent =
                    "No file selected";

                return;

            }


            if (fileInput.files.length === 1) {

                fileName.textContent =
                    fileInput.files[0].name;

            } else {

                fileName.textContent =
                    fileInput.files.length +
                    " files selected";

            }

        });

    }


    /* =========================
       REGISTER / LOGIN
    ========================= */

    const loginBtn =
        document.querySelector(".login-btn");

    const registerBtn =
        document.querySelector(".register-btn");


    if (loginBtn) {

        loginBtn.addEventListener("click", function () {

            window.location.href = "/Harvestly/Controller/Buyer/AuthController.php";

        });

    }


    if (registerBtn) {

        registerBtn.addEventListener("click", function () {

            window.location.href = "/Harvestly/Controller/Buyer/RegistrationController.php";

        });

    }

});