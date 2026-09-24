document.addEventListener("DOMContentLoaded", function () {


    const quantityInput =
        document.getElementById("quantity");


    const decreaseBtn =
        document.getElementById("decreaseBtn");


    const increaseBtn =
        document.getElementById("increaseBtn");


    const addCartBtn =
        document.getElementById("addCartBtn");


    const buyNowBtn =
        document.getElementById("buyNowBtn");


    const favoriteBtn =
        document.getElementById("favoriteBtn");


    const destinationDistrict =
        document.getElementById("destinationDistrict");


    const mainProductImage =
        document.getElementById("mainProductImage");


    /*
    |--------------------------------------------------------------------------
    | UPDATE LINKS
    |--------------------------------------------------------------------------
    */

    function updateCartLinks() {

        if (!quantityInput) {
            return;
        }


        let quantity =
            parseInt(
                quantityInput.value
            ) || 1;


        const max =
            parseInt(
                quantityInput.max
            ) || 999999;


        if (quantity < 1) {
            quantity = 1;
        }


        if (quantity > max) {
            quantity = max;
        }


        quantityInput.value =
            quantity;


        if (addCartBtn) {

            const url =
                new URL(
                    addCartBtn.href,
                    window.location.origin
                );


            url.searchParams.set(
                "qty",
                quantity
            );


            addCartBtn.href =
                url.toString();

        }


        if (buyNowBtn) {

            const url =
                new URL(
                    buyNowBtn.href,
                    window.location.origin
                );


            url.searchParams.set(
                "qty",
                quantity
            );


            buyNowBtn.href =
                url.toString();

        }

        if (destinationDistrict) {
            const district = destinationDistrict.value;
            if (district) {
                [addCartBtn, buyNowBtn].forEach(function (button) {
                    if (!button) return;
                    const link = new URL(button.href, window.location.origin);
                    link.searchParams.set("destination_district", district);
                    button.href = link.toString();
                });
            }
        }

    }


    /*
    |--------------------------------------------------------------------------
    | DECREASE
    |--------------------------------------------------------------------------
    */

    if (decreaseBtn) {

        decreaseBtn.addEventListener(
            "click",
            function () {

                let quantity =
                    parseInt(
                        quantityInput.value
                    ) || 1;


                if (quantity > 1) {

                    quantity--;

                }


                quantityInput.value =
                    quantity;


                updateCartLinks();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | INCREASE
    |--------------------------------------------------------------------------
    */

    if (increaseBtn) {

        increaseBtn.addEventListener(
            "click",
            function () {

                let quantity =
                    parseInt(
                        quantityInput.value
                    ) || 1;


                const max =
                    parseInt(
                        quantityInput.max
                    ) || 999999;


                if (quantity < max) {

                    quantity++;

                }


                quantityInput.value =
                    quantity;


                updateCartLinks();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MANUAL INPUT
    |--------------------------------------------------------------------------
    */

    if (quantityInput) {

        quantityInput.addEventListener(
            "change",
            function () {

                updateCartLinks();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FAVORITE
    |--------------------------------------------------------------------------
    */

    if (favoriteBtn) {

        favoriteBtn.addEventListener(
            "click",
            function () {

                this.classList.toggle(
                    "active"
                );


                const icon =
                    this.querySelector(
                        ".material-symbols-outlined"
                    );


                if (
                    this.classList.contains(
                        "active"
                    )
                ) {

                    icon.textContent =
                        "favorite";

                } else {

                    icon.textContent =
                        "favorite_border";

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | THUMBNAILS
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(".thumbnail")
        .forEach(
            function (thumbnail) {

                thumbnail.addEventListener(
                    "click",
                    function () {

                        const image =
                            this.dataset.image;


                        if (
                            mainProductImage &&
                            image
                        ) {

                            mainProductImage.src =
                                image;

                        }


                        document
                            .querySelectorAll(
                                ".thumbnail"
                            )
                            .forEach(
                                function (item) {

                                    item.classList.remove(
                                        "selected"
                                    );

                                }
                            );


                        this.classList.add(
                            "selected"
                        );

                    }
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | MOBILE MENU
    |--------------------------------------------------------------------------
    */

    const mobileMenu =
        document.getElementById(
            "mobileMenu"
        );


    const mobileNav =
        document.getElementById(
            "mobileNav"
        );


    if (
        mobileMenu &&
        mobileNav
    ) {

        mobileMenu.addEventListener(
            "click",
            function () {

                mobileNav.classList.toggle(
                    "show"
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | TABS
    |--------------------------------------------------------------------------
    */

    const tabs =
        document.querySelectorAll(
            ".tab"
        );


    const contents =
        document.querySelectorAll(
            ".tab-content"
        );


    tabs.forEach(
        function (tab) {

            tab.addEventListener(
                "click",
                function () {

                    const target =
                        this.dataset.tab;


                    tabs.forEach(
                        function (item) {

                            item.classList.remove(
                                "active"
                            );

                        }
                    );


                    contents.forEach(
                        function (content) {

                            content.classList.remove(
                                "active"
                            );

                        }
                    );


                    this.classList.add(
                        "active"
                    );


                    const targetContent =
                        document.getElementById(
                            target
                        );


                    if (targetContent) {

                        targetContent.classList.add(
                            "active"
                        );

                    }

                }
            );

        }
    );


    if (destinationDistrict) {
        destinationDistrict.addEventListener("change", updateCartLinks);
    }


    [addCartBtn, buyNowBtn].forEach(function (button) {
        if (!button) return;
        button.addEventListener("click", function (event) {
            if (destinationDistrict && !destinationDistrict.value) {
                event.preventDefault();
                destinationDistrict.focus();
                destinationDistrict.setCustomValidity("Please select your destination district.");
                destinationDistrict.reportValidity();
            } else if (destinationDistrict) {
                destinationDistrict.setCustomValidity("");
            }
        });
    });


    /*
    |--------------------------------------------------------------------------
    | INITIAL
    |--------------------------------------------------------------------------
    */

    updateCartLinks();

});