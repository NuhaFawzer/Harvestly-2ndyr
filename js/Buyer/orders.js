const HARVESTLY_BASE_URL = (() => {
    const pathname = window.location.pathname || "";
    const controllerMarker = "/Controller/";
    const markerIndex = pathname.indexOf(controllerMarker);

    if (markerIndex >= 0) {
        return pathname.slice(0, markerIndex);
    }

    const segments = pathname.split("/").filter(Boolean);
    const first = segments[0] || "";
    const applicationFolders = new Set([
        "Controller", "View", "buyer", "auth", "admin", "farmer", "courier", "config", "includes"
    ]);

    if (!first || applicationFolders.has(first)) {
        return "";
    }

    return "/" + first;
})();

const buyerControllerUrl = (controller, query = "") =>
    `${HARVESTLY_BASE_URL}/Controller/Buyer/${controller}${query ? `?${query}` : ""}`;

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
    window.location.href = buyerControllerUrl('OrderTrackingController.php', 'id=' + encodeURIComponent(orderId));
};
