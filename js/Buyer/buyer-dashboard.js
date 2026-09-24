document.addEventListener("DOMContentLoaded", function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const mobileMenuButton =
        document.getElementById("mobileMenuButton");

    const mobileNav =
        document.getElementById("mobileNav");

    const notificationButton =
        document.getElementById("notificationButton");

    const searchInput =
        document.getElementById("searchInput");

    const searchButton =
        document.getElementById("searchButton");


    /*
    |--------------------------------------------------------------------------
    | MOBILE MENU
    |--------------------------------------------------------------------------
    */

    if (
        mobileMenuButton &&
        mobileNav
    ) {

        mobileMenuButton.addEventListener(
            "click",
            function () {

                mobileNav.classList.toggle("show");

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE LINKS
    |--------------------------------------------------------------------------
    */

    if (mobileNav) {

        const mobileLinks =
            mobileNav.querySelectorAll("a");


        mobileLinks.forEach(
            function (link) {

                link.addEventListener(
                    "click",
                    function () {

                        mobileNav.classList.remove(
                            "show"
                        );

                    }
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH FUNCTION
    |--------------------------------------------------------------------------
    */

    function performSearch() {

        if (!searchInput) {
            return;
        }


        const search =
            searchInput.value.trim();


        /*
        | Empty search
        */

        if (search === "") {

            window.location.href =
                "../../Controller/Buyer/ProductController.php";

            return;
        }


        /*
        | Search Products
        */

        window.location.href =
            "../../Controller/Buyer/ProductController.php?search=" +
            encodeURIComponent(search);

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH BUTTON
    |--------------------------------------------------------------------------
    */

    if (searchButton) {

        searchButton.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

                performSearch();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH ENTER KEY
    |--------------------------------------------------------------------------
    */

    if (searchInput) {

        searchInput.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Enter"
                ) {

                    event.preventDefault();

                    performSearch();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH ICON HOVER/CURSOR
    |--------------------------------------------------------------------------
    */

    if (searchButton) {

        searchButton.style.cursor =
            "pointer";

    }


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    if (notificationButton) {

        notificationButton.addEventListener(
            "click",
            function () {

                window.location.href =
                    "../../Controller/Buyer/NotificationsController.php";

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE MOBILE MENU OUTSIDE
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        "click",
        function (event) {

            if (
                !mobileNav ||
                !mobileMenuButton
            ) {
                return;
            }


            const insideMenu =
                mobileNav.contains(
                    event.target
                );


            const menuButton =
                mobileMenuButton.contains(
                    event.target
                );


            if (
                !insideMenu &&
                !menuButton
            ) {

                mobileNav.classList.remove(
                    "show"
                );

            }

        }
    );


});