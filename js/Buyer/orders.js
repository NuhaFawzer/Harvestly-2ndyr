document.addEventListener("DOMContentLoaded", function () {

    /*
     * MOBILE MENU
     */

    const mobileNav =
        document.getElementById("mobileNav");

    if (mobileNav) {

        document.addEventListener(
            "click",
            function (event) {

                const menuButton =
                    document.querySelector(
                        ".mobile-menu"
                    );

                if (
                    !mobileNav.contains(event.target) &&
                    !menuButton?.contains(event.target)
                ) {

                    mobileNav.classList.remove(
                        "open"
                    );
                }

            }
        );
    }


    /*
     * ORDER CARDS
     */

    const cards =
        document.querySelectorAll(
            ".order-card"
        );

    cards.forEach(function (card) {

        card.addEventListener(
            "mouseenter",
            function () {
                card.classList.add("is-hovered");
            }
        );

        card.addEventListener(
            "mouseleave",
            function () {
                card.classList.remove("is-hovered");
            }
        );

    });

});

window.viewTracking = function(orderId) {
    window.location.href = '/Harvestly/Controller/Buyer/OrderTrackingController.php?id=' + encodeURIComponent(orderId);
};
