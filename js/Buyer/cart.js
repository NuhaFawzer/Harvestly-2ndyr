document.addEventListener("DOMContentLoaded", function () {

    const controllerUrl =
        "/Harvestly/Controller/Buyer/CartController.php";


    const itemCount =
        document.getElementById("itemCount");

    const subtotal =
        document.getElementById("subtotal");

    const deliveryFee =
        document.getElementById("deliveryFee");

    const grandTotal =
        document.getElementById("grandTotal");

    const emptyCart =
        document.getElementById("emptyCart");

    const checkoutButton =
        document.getElementById("checkoutButton");


    /*
    |--------------------------------------------------------------------------
    | MONEY
    |--------------------------------------------------------------------------
    */

    function formatMoney(value) {

        return Number(value).toLocaleString(
            "en-LK",
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEND TO CONTROLLER
    |--------------------------------------------------------------------------
    */

    async function sendCartAction(action, id) {

        const formData =
            new FormData();

        formData.append(
            "action",
            action
        );

        if (id !== null) {

            formData.append(
                "id",
                id
            );
        }


        try {

            const response =
                await fetch(
                    controllerUrl,
                    {
                        method: "POST",
                        body: formData
                    }
                );


            if (!response.ok) {

                throw new Error(
                    "Server returned an error."
                );
            }


            const data =
                await response.json();


            if (!data.success) {

                throw new Error(
                    data.message ||
                    "Cart operation failed."
                );
            }


            return data;

        } catch (error) {

            console.error(
                "Cart Error:",
                error
            );

            alert(
                "Unable to update the cart."
            );

            return null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SUMMARY
    |--------------------------------------------------------------------------
    */

    function updateSummary(data) {

        itemCount.textContent =
            data.quantity;


        subtotal.textContent =
            "LKR " +
            formatMoney(
                data.subtotal
            );


        deliveryFee.textContent =
            "LKR " +
            formatMoney(
                data.deliveryFee
            );


        grandTotal.textContent =
            "LKR " +
            formatMoney(
                data.total
            );


        if (data.cart.length === 0) {

            emptyCart.style.display =
                "block";

            checkoutButton.disabled =
                true;

        } else {

            emptyCart.style.display =
                "none";

            checkoutButton.disabled =
                false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SINGLE ITEM
    |--------------------------------------------------------------------------
    */

    function updateItem(data, id) {

        const card =
            document.querySelector(
                '.cart-item[data-item-id="' +
                id +
                '"]'
            );


        const item =
            data.cart.find(
                function (cartItem) {

                    return (
                        Number(cartItem.id) ===
                        Number(id)
                    );
                }
            );


        /*
        | ITEM WAS REMOVED
        */

        if (!item) {

            if (card) {
                card.remove();
            }

            updateSummary(data);

            return;
        }


        if (!card) {
            return;
        }


        /*
        | QUANTITY
        */

        const quantityElement =
            card.querySelector(
                ".quantity"
            );


        quantityElement.textContent =
            item.quantity;


        /*
        | ITEM TOTAL
        */

        const itemTotal =
            card.querySelector(
                ".item-total-price"
            );


        itemTotal.textContent =
            formatMoney(
                Number(item.quantity) *
                Number(item.price)
            );


        updateSummary(data);
    }


    /*
    |--------------------------------------------------------------------------
    | PLUS BUTTON
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(".increase")
        .forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    async function () {

                        const id =
                            this.dataset.id;


                        if (!id) {

                            console.error(
                                "No product ID found."
                            );

                            return;
                        }


                        this.disabled = true;


                        const data =
                            await sendCartAction(
                                "increase",
                                id
                            );


                        this.disabled = false;


                        if (data) {

                            updateItem(
                                data,
                                id
                            );
                        }
                    }
                );
            }
        );


    /*
    |--------------------------------------------------------------------------
    | MINUS BUTTON
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(".decrease")
        .forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    async function () {

                        const id =
                            this.dataset.id;


                        if (!id) {

                            console.error(
                                "No product ID found."
                            );

                            return;
                        }


                        this.disabled = true;


                        const data =
                            await sendCartAction(
                                "decrease",
                                id
                            );


                        this.disabled = false;


                        if (data) {

                            updateItem(
                                data,
                                id
                            );
                        }
                    }
                );
            }
        );


    /*
    |--------------------------------------------------------------------------
    | DELETE BUTTON
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(".remove-item")
        .forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    async function () {

                        const id =
                            this.dataset.remove;


                        if (!id) {
                            return;
                        }


                        const confirmed =
                            window.confirm(
                                "Remove this item from your cart?"
                            );


                        if (!confirmed) {
                            return;
                        }


                        this.disabled = true;


                        const data =
                            await sendCartAction(
                                "remove",
                                id
                            );


                        if (data) {

                            updateItem(
                                data,
                                id
                            );
                        }

                    }
                );
            }
        );


    /*
    |--------------------------------------------------------------------------
    | CONTINUE SHOPPING
    |--------------------------------------------------------------------------
    */

    const continueShopping =
        document.querySelector(
            ".continue-shopping"
        );


    if (continueShopping) {

        continueShopping.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

                window.location.href =
                    "/Harvestly/Controller/Buyer/ProductController.php";

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | START SHOPPING
    |--------------------------------------------------------------------------
    */

    const startShopping =
        document.querySelector(
            ".empty-cart a"
        );


    if (startShopping) {

        startShopping.addEventListener(
            "click",
            function (event) {

                event.preventDefault();

                window.location.href =
                    "/Harvestly/Controller/Buyer/ProductController.php";

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    if (checkoutButton) {

        checkoutButton.addEventListener(
            "click",
            function () {

                const count =
                    Number(
                        itemCount.textContent
                    );


                if (count <= 0) {

                    alert(
                        "Your cart is empty."
                    );

                    return;
                }


                window.location.href =
                    "/Harvestly/Controller/Buyer/CheckoutController.php";

            }
        );
    }

});